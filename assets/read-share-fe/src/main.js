import ReadShare from './ReadShare.vue'
import { startApp } from './utils/loader'

startApp(
  ReadShare,
  window._feInjection.currentData,
  window._feInjection.currentUser
).then(() => { console.log('finish startApp') })
