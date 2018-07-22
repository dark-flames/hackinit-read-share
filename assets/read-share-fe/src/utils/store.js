import localforage from 'localforage'
import { requestGet } from './request'

const Store = {
  async _updateConfig () {
    let newVersion
    try {
      newVersion = await requestGet('/_fe/config/_versions')
    } catch (e) {
      console.log('Catch an Error while get config version.')
    }
    let localVersion = await this._versionStore.getItem('localVersion') || {}

    const updateConfig = async configName => {
      if (!localVersion[configName] || localVersion[configName] < newVersion[configName]) {
        try {
          const configItem = await requestGet(`/_fe/config/${configName}`)
          await this._configStore.setItem(configName, configItem)
        } catch (e) {
          console.log(`Catch an Error while get config ${configName}.`)
        }
      }
      localVersion[configName] = newVersion[configName]
    }

    await Promise.all(Object.keys(newVersion).map(updateConfig))
    await this._versionStore.setItem('localVersion', localVersion)
  },
  async _getConfig () {
    const localVersion = await this._versionStore.getItem('localVersion')

    this.config = {}

    const getConfigItem = async configName => {
      this.config[configName] = await this._configStore.getItem(configName)
    }

    return Promise.all(Object.keys(localVersion).map(getConfigItem))
  },
  async _setup () {
    this._versionStore = localforage.createInstance({ name: 'feVersion' })
    this._configStore = localforage.createInstance({ name: 'feConfig' })

    await this._updateConfig()
    await this._getConfig()
  }
}

export default Store
