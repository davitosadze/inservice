import Service from './Service'

class SurvayService extends Service {

  submit (data) {
    return this.globalState.js.$http.post('http://geostat-admin.locale/api/survay/get', { data })
  }

}

export default SurvayService


