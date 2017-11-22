const path = require('path');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin'); 

module.exports = {
  context: __dirname,
  entry: "src/js/index.js",
  output: {
    path: path.join(__dirname, "public"),
    filename: "index.js"
  },
  resolve: {
    alias: {
      src: path.join(__dirname, "src"),
      components: path.join(__dirname, "src/js/react-components"),
      redux: path.join(__dirname, "src/js/redux"),
      services: path.join(__dirname, "src/js/services")
    }
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: "babel-loader",
        options: {
          presets: ["babel-preset-env", "babel-preset-react"]
        }
      }
    ]
  },
  plugins: [
    new UglifyJSPlugin({
      exclude: [/\/vendor/]
    }),
  ],
  devServer: {
    contentBase: path.join(__dirname, "public"),
    compress: true,
    port: 9000
  }
}