<template>
  <WaField :name="l10n('Ограничение по весу')" name-class="for-input">
    <div class="value">
      {{ l10n('от') }}
      <input :name="addns('min', addns('weight_limits', ns))" type="number" class="shortest"
             min="0" step="1" v-model.number="setting.min" placeholder="0"
             @input="emit('update:modelValue', setting)">
      {{ l10n('до') }}
      <input :name="addns('max', addns('weight_limits', ns))" type="number" class="shortest"
             min="0" step="1" v-model.number="setting.max" placeholder="∞"
             @input="emit('update:modelValue', setting)">
      {{ l10n('кг.') }}
    </div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from './WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface Limits { min: number | null; max: number | null }

const props = defineProps<{ modelValue: Limits; ns: string }>()
const emit = defineEmits<{ 'update:modelValue': [value: Limits] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const setting = props.modelValue
</script>
