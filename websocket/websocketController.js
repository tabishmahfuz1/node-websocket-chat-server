const clientLists = require('./clientList');

var broadcastNewClientNotification = function (clientConn) {
    let msg = { type: 'new_peer', data: clientConn.userID };
    broadcastInGroup({clientConn, msg});
}

var broadcastInGroup = function ({ clientConn, group, msg }) {
	if((!clientConn && !group) || !msg) {
		return false;
	}
	for (var conn of clientLists.getClientsInGroup(group || clientConn.group).values()) {
      	for(let i = 0; i< conn.length; i++) {
        	conn[i].sendUTF(JSON.stringify(msg));
      	}
    }
}

var processPendingMessages = async function(clientConn){
	try {
  		let pendingMessages = 
  			await ChatMessage.checkPendingMessages(clientConn.userID, clientConn.group);
  	} catch (err) {
  		console.error("DB-Error", err);
  	}
    for (let i = 0; i < pendingMessages.length; i++) {
      	try {
      		let history  = ChatMessage.getChatHistory(clientConn.userID, 
      							pendingMessages[i].author, clientConn.group);
      	} catch(err) {
      		console.error("DB-Error", err);
      	}
    	clientConn.send(
    		JSON.stringify({
    			type: "chat_history", 
    			data: history, 
    			history_of: pendingMessages[i].author
    		})
    	);
    	ChatMessage.markMessagesRead(pendingMessages[i].author, 
    		clientConn.userID, clientConn.group);
    }
}

var handleClientRequest = async function (request) {
	console.log((new Date()) + ' Connection from origin '
      + request.origin + '.');
  	// accept connection - you should check 'request.origin' to
  	// make sure that client is connecting from your website
  	// (http://en.wikipedia.org/wiki/Same_origin_policy)
  	var connection = request.accept(null, request.origin);

  	var queryParams = request.resourceURL.query;
  
  	connection.userID     = request.resourceURL.query;
  	connection.group      = request.resourceURL.query.group;
  
  	let arr_index = clientLists.addClientConnection(connection);

  	// for (var [cliendID, conn] of clientLists) {
  	connection.sendUTF(
    	JSON.stringify({type: 'users_list', 
      		data: Array.from( clientLists.get(connection.group).keys() )}));

	if(arr_index == 0) {         //First Connection from this User
	    broadcastNewClientNotification(connection.userID);
	}

  	processPendingMessages(connection);

  	// TODO: Add OnMessage, OnClose and OnError Handlers
  	connection.on('message', messageHandler);
  	connection.on('close', connectionCloseHandler);
}

let sendChatHistory = async (clientConn, historyWith, clientGroup) => {
	clientGroup = clientGroup || clientLists.defaultGroup;
	if(!historyWith || ) return false;

    let data = await ChatMessage.getChatHistory(clientConn.userID, historyWith, group);
    connection.send(JSON.stringify({
    	type: "chat_history", data: data, history_of: recipient
    }));
}

function prepareMsgResponseObject({ type, data, recipient, clientConn }){
	return {
          	time: 		(new Date()).getTime(),
          	type: 		type,
          	text: 		data,
          	author: 	clientConn.userID,
          	recipient: 	recipient,
          	group:  	clientConn.group,
          	delivered: 	0
        };
}

var messageHandler = async function (message){
	if (message.type === 'utf8') { // accept only text
	    var messageObj = JSON.parse(message.utf8Data);

	    if (messageObj.type === 'messageHistory') {
	      	sendChatHistory(this, messageObj.recipient, this.group);
	    }
	    else if(messageObj.type == "file_link"){
	        console.log("Received File from " + userID 
	          + " for " + messageObj.recipient);
	        var obj = prepareMsgResponseObject({ 
	        	type: 'file_link', 
	        	data: message.utf8Data,
	        	recipient:  messageObj.recipient,
	        	clientConn: this
	        });
	        
	        sendMessage(obj);
	    }
	    else { // log and broadcast the message
	        // console.log((new Date()) + ' Received Message from ' + userID + ': ' + messageObj.msg + (messageObj.recipient? ("| for " + messageObj.recipient) : ""));
	        if(!messageObj.msg)
	          return;
	      	var obj = prepareMsgResponseObject({ 
	        	type: 'message', 
	        	data: htmlEntities(message.utf8Data),
	        	recipient:  messageObj.recipient,
	        	clientConn: this
	        });
	        sendMessage(obj);
	    }
    }
    else{ // Binary Message
      	console.log(message.binaryData);
      	connection.send(message.binaryData);
    }
}


let sendMessage = (obj) => {
	// console.log("Sending")
	var json = JSON.stringify(obj);
	var this_author_conns = clientLists.getClientConnections({clientId: obj.author, group: obj.group});
	if(obj.recipient){
	    
	    if(clientLists.getClientConnections({clientId: obj.recipient, obj.group})) {
	      	obj.delivered = 1;
	      	sendToClient({ group: obj.group, client: obj.recipient, msg: json });
	    }

	    sendToClient({ group: obj.group, client: obj.author, msg: json });
	}
	else {
	  	broadcastInGroup({ group: obj.group, msg: json });
	}
	    
	ChatMessage.create(obj)
	.catch(error => {
	    console.log(error);
	});
}

function sendToClient({group, client, msg}){
  var client_conns = clientLists.getClientConnections({ group, clientId: client });
  // console.log(client_conns)
  for(let i = 0; i < client_conns.length; i++) {
    console.log(i)
    client_conns[i].sendUTF(msg);
  }
}

/**
 * Helper function for escaping input strings
 */
function htmlEntities(str) {
  return String(str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

var connectionCloseHandler = function (clientConn) {
	if (clientConn.userID !== false) {
      // console.log((new Date()) + " Peer " + userID + " disconnected.");
      // remove user from the list of connected clients
      // console.log(clientLists.get(queryParams.group).values());
      var arr = clientLists.get(clientConn.group).get(userID);
      if(arr.length == 1){
        clientLists.get(clientConn.group).delete(userID);
        // console.log("Users", clientLists.get(queryParams.group).keys());
        let msg = JSON.stringify({type: 'disconnected_peer', data: clientConn.userID}),
            group = clientConn.group;
        for (var client of clientLists.get(clientConn.group).keys()) {
          sendToClient({group, client, msg});
        }
      } else {
        var new_arr = arr.splice(arr_index, 1);
        // console.log("UserLength", new_arr.length);
        // console.log("TPYE", new_arr);
        clientLists.get(clientConn.group).set(clientConn.userID, new_arr);
      }
    }
}

module.exports = { handleClientRequest };