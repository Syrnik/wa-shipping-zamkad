<template>
  <WaField :name="l10n('Валюта расчёта')">
    <div class="value">
      <select @change="emit('update:modelValue', ($event.target as HTMLSelectElement).value)" :name="addns('currency', ns)">
        <option v-for="c in currencies" :key="c.code" :value="c.code" :selected="modelValue === c.code">
          {{ c.name }} ({{ c.code }})
        </option>
      </select>
      <br>
      <span class="hint">
        <i class="icon16 exclamation"></i>
        {{ l10n('Убедитесь, что выбранная валюта есть в настройках магазина! Рекомендуется указывать здесь основную валюту магазина.') }}
      </span>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import WaField from '../WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

interface Currency { code: string; name: string; sign: string; precision?: number }

defineProps<{ modelValue: string; ns: string; currencies: Record<string, Currency> }>()
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
</script>
