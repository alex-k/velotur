const path = require('path');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin'); 

module.exports = {
  context: __dirname,
  entry: "src/js/index.js",
  output: {
    path: path.resolve(__dirname, "../www/app/public/n"),
    filename: "index.js"
  },
  resolve: {
    alias: {
      src: path.resolve(__dirname, "src"),
      config$: path.resolve(__dirname, "config/config.js"),
      components: path.resolve(__dirname, "src", "js", "react-components"),
      reducers: path.resolve(__dirname, "src", "js", "redux", "reducers"),
      actions: path.resolve(__dirname, "src", "js", "redux", "actions"),
      services: path.resolve(__dirname, "src", "js","services"),
      css: path.resolve(__dirname, "src", "css"),
      util: path.resolve(__dirname, "src", "js", "util")
    }
  },
  module: {
    rules: [{
      test: /\.css$/,
      use: [{
        loader: "style-loader"
      }, {
        loader: "css-loader"
      }]
    }, {
      test: /\.js$/,
      use : {
        loader: "babel-loader",
        options: {
          presets: ["babel-preset-env", "babel-preset-react"]
        }
      }
    }]
  },
  plugins: [
    // new UglifyJSPlugin({
    //   exclude: [/\/vendor/]
    // }),
  ],
  devServer: {
    contentBase: path.join(__dirname, "public"),
    compress: true,
    port: 9000
  }
}