import { createApp } from 'vue'
import SettingsApp from './SettingsApp.vue'
import '../css/settings-legacy.styl'

interface Options {
  el?: string
  info?: Record<string, unknown>
  settings?: Record<string, unknown>
}

export default function (options: Options = {}): void {
  createApp(SettingsApp, {
    info: options.info ?? {},
    settings: options.settings ?? {},
  }).mount(options.el ?? '#zamkad-shipping-settings')
}
