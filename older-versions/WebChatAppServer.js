"use strict";
// Optional. You will see this name in eg. 'ps' or 'top' command
process.title = 'node-chat';
// Port where we'll run the websocket server
var webSocketsServerPort = 1337;
// websocket and http servers
var webSocketServer = require('websocket').server;
var http = require('http');
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
  var userID = queryParams.user_id;
  if(clientLists.has(queryParams.subdomain))
    if(clientLists.get(queryParams.subdomain).has(userID)){
      request.reject();
      return false;
    }
    else
      var connection = request.accept(null, request.origin);
  else
    var connection = request.accept(null, request.origin); 
  // var userColor = false;
  

  if(!clientLists.has(queryParams.subdomain)) {
    clientLists.set(queryParams.subdomain, new Map());
    // clientIndexes[queryParams.subdomain] = [];
  }
  clientLists.get(queryParams.subdomain).set(queryParams.user_id, connection);

  // for (var [cliendID, conn] of clientLists) {
  connection.sendUTF(
    JSON.stringify({type: 'users_list', 
      data: Array.from( clientLists.get(queryParams.subdomain).keys() )}));
  for (var conn of clientLists.get(queryParams.subdomain).values()) {
    conn.sendUTF(
        JSON.stringify({type: 'new_peer', data: userID}));
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
  })
  .catch((err) => {
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
        sendToClient(obj);
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
        sendToClient(obj);
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
      clientLists.get(queryParams.subdomain).delete(userID);
      for (var conn of clientLists.get(queryParams.subdomain).values()) {
        conn.sendUTF(
            JSON.stringify({type: 'disconnected_peer', data: userID}));
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


function sendToClient(obj) {
  console.log("Sending")
  var json = JSON.stringify(obj);
  if(obj.recipient){
    if(clientLists.get(obj.company).has(obj.recipient)){
      obj.delivered = 1;
      clientLists.get(obj.company).get(obj.recipient).sendUTF(json);
      clientLists.get(obj.company).get(obj.author).sendUTF(json);
    }
    else{
      clientLists.get(obj.company).get(obj.author).sendUTF(json);
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