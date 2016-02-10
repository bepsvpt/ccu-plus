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
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_UNKNOWN_ERROR = 520;

    this.toastSuccess = function (message) {
      toast(message, 'green');
    };

    this.toastInfo = function (message, style = 'amber darken-2') {
      toast(message, style);
    };

    this.toastError = function (message) {
      this.toast(message, 'red');
    };

    this.toast = function (message = '', style, duration = 4500) {
      Materialize.toast(message, duration, style);
    };

    switch (response.status) {
      case HTTP_OK:
        break;

      case HTTP_CREATED:
        this.toastSuccess(`新增成功`);

        break;

      case HTTP_UNAUTHORIZED:
        this.toastError(`您必須先登入方能訪問此頁面`);

        break;

      case HTTP_FORBIDDEN:
        this.toastError(`您沒有權限訪問此頁面`);

        break;

      case HTTP_NOT_FOUND:
        this.toastError('您所訪問的頁面不存在');

        break;

      case HTTP_METHOD_NOT_ALLOWED:
        this.toastError(`似乎發生了一些狀況，請重新整理您的瀏覽器`);

        break;

      case HTTP_CONFLICT:
        break;

      case HTTP_UNPROCESSABLE_ENTITY:
        let messages = response.data.messages;

        for (let i in messages) {
          if (messages.hasOwnProperty(i)) {
            messages[i].map((message) => {
              if (message.length > 0) {
                this.toastError(message);
              }
            });
          }
        }

        break;

      case HTTP_TOO_MANY_REQUESTS:
        this.toastError(`您於短時間內發送過多請求，請於 ${response.headers('Retry-After')} 秒後再嘗試`);

        break;

      case HTTP_SERVICE_UNAVAILABLE:
      case HTTP_UNKNOWN_ERROR:
        this.toastError(`伺服器發生無法預期的錯誤，請稍候再嘗試`);

        break;
    }

    if (! response.ok) {
      // Reset recaptcha.
      this.$broadcast('recaptcha-reset');
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
