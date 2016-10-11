const ExtractTextPlugin = require('extract-text-webpack-plugin')

const loaders = {
  css: ExtractTextPlugin.extract({ loader: 'css' }),
  scss: ExtractTextPlugin.extract({ loader: 'css!sass' })
}

const instance = new ExtractTextPlugin({
  filename: 'main.css',
  allChunks: true
})

module.exports = { loaders, instance }
