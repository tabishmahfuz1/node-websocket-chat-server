"use strict";
// Optional. You will see this name in eg. 'ps' or 'top' command
process.title = 'node-chat';
// Port where we'll run the websocket server
var webSocketsServerPort = 1337;
// websocket and http servers
var webSocketServer = require('websocket').server;
var http = require('http');
var url = require('url');
const db = require('./models/index.js');
var ChatMessage = db.ChatMessage;
// var url = require('url');
/**
 * Global variables
 */
// latest 100 messages
var history = [ ];
// list of currently connected clients (users)

var clientLists = new Map();

// var clientIndexes = [ ];
/**
 * Helper function for escaping input strings
 */
function htmlEntities(str) {
  return String(str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
/**
 * HTTP server
 */
var server = http.createServer(function(request, response) {
  // Not important for us. We're writing WebSocket server,
  // not HTTP server
  response.writeHead(200, {'Content-Type': 'text/html'});
  // console.log(request.url)
  var q = url.parse(request.url, true);
  // console.log(q);
  if(q.pathname == "/companies"){
    response.write("<h4>Companies Accessing the Server Currently:</h4>");
    let companies = "<ol><li>" + Array.from( clientLists.keys() ).join("</li><li>") + "</li></ol>";
    response.write(companies);
    response.end();
  } else if(q.pathname == "/users"){
    let domain = q.query.company;
    response.write("<h4>Users connected from "+ domain +":</h4>");
    let users = clientLists.get(domain);
    if(!users){
      response.write("NO USERS FOR THIS COMPANY");
      response.end();
    } else {
      let res_string = "<ol><li>" + Array.from( users.keys() ).join("</li><li>") + "</li></ol>";
      response.write(res_string);
      response.end();
    }
    

  }else{
    response.write('You shall not <b>PASS</b><br>' + clientLists.size); //write a response to the client
    response.end(); //end the response
  }

  
});
server.listen(webSocketsServerPort, function() {
  console.log((new Date()) + " Server is listening on port "
      + webSocketsServerPort);
});
/**
 * WebSocket server
 */
var wsServer = new webSocketServer({
  // WebSocket server is tied to a HTTP server. WebSocket
  // request is just an enhanced HTTP request. For more info 
  // http://tools.ietf.org/html/rfc6455#page-6
  httpServer: server
});


// This callback function is called every time someone
// tries to connect to the WebSocket server
wsServer.on('request', function(request) {
  console.log((new Date()) + ' Connection from origin '
      + request.origin + '.');
  // accept connection - you should check 'request.origin' to
  // make sure that client is connecting from your website
  // (http://en.wikipedia.org/wiki/Same_origin_policy)
  var queryParams = request.resourceURL.query;
  var userID      = queryParams.user_id;

  var connection = request.accept(null, request.origin);
  if(!clientLists.has(queryParams.subdomain)) {
    clientLists.set(queryParams.subdomain, new Map());
  }

  if(!clientLists.get(queryParams.subdomain).has(userID)){
    clientLists.get(queryParams.subdomain).set(userID, []);
  }
   var arr_index   = clientLists.get(queryParams.subdomain).get(userID).push(connection) - 1;

  // for (var [cliendID, conn] of clientLists) {
  connection.sendUTF(
    JSON.stringify({type: 'users_list', 
      data: Array.from( clientLists.get(queryParams.subdomain).keys() )}));

  if(arr_index == 0) {         //First Connection from this User
    for (var conn of clientLists.get(queryParams.subdomain).values()) {
      for(let i = 0; i< conn.length; i++) {
        conn[i].sendUTF(
          JSON.stringify({type: 'new_peer', data: userID}));
      }
    }
  }
  

  ChatMessage.checkPendingMessages(userID, queryParams.subdomain)
  // .then(sendPendingMessages)
  .then((pendingMessages) => {
  if(pendingMessages.length){
    for (let i = 0; i < pendingMessages.length; i++) {
      ChatMessage.getChatHistory(userID, pendingMessages[i].author, queryParams.subdomain).then((history) => {
         connection.send(JSON.stringify({type: "chat_history", data: history, history_of: pendingMessages[i].author}));
         ChatMessage.markMessagesRead(pendingMessages[i].author, userID, queryParams.subdomain)
         .catch((err) => {
            console.log("Error while Updating Delivery");
            console.log(err);
         });
      });
    }
  }
}
).catch((err) => {
  console.log("Unexpected error Catched.");
  console.log(err);
});
  
  // send back chat history
  // user sent some message
  connection.on('message', function(message) {
    // console.log(message);
    if (message.type === 'utf8') { // accept only text
    var messageObj = JSON.parse(message.utf8Data);
     if (messageObj.type === 'messageHistory') {
      if(messageObj.recipient){
        ChatMessage.getChatHistory(userID, messageObj.recipient, queryParams.subdomain).then((data) => {
           connection.send(JSON.stringify({type: "chat_history", data: data, history_of: messageObj.recipient}));
        });
      }
      }

      else if(messageObj.type == "file_link"){
        console.log("Received File from " + userID 
          + " for " + messageObj.recipient);
        var obj = {
          time: (new Date()).getTime(),
          type: 'file_link',
          text: message.utf8Data,
          author: userID,
          recipient: messageObj.recipient,
          company:  queryParams.subdomain,
          delivered: 0
        };
        sendMessage(obj);
      }

       else { // log and broadcast the message
        // console.log((new Date()) + ' Received Message from ' + userID + ': ' + messageObj.msg + (messageObj.recipient? ("| for " + messageObj.recipient) : ""));
        if(!messageObj.msg)
          return;
        var obj = {
          time: (new Date()).getTime(),
          type: 'message',
          text: htmlEntities(messageObj.msg),
          author: userID,
          recipient: messageObj.recipient,
          company:  queryParams.subdomain,
          delivered: 0
        };
        var json = JSON.stringify({ type:'message', data: obj });
        sendMessage(obj);
      }
    }
    else{ // Binary Message
      console.log(message.binaryData);
      connection.send(message.binaryData);
    }
  });
  // user disconnected
  connection.on('close', function(connection) {
    if (userID !== false) {
      // console.log((new Date()) + " Peer " + userID + " disconnected.");
      // remove user from the list of connected clients
      // console.log(clientLists.get(queryParams.subdomain).values());
      var arr = clientLists.get(queryParams.subdomain).get(userID);
      if(arr.length == 1){
        clientLists.get(queryParams.subdomain).delete(userID);
        // console.log("Users", clientLists.get(queryParams.subdomain).keys());
        for (var user_id of clientLists.get(queryParams.subdomain).keys()) {
          sendToClient(queryParams.subdomain, user_id, JSON.stringify({type: 'disconnected_peer', data: userID}));
        }
      } else {
        var new_arr = arr.splice(arr_index, 1);
        // console.log("UserLength", new_arr.length);
        // console.log("TPYE", new_arr);
        clientLists.get(queryParams.subdomain).set(userID, new_arr);
      }
    }
  });
});

var sendPendingMessages = (pendingMessages) => {
  if(pendingMessages.length){
    for (let i = 0; i < pendingMessages.length; i++) {
      ChatMessage.getChatHistory(userID, pendingMessages[i].author, queryParams.subdomain).then((history) => {
         connection.send(JSON.stringify({type: "chat_history", data: history, history_of: pendingMessages[i].author}));
         ChatMessage.markMessagesRead(pendingMessages[i].author, userID, queryParams.subdomain)
         .catch((err) => {
            console.log("Error while Updating Delivery");
            console.log(err);
         });
      });
    }
  }
}


function sendMessage(obj) {
  // console.log("Sending")
  var json = JSON.stringify(obj);
  var this_author_conns = clientLists.get(obj.company).get(obj.author);
  if(obj.recipient){
    if(clientLists.get(obj.company).has(obj.recipient)){
      obj.delivered = 1;
      // clientLists.get(obj.company).get(obj.recipient).sendUTF(json);
      sendToClient(obj.company, obj.recipient, json);
      sendToClient(obj.company, obj.author, json);

    }
    else{
      // clientLists.get(obj.company).get(obj.author).sendUTF(json);
      sendToClient(obj.company, obj.author, json);
    }
  }
  else
    for (var conn of clientLists.get(obj.company).values()) {
      conn.sendUTF(json);
    }
  const message = ChatMessage.create(obj).then(() => {
    // console.log("Message Saved to DB");
  }).catch(error => {
    console.log(error);
  });
}

function sendToClient(company, client, msg){
  var client_conns = clientLists.get(company).get(client);
  // console.log(client_conns)
  for(let i = 0; i < client_conns.length; i++) {
    console.log(i)
    client_conns[i].sendUTF(msg);
  }
}