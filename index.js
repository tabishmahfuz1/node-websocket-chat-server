var app = require('./app.js');
var websocket = require('./websocket');

var ServerPort = 1337;

app.listen(ServerPort, function() {
  console.log((new Date()) + " Server is listening on port "
      + ServerPort);
});

websocket.attach(app);

