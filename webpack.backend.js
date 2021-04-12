var webpack = require('webpack');
var path = require('path');
var fs = require('fs');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

var nodeModules = {};
fs.readdirSync('node_modules')
    .filter(function(x) {
        return ['.bin'].indexOf(x) === -1;
    })
    .forEach(function(mod) {
        nodeModules[mod] = 'commonjs ' + mod;
    });

module.exports = {
    entry: './node/server.js',
    target: 'node',
    output: {
        path: path.join(__dirname),
        filename: 'server.js'
    },
    externals: nodeModules,
    plugins: [
        new UglifyJsPlugin()
    ]
}