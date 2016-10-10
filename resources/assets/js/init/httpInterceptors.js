import Cookies from 'js-cookie'

export default function (request, next) {
  // https://laravel.com/docs/5.3/csrf#csrf-x-xsrf-token
  request.headers['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN')

  // continue to next interceptor
  next((response) => {
    //
  })
}
