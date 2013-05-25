var _ns_ = {
  "id": "pieshares"
};;

(function() {
  var express = require("express");
  var app = express();
  return (function() {
    app.use(express.bodyParser());
    app.use(express.static("public"));
    return app.listen(3000);
  })();
})()