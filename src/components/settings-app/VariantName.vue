<template>
  <WaFieldSimple :name="l10n(name)" value_class="no-shift">
    <input
      type="text"
      class="long"
      :name="addns('variant_name', ns)"
      v-model.trim="val"
      @input="emit('update:modelValue', val)"
      :placeholder="l10n('Доставка за город')"
    >
  </WaFieldSimple>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import WaFieldSimple from '../WaFieldSimple.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

const props = withDefaults(defineProps<{ name?: string; modelValue?: string; ns?: string }>(), {
  name: 'Название варианта доставки',
  modelValue: '',
  ns: '',
})
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const val = ref(props.modelValue)
</script>
