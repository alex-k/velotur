module.exports = {
  context: __dirname,
  entry: "src/js/index.js",
  output: {
    path: __dirname + "/public",
    filename: "index.js"
  },
  resolve: {
    alias: {
      src: __dirname + "/src",
      components: __dirname + "/src/js/react-components",
      redux: __dirname + "/src/js/redux",
      services: __dirname + "/src/js/services"
    }
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: "babel-loader",
        options: {
          presets: ["babel-preset-env"]
        }
      }
    ]
  }
}