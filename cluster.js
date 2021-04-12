const cluster = require("cluster");

if(cluster.isMaster) {

    const cpuCount = require('os').cpus().length;

    for(var i = 0; i < cpuCount; i++) {
        cluster.fork();
    }

    cluster.on("exit", () => {
        cluster.fork();
    });

} else {
    require('./server.js');
}