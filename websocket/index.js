var WebSocketServer = require('websocket').server;
const db = require('../models');
const wsController = require('./websocketController');
var ChatMessage = db.ChatMessage;

var WebSocketServer = function(httpServer) {
	var WSServer = new WebSocketServer({httpServer});
	WSServer.on('request', handleClientRequest);
}

module.exports = { WebSocketServer, clientLists }