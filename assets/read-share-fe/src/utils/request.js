import axios from 'axios'

let csrfToken = null

if (document.querySelector('meta[name="csrf-token"]')) { csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content') }

axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN': csrfToken
}

const MaxTimeout = 2000

axios.defaults.timeout = MaxTimeout

const request = async (param) => {
  try {
    const { status, data } = await axios.request(param)

    if (status !== 200) {
      const errorMessage = data.error ? data.error : 'Unknow'
      throw new Error(`Request Error on ${param.url}: ${status} - ${errorMessage}`)
    }
    return data
  } catch (e) {
    console.log(e)
    throw e
  }
}

const requestGet = async (url, params = null) => request({ method: 'get', url, params })
const requestPost = async (url, data, params = null) => request({ method: 'post', url, params, data })

export {
  axios,
  requestGet, requestPost
}
