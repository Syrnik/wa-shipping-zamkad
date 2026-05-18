<template>
  <WaField :name="l10n('Ограничение по стоимости заказа')" name-class="for-input">
    <div class="value">
      {{ l10n('от') }}
      <input :name="addns('min', addns('price_limits', ns))" type="number" class="shortest"
             min="0" :step="currencyPrecision" v-model.number="setting.min" placeholder="0"
             @input="emit('update:modelValue', setting)">
      {{ l10n('до') }}
      <input :name="addns('max', addns('price_limits', ns))" type="number" class="shortest"
             min="0" :step="currencyPrecision" v-model.number="setting.max" placeholder="∞"
             @input="emit('update:modelValue', setting)">
      {{ currency.sign }}
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import WaField from './WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface Limits { min: number | null; max: number | null }
interface Currency { sign: string; precision?: number }

const props = defineProps<{ modelValue: Limits; ns: string; currency: Currency }>()
const emit = defineEmits<{ 'update:modelValue': [value: Limits] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = props.modelValue
const currencyPrecision = computed(() =>
  Math.pow(10, props.currency.precision ? 0 - props.currency.precision : -2)
)
</script>
