'use strict';
module.exports = (sequelize, DataTypes) => {
  const ChatMessages = sequelize.define('ChatMessage', {
    group: DataTypes.STRING,
    author: DataTypes.STRING,
    type: DataTypes.STRING,
    text: DataTypes.TEXT,
    recipient: DataTypes.STRING,
    delivered: DataTypes.BOOLEAN,
    time: DataTypes.DATE
  }, {
  	timestamps: false,
  	tableName: 'chat_messages',
  });

  ChatMessages.getChatHistory =  (author, recipient, group, offset = 0, limit = 50) => {
  	return sequelize.query('SELECT * FROM chat_messages WHERE ((author = ? AND recipient = ?) OR (author = ? AND recipient = ?)) AND `group` = ? ORDER BY id DESC LIMIT ? OFFSET ?',
	  { replacements: [author, recipient, recipient, author, group, limit, offset], type: sequelize.QueryTypes.SELECT }
	).then(function(data) {
	  return (data);
	});
  	/*ChatMessages.findAll({
  		offset: offset, 
          limit: limit,
          where: {
          	[Op.or]: {
          		[Op.and]: [{author: author}, {recipient: recipient}],
            	[Op.and]: [{author: recipient}, {recipient: author}]
          	}
          },
          order: [
            ['id', 'DESC'],
          ]}
          ).then((data) => {
          	return data;
          });*/
  }

  ChatMessages.checkPendingMessages =  (recipient, group) => {
  	return sequelize.query('SELECT DISTINCT author FROM chat_messages WHERE `group` = ? AND recipient = ? AND delivered = 0',
	  { replacements: [group, recipient], type: sequelize.QueryTypes.SELECT }
	).then(function(data) {
	  return (data) ;
	});
  }

  ChatMessages.markMessagesRead =  (author, recipient, group) => {
  	return sequelize.query('UPDATE chat_messages SET delivered = 1 WHERE `group` = ? AND author = ? AND recipient = ?',
	  { replacements: [group, author, recipient], type: sequelize.QueryTypes.UPDATE }
	).then(function(data) {
	  return (data) ;
	});
  }

  ChatMessages.associate = function(models) {
    // associations can be defined here
  };
  return ChatMessages;
};