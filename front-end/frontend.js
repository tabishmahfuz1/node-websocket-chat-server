$(function () {
  "use strict";
  // for better performance - to avoid searching in DOM
  var content = $('#content');
  var input = $('#input');
  var status = $('#status');
  // my name sent to the server
  var myName = prompt("Please enter your name", "Tabish");;
  var group = 'Default';
  // if user is running mozilla then use it's built-in WebSocket
  window.WebSocket = window.WebSocket || window.MozWebSocket;
  // if browser doesn't support WebSocket, just show
  // some notification and exit
  if (!window.WebSocket) {
    content.html($('<p>',
      { text:'Sorry, but your browser doesn\'t support WebSocket.'}
    ));
    input.hide();
    $('span').hide();
    return;
  }
  // open connection
  var connection = new WebSocket('ws://127.0.0.1:1337?user_id=' + myName + '&group=' + group);
  connection.onopen = function () {
    // first we want users to enter their names
    input.removeAttr('disabled');
    status.text(myName + ':');
  };
  connection.onerror = function (error) {
    // just in there were some problems with connection...
    content.html($('<p>', {
      text: 'Sorry, but there\'s some problem with your '
         + 'connection or the server is down.'
    }));
  };
  // most important part - incoming messages
  connection.onmessage = function (message) {
    // try to parse JSON message. Because we know that the server
    // always returns JSON this should work without any problem but
    // we should make sure that the massage is not chunked or
    // otherwise damaged.
    try {
      var json = JSON.parse(message.data);
    } catch (e) {
      console.log('Invalid JSON: ', message.data);
      return;
    }
    // NOTE: if you're not sure about the JSON structure
    // check the server source code above
    // first response from the server with user's color
    if (json.type === 'chat_history') { // entire message history
      // insert every single message to the chat window
      var user_index = "user_" + json.history_of;
      if(!hasInitialHistoryOf[user_index]){
        hasInitialHistoryOf[user_index] = true;
      }
      console.log(json);
      for (var i = json.data.length-1; i >= 0; i--) {
        if(json.data[i].type == "file_link")
          addFile(json.data[i], true);
        else if(json.data[i].author == my_id || json.data[i].recipient == my_id )
          addMessage(json.data[i].author, json.data[i].text,
              'red', new Date(json.data[i].time), json.data[i].recipient, false);
      }
    }
    else if(json.type === 'file_link'){
      // addFile(json);
    } 
    else if (json.type === 'ClientList'){
        console.log("User List Received");
        // console.log(json.data);
        refreshOnlineUsers(json.data);
    }
    else if (json.type == 'new_peer'){
      console.log("A new Peer Connected");
      // console.log(json.data);
      refreshOnlineUsers(json.data, 'new_peer');
    }
    else if (json.type == 'disconnected_peer'){
      console.log("A Peer Disconnected");
      // console.log(json.data);
      refreshOnlineUsers(json.data, 'disconnected_peer');
    }
    else if (json.type === 'message') { // it's a single message
      addMessage(json.author, json.text,
                 'red', new Date(json.time), json.recipient);
    } else {
      console.log('Hmm..., I\'ve never seen JSON like this:', json);
    }
  };
  /**
   * Send message when user presses Enter key
   */
  input.keydown(function(e) {
    if (e.keyCode === 13) {
      var msg = $(this).val();
      if (!msg) {
        return;
      }
      msg = JSON.stringify(
          { msg }
        ); 
      // send the message as an ordinary text
      connection.send(msg);
      $(this).val('');
      // disable the input field to make the user wait until server
      // sends back response
      // input.attr('disabled', 'disabled');
      // we know that the first message sent from a user their name
      /*if (myName === false) {
        myName = msg;
      }*/
    }
  });
  /**
   * This method is optional. If the server wasn't able to
   * respond to the in 3 seconds then show some error message 
   * to notify the user that something is wrong.
   */
  setInterval(function() {
    if (connection.readyState !== 1) {
      status.text('Error');
      input.attr('disabled', 'disabled').val(
          'Unable to communicate with the WebSocket server.');
    }
  }, 3000);
  /**
   * Add message to the chat window
   */
  function addMessage(author, message, color, dt, recipient) {
    let Class = (author == recipient? 'text-right' : '');
    content.prepend('<p class="' + Class + '"><span style="color:' + color + '">'
        + author + '</span> @ ' + (dt.getHours() < 10 ? '0'
        + dt.getHours() : dt.getHours()) + ':'
        + (dt.getMinutes() < 10
          ? '0' + dt.getMinutes() : dt.getMinutes())
        + ': ' + message + '</p>');
  }

  function refreshOnlineUsers(data, type) {
    console.log(data)
    let $userList = $('#user_list');
    if(type == 'new_peer') {
      $userList.append(`<option value="${data}">${data}</option>`);
    } else if(type == 'disconnected_peer') {
      $userList.find('option[value=' + data + ']').remove();
    } else {
      $userList.append(data.reduce((x) => `<option value="${x}">${x}</option>`))
    }
  }


});