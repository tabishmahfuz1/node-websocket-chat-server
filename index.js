var app = require('./app.js');

var ServerPort = 1337;

app.listen(ServerPort, function() {
  console.log((new Date()) + " Server is listening on port "
      + ServerPort);
});

