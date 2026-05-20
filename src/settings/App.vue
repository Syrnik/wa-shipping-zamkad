<template>
  <div id="shipping-zamkad-settings-block">
    <FieldName v-model="setting.field_name" :ns="info.namespace" />
    <VariantName v-model="setting.variant_name" :ns="info.namespace" />
    <CurrencySelector v-model="setting.currency" :ns="info.namespace" :currencies="info.currencies" />
    <GeographyLimits
      :urls="info.action_url"
      v-model="setting.geography_limits"
      :name="l10n('Регион доставки')"
      :ns="addns('geography_limits', info.namespace)"
    />
    <WeightLimits v-model="setting.weight_limits" :ns="info.namespace" />
    <PriceLimits v-model="setting.price_limits" :ns="info.namespace" :currency="selectedCurrency" />
    <CostsTable v-model="setting.km_table" :ns="addns('km_table', info.namespace)" :currency="selectedCurrency" />
    <StreetField v-model="setting.street_field" :ns="addns('street_field', info.namespace)" />
    <DeliveryDate
      :name="l10n('Дата доставки')"
      :ns="addns('delivery_date', info.namespace)"
      v-model="setting.delivery_date"
    />
    <DateTimeCustomFieldsToggle v-model="setting.desired_delivery" :ns="addns('desired_delivery', info.namespace)" />
    <Timeframes v-model="setting.timeframes" :ns="addns('timeframes', info.namespace)" />
    <Dates
      class="holidays"
      :name="l10n('Дополнительные выходные')"
      v-model="setting.holidays"
      :ns="addns('holidays', info.namespace)"
    />
    <Dates
      class="workdays"
      :name="l10n('Дополнительные рабочие дни')"
      v-model="setting.workdays"
      :ns="addns('workdays', info.namespace)"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, reactive } from 'vue'
import FieldName from './components/FieldName.vue'
import VariantName from './components/VariantName.vue'
import CurrencySelector from './components/CurrencySelector.vue'
import GeographyLimits from './components/GeographyLimits.vue'
import WeightLimits from './components/WeightLimits.vue'
import PriceLimits from './components/PriceLimits.vue'
import CostsTable from './components/CostsTable.vue'
import StreetField from './components/StreetField.vue'
import DeliveryDate from './components/DeliveryDate.vue'
import DateTimeCustomFieldsToggle from './components/DateTimeCustomFieldsToggle.vue'
import Timeframes from './components/Timeframes.vue'
import Dates from './components/Dates.vue'
import { useL10n } from '../composables/useL10n'
import { useNamespace } from '../composables/useNamespace'
import type { TimeframeData } from '../types'

interface Currency { code: string; name: string; sign: string; precision?: number }
interface Urls { countries: string; regions: string }
interface GeoLimits { country: string; region: string }
interface Limits { min: number | null; max: number | null }
interface Tariff { to: number; base: number; price: number }
interface DeliveryDateSettings { show: unknown; interval: string }
interface DesiredDelivery { date: unknown; interval: unknown }

interface Settings {
  field_name: string
  variant_name: string
  currency: string
  geography_limits: GeoLimits
  weight_limits: Limits
  price_limits: Limits
  km_table: Tariff[]
  street_field: string
  delivery_date: DeliveryDateSettings
  desired_delivery: DesiredDelivery
  timeframes: TimeframeData[]
  holidays: string[]
  workdays: string[]
}

interface Info {
  namespace: string
  currencies: Record<string, Currency>
  action_url: Urls
}

const props = defineProps<{
  settings: Settings
  info: Info
}>()

const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = reactive(props.settings)
if (!Array.isArray(setting.holidays)) setting.holidays = setting.holidays ? Object.values(setting.holidays as Record<string, string>) : []
if (!Array.isArray(setting.workdays)) setting.workdays = setting.workdays ? Object.values(setting.workdays as Record<string, string>) : []

const selectedCurrency = computed<Currency>(() =>
  props.info.currencies[setting.currency] ?? { code: '', name: '', sign: '' }
)
</script>
