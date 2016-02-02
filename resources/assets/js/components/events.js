let events = {
  'http-response'(response, options = {}) {
    const HTTP_OK = 201;
    const HTTP_CREATED = 201;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_CONFLICT = 409;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_TOO_MANY_REQUESTS = 429;

    function http_ok() {
    }

    function http_created() {
    }

    function http_unprocessable_entity() {
    }

    function http_success() {
    }

    function http_error() {
      // Reset recaptcha.
      let g = document.querySelectorAll('textarea[id^="g-recaptcha-response"]');

      for (let i in g) {
        if (g.hasOwnProperty(i)) {
          let tokens = g[i].id.split('-');

          grecaptcha.reset(4 === tokens.length ? +tokens.pop() : 0);
        }

      }
    }

    function toastSuccess(message) {
      toast(message, 'green');
    }

    function toastInfo(message, style = 'amber darken-2') {
      toast(message, style);
    }

    function toastError(message) {
      toast(message, 'red');
    }

    function toast(message = '', style, duration = 100500) {
      Materialize.toast(message, duration, style);
    }

    switch (response.status) {
      case HTTP_OK: http_ok(); break;
      case HTTP_CREATED: http_created(); break;
      case HTTP_UNPROCESSABLE_ENTITY: http_unprocessable_entity(); break;
    }

    response.ok ? http_success() : http_error();

    if (options.hasOwnProperty('modal-close')) {
      $(options['modal-close']).closeModal();
    }

    if (options.hasOwnProperty('redirect') && options.redirect.hasOwnProperty('name')) {
      this.$router.go({
        name: options.redirect.name,
        params: options.redirect.params || {},
        query: options.redirect.query || {}
      });
    }
  }
};

export default events;
