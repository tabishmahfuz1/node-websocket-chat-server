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
	return clientLists.get(connection.group);
}

var getClientConnections = function ({group, clientId}) {
	clientLists.get((group || defaultGroup)).get(clientId)
} 

module.exports = {
	addClientConnection,
	all,
	getClientsInGroup,
	getClientConnections,
	defaultGroup
}