import Service from './Service'

class AuthService extends Service {

  constructor (token = '', js = {}) {
  	super(token, js)
  }

  login (user) {
    return this.globalState.js.$http.get('http://geostat-admin/api/login', { params: user })
  }

}

export default AuthService


