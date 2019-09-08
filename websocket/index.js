var WebSocketServer = require('websocket').server;
const wsController = require('./websocketController');

var attach = function(httpServer) {
	var WSServer = new WebSocketServer({httpServer});
	WSServer.on('request', wsController.handleClientRequest);
}

module.exports =  { attach };