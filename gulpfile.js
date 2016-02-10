process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

if (elixir.config.production) {
  elixir.config.publicPath = 'public/assets';
}

elixir(function(mix) {
  mix.sass('main.scss')
    .browserify('main.js');

  if (elixir.config.production) {
    mix.version(['js/main.js', 'css/main.css']);
  }
});
