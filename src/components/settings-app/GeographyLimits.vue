<template>
  <WaField :name="l10n(name)">
    <div class="value no-shift">
      <select v-model="setting.country" :name="addns('country', ns)">
        <option value=""></option>
        <option v-for="o in countries" :key="o.code" :value="o.code">{{ o.name }}</option>
      </select>
      <i class="icon16 loading" v-if="loadingCountries"></i>
    </div>
    <div class="value">
      <select v-model="setting.region" :name="addns('region', ns)">
        <option value=""></option>
        <option v-for="o in regions" :key="o.code" :value="o.code">{{ o.name }}</option>
      </select>
      <i class="icon16 loading" v-if="loadingRegions"></i>
    </div>
    <div class="value">
      <span class="hint">{{ l10n('Доставка будет ограничена только выбранной страной или регионом страны') }}</span>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { ref, reactive, watch, onMounted } from 'vue'
import WaField from '../WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface GeoOption { code: string; name: string }
interface Urls { countries: string; regions: string }
interface GeoLimits { country: string; region: string }

const props = withDefaults(defineProps<{ name?: string; urls: Urls; modelValue: GeoLimits; ns: string }>(), {
  name: 'Ограничения по географии',
})
const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = reactive(props.modelValue)
const countries = ref<GeoOption[]>([])
const regions = ref<GeoOption[]>([])
const loadingCountries = ref(false)
const loadingRegions = ref(false)

async function loadCountries(): Promise<void> {
  loadingCountries.value = true
  try {
    const r = await fetch(props.urls.countries).then(res => res.json())
    if (r.data && Array.isArray(r.data)) {
      countries.value = r.data
      if (setting.country && !countries.value.find(c => c.code === setting.country)) {
        setting.country = ''
      }
    }
  } finally {
    loadingCountries.value = false
  }
}

async function loadRegions(): Promise<void> {
  if (!setting.country) {
    regions.value = []
    setting.region = ''
    return
  }
  loadingRegions.value = true
  try {
    const sep = props.urls.regions.includes('?') ? '&' : '?'
    const url = `${props.urls.regions}${sep}country=${encodeURIComponent(setting.country)}`
    const r = await fetch(url).then(res => res.json())
    if (r.data && Array.isArray(r.data)) {
      regions.value = r.data
      if (!setting.region || !regions.value.find(r => r.code === setting.region)) {
        setting.region = ''
      }
    }
  } finally {
    loadingRegions.value = false
  }
}

onMounted(async () => {
  if (setting.country) {
    await loadRegions()
  }
  await loadCountries()
})

watch(() => setting.country, (newV, oldV) => {
  if (newV !== oldV) loadRegions()
})
</script>
