<template>
  <tr>
    <td>
      {{ l10n('до') }}
      <input type="number" class="short numerical" min="1" step="1"
             :name="addns('to', ns)" v-model.number="tariff.to">
      {{ l10n('км.') }}
    </td>
    <td>
      <input type="number" class="short numerical" min="0" :step="currencyPrecision"
             :name="addns('base', ns)" v-model.number="tariff.base">
      {{ currency.sign }}
    </td>
    <td><i class="icon16 plus"></i></td>
    <td>
      <input type="number" class="short numerical" min="0" :step="currencyPrecision"
             :name="addns('price', ns)" v-model.number="tariff.price">
      {{ currency.sign }}
    </td>
    <td>
      <a href="#" @click.prevent="emit('delete')"><i class="icon16 no"></i></a>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface Currency { sign: string; precision?: number }
interface Tariff { to: number; base: number; price: number }

const props = defineProps<{ modelValue: Tariff; ns: string; currency: Currency }>()
const emit = defineEmits<{ delete: [] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const tariff = props.modelValue
const currencyPrecision = computed(() =>
  Math.pow(10, props.currency.precision ? 0 - props.currency.precision : -2)
)
</script>
