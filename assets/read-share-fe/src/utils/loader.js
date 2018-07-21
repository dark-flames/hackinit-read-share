import Store from './store'
import Vue from 'vue'

const startApp = async (rootComponent, currentData, currentUser) => {
  await Store._setup()
  Vue.prototype.$config = Store.config
  Vue.prototype.$currentUser = currentUser
  console.log(Store.config)

  const Constructor = Vue.extend(rootComponent)

  console.log(`start vue app on ${Store.config['entryElement']}`)
  return new Constructor({
    data: currentData
  }).$mount(Store.config['entryElement'])
}

export {
  startApp
}
