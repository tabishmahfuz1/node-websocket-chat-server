var http        = require('http');
var url         = require('url');
const clientLists = require('./websocket').clientLists;

/**
 * HTTP server
 */
var server = http.createServer(function(request, response) {
  response.writeHead(200, {'Content-Type': 'application/json'});
  // console.log(request.url)
  var q = url.parse(request.url, true);
  // console.log(q);
  if(q.pathname == "/companies"){
    return JSON.stringify({companies: clientLists.keys()});
  } else if(q.pathname == "/users"){
    let domain = q.query.company;
    let users = clientLists.get(domain);
    
    return JSON.stringify({users});
  }else{
    return JSON.stringify({
      routes: ['companies', 'users']
    });
  }

  
});

module.exports = server;