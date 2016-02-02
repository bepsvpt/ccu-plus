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
      case HTTP_OK:
        break;

      case HTTP_CREATED:
        break;

      case HTTP_UNAUTHORIZED:
        break;

      case HTTP_FORBIDDEN:
        break;

      case HTTP_NOT_FOUND:
        break;

      case HTTP_METHOD_NOT_ALLOWED:
        break;

      case HTTP_CONFLICT:
        break;

      case HTTP_UNPROCESSABLE_ENTITY:
        break;

      case HTTP_TOO_MANY_REQUESTS:
        break;
    }

    if (! response.ok) {
      // Reset recaptcha.
      this.$broadcast('recaptcha-reset');
    } else {
      //
    }

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
