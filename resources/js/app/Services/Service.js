
const internal = Symbol('SurvayService')

class Service {

  static instance = []

  constructor (token, js) {
  	if (token !== internal) {
      throw new Error('Please use the Service.getInstance() static method instead')
    }
    this.globalState = { js }
  }

  static getInstance (js) {
    if (Service.instance[this.name]) {
      return Service.instance[this.name]
    }
    Service.instance[this.name] = new this(internal, js)
    return Service.instance[this.name]
  }

}

export default Service


