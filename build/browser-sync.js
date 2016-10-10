const path = require('path')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const basePath = path.resolve(__dirname, '..')

module.exports = new BrowserSyncPlugin({
  proxy: 'https://ccu-plus.dev',
  host: 'localhost',
  port: 3000,
  files: path.resolve(basePath, 'public', 'assets', 'js', '*.js'),
  https: {
    key: path.resolve(basePath, 'certs', 'server.key'),
    cert: path.resolve(basePath, 'certs', 'server.crt')
  },
  browser: ['google chrome'],
  reloadDelay: 500
})
