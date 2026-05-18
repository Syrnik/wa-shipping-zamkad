<template>
  <WaField :name="l10n('Название поля с расстоянием')" name-class="for-input">
    <div class="value">
      <input
        type="text"
        class="long"
        :name="addns('field_name', ns)"
        v-model.trim="val"
        @input="emit('update:modelValue', val)"
        :placeholder="l10n('Расстояние от города (км.)')"
      >
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import WaField from './WaField.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'

const props = withDefaults(defineProps<{ modelValue?: string; ns?: string }>(), {
  modelValue: '',
  ns: '',
})
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const val = ref(props.modelValue)
</script>
