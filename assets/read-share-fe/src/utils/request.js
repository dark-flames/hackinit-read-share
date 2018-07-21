import axios from 'axios'
import qs from 'qs'

const request = async conf => {
  try {
    const { status, data } = await axios.request(conf)

    if (status !== 200) throw new Error(`Request HTTP Error: ${status}`)
    return data
  } catch (e) {
    console.error(e)
    throw e
  }
}

const requestGet = async (url, params = null) => request({ method: 'get', url, params })
const requestPost = async (url, data, params = null) => request({ method: 'post', url, params, data })
const requestPostQS = async (url, params) => request({ method: 'post', url, data: qs.stringify(params) })

export {
  axios,
  requestGet, requestPost, requestPostQS
}
