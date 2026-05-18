import { createApp } from 'vue'
import App from './App.vue'
import '../../css/settings.styl'

interface Options {
  info?: Record<string, unknown>
  settings?: Record<string, unknown>
}

export default function (options: Options = {}) {
  return createApp(App, {
    info: options.info ?? {},
    settings: options.settings ?? {},
  })
}
