const defaultGroup = '_default';
var clientLists = new Map();

var addClientConnection	= function (clientConn) {
	let userID = clientConn.userID,
		group  = clientConn.group || defaultGroup;
	if(!clientLists.has(group)) {
	    clientLists.set(group, new Map());
	 }

	if(!clientLists.get(group).has(userID)){
		clientLists.get(group).set(userID, []);
	}

	let userCollection 		= clientLists.get(group).get(userID);
	clientConn.arr_index 	= userCollection.length;
	var arr_index   		= clientLists.get(group).get(userID)
								.push(clientConn) - 1;
	// connection.arr_index  = arr_index;
	return arr_index;
}

var all = function () {
	return clientLists;
}

var getClientsInGroup 	= function (group) {
	group = group || defaultGroup;
	// console.log("CLG", group, clientLists.get(group));

	return clientLists.get(group);
}

var getClientConnections = function ({group, clientId}) {
	clientLists.get((group || defaultGroup)).get(clientId)
}

var setClientConnections = function({group, clientId, newList}){
	//TODO: Check type of newList to ensure that its an arrays
	clientLists.get(group).set(clientId, newList);
}

var removeClientConnection = function(clientConn) {
	// console.log("Conn", clientConn)
	var arr = clientLists.get(clientConn.group).get(clientConn.userID);
	arr.splice(clientConn.arr_index, 1);
	if(!arr.length) {
		clientLists.get(clientConn.group).delete(clientConn.userID);
	}
	return arr.length;
}

var removeClient = function(clientId, group) {
	clientLists.get(group || defaultGroup).delete(clientId);
}

module.exports = {
	addClientConnection,
	all,
	getClientsInGroup,
	getClientConnections,
	defaultGroup,
	removeClientConnection,

}