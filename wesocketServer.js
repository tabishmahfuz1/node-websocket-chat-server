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

var clientLists = [ ];

var clientIndexes = [ ];
/**
 * Helper function for escaping input strings
 */
 function htmlEntities(str) {
  return String(str)
  .replace(/&/g, '&amp;').replace(/</g, '&lt;')
  .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
// Array with some colors
var colors = [ 'red', 'green', 'blue', 'magenta', 'purple', 'plum', 'orange' ];
// ... in random order
colors.sort(function(a,b) { return Math.random() > 0.5; } );
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
  // console.log((new Date()) + ' Connection from origin ' + request.origin + '.');
  // accept connection - you should check 'request.origin' to
  // make sure that client is connecting from your website
  // (http://en.wikipedia.org/wiki/Same_origin_policy)
  var connection = request.accept(null, request.origin); 
  var userID = false;
  var userColor = false;
  var queryParams = request.resourceURL.query;

  if(!clientLists[queryParams.subdomain]) {
    clientLists[queryParams.subdomain] = [];
    clientIndexes[queryParams.subdomain] = [];
  }
  clientLists[queryParams.subdomain][queryParams.user_id] = connection;

  // we need to know client index to remove them on 'close' event
  var index = clientIndexes[queryParams.subdomain].push(queryParams.user_id) - 1; 
  // var query = url.parse(request.url,true).query;

  // console.log((new Date()) + ' Connection accepted.');
  for (var i=0; i < clientIndexes[queryParams.subdomain].length; i++) {
    if(clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]])
      clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]].sendUTF(
        JSON.stringify({type: 'users_list', data: clientIndexes[queryParams.subdomain]}));
  }


  userID = queryParams.user_id;
  userColor = colors.shift();


  ChatMessage.checkPendingMessages(userID)
  .then((pendingMessages) => {
    // console.log(pendingMessages);
    // console.log(pendingMessages[0]);
    // console.log(pendingMessages[1]);
    if(pendingMessages.length){
      for (var i = 0; i < pendingMessages.length; i++) {
        // var theAuhtor = pendingMessages[i].author;
        ChatMessage.getChatHistory(userID, pendingMessages[i].author).then((history) => {
          // console.log(history);
          // var thisAuthor  = theAuhtor;
          // console.log(history.recipient);
          connection.send(JSON.stringify({type: "chat_history", data: history.history, history_of: history.recipient}));
          ChatMessage.markMessagesRead(history.recipient, userID)
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
    // if (message.type === 'utf8') { // accept only text
    // first message sent by user is their name
    var messageObj = JSON.parse(message.utf8Data);
    if (messageObj.type === 'messageHistory') {
      if(messageObj.recipient){
        ChatMessage.getChatHistory(userID, messageObj.recipient).then((data) => {
         connection.send(JSON.stringify({type: "chat_history", data: data.history, history_of: messageObj.recipient}));
       });
      }
      } else { // log and broadcast the message
        // console.log((new Date()) + ' Received Message from ' + userID + ': ' + messageObj.msg + (messageObj.recipient? ("| for " + messageObj.recipient) : ""));
        
        // we want to keep history of all sent messages
        var obj = {
          time: (new Date()).getTime(),
          text: htmlEntities(messageObj.msg),
          author: userID,
          recipient: messageObj.recipient,
          color: userColor, 
          delivered: 0
        };
        // history.push(obj);
        // history = history.slice(-100);
        
        
        // broadcast message to all connected clients
        var json = JSON.stringify({ type:'message', data: obj });
        if(obj.recipient){
          if(!clientLists[queryParams.subdomain][obj.recipient]){
            clientLists[queryParams.subdomain][queryParams.user_id].sendUTF(json);
          }
          else{
            obj.delivered = 1;
            clientLists[queryParams.subdomain][obj.recipient].sendUTF(json);
            clientLists[queryParams.subdomain][queryParams.user_id].sendUTF(json);
          }
          // console.log(clientLists);
          // console.log(clientLists[obj.recipient]);
        }
        else
          for (var i=0; i < clientIndexes[queryParams.subdomain].length; i++) {
            if(clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]])
              clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]].sendUTF(json);
            // clients[i].sendUTF();
            // console.log(index);
          }

        //Saving Msg to Database
        var msgToSave = obj;
        delete msgToSave.userColor;
        msgToSave.company = queryParams.subdomain;
        const message = ChatMessage.create(obj).then(() => {
          console.log("Message Saved to DB");
        }).catch(error => {
          console.log(error);
        });
      }
    // }
  });
  // user disconnected
  connection.on('close', function(connection) {
    if (userID !== false && userColor !== false) {
      console.log((new Date()) + " Peer "
        + userID + " disconnected.");
      // remove user from the list of connected clients
      clientIndexes[queryParams.subdomain].splice(index, 1);
      console.log(clientIndexes);
      delete clientLists[queryParams.subdomain][queryParams.user_id];
      // delete clientIndexes[queryParams.subdomain][queryParams.user_id];
      for (var i=0; i < clientIndexes[queryParams.subdomain].length; i++) {
        if(clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]])
          clientLists[queryParams.subdomain][clientIndexes[queryParams.subdomain][i]].sendUTF(
            JSON.stringify({type: 'users_list', data: clientIndexes[queryParams.subdomain]}));
      }
      // push back user's color to be reused by another user
      colors.push(userColor);
    }
  });
});