const webpack = require('webpack')
const fs = require('fs')
const path = require('path')
const production = process.argv.includes('-p')

const config = {
  entry: {
    main: path.resolve(__dirname, 'resources', 'assets', 'js', 'entry.js'),
    vendor: Object.keys(require('./package.json').dependencies)
  },

  output: {
    path: path.resolve(__dirname, 'public', 'assets', 'js'),
    publicPath: '/assets/js/',
    filename: '[name].js'
  },

  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel!eslint', exclude: /node_modules/ },
      { test: /\.json$/, loader: 'json' },
      { test: /\.vue$/, loader: 'vue!eslint' }
    ]
  },

  resolve: {
    alias: {
      vue: 'vue/dist/vue.js'
    }
  },

  plugins: [
    require('./build/on-build-webpack'),
    new webpack.optimize.CommonsChunkPlugin({ name: 'vendor', filename: 'vendor.js' })
  ],

  devtool: production ? false : 'source-map'
}

if (! production) {
  config.plugins.push(
    require('./build/browser-sync')
  )
} else {
  config.plugins.push(
    new webpack.EnvironmentPlugin(['NODE_ENV']),
    new webpack.optimize.OccurrenceOrderPlugin(true),
    new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, output: { comments: false }})
  )
}

module.exports = config
