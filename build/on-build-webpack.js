const exec = require('child_process').exec
const fs = require('fs')
const path = require('path')
const WebpackOnBuildPlugin = require('on-build-webpack')

module.exports = new WebpackOnBuildPlugin(stats => {
  if (process.argv.includes('-p')) {
    const manifest = path.resolve(__dirname, '..', 'public', 'assets', 'js', 'manifest')

    fs.writeFileSync(manifest, stats.hash)
  }

  exec('php artisan build:post')
})
