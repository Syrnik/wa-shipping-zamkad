<template>
  <WaField :name="l10n('Интервалы доставки')">
    <div class="value">
      <table class="zebra">
        <thead>
          <tr>
            <th colspan="2">{{ l10n('Интервал доставки') }}</th>
            <th>{{ l10n('Пн') }}</th>
            <th>{{ l10n('Вт') }}</th>
            <th>{{ l10n('Ср') }}</th>
            <th>{{ l10n('Чт') }}</th>
            <th>{{ l10n('Пт') }}</th>
            <th>{{ l10n('Сб') }}</th>
            <th>{{ l10n('Вс') }}</th>
            <th>{{ l10n('Доп. выходной') }}</th>
            <th>{{ l10n('Доп. рабочий день') }}</th>
            <th></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td colspan="12">
              <a href="#" @click.prevent="addInterval">
                <i class="icon16 add"></i> {{ l10n('Добавить интервал') }}
              </a>
            </td>
          </tr>
        </tfoot>
        <tbody>
          <Timeframe
            v-for="(t, idx) in timeframes"
            :key="array_unique_key()"
            :modelValue="t"
            :ns="addns(''+idx, ns)"
            :show-delete="timeframes.length > 1"
            @delete="deleteInterval(idx)"
          />
        </tbody>
      </table>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import WaField from '../WaField.vue'
import Timeframe from './Timeframe.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'
import { useArrayKey } from '../../composables/useArrayKey'
import type { TimeframeData } from '../../types'

const props = defineProps<{ modelValue: TimeframeData[]; ns: string }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const { array_unique_key } = useArrayKey()
const timeframes = reactive(props.modelValue)

function addInterval(): void {
  const last = timeframes[timeframes.length - 1]
  const newInterval: TimeframeData = { ...last }
  newInterval.from_hour = Math.min((last.from_hour ?? 0) + 1, 23)
  newInterval.to_hour = Math.min((last.to_hour ?? 0) + 1, 23)
  newInterval[1] = true
  timeframes.push(newInterval)
}

function deleteInterval(idx: number): void {
  if (timeframes.length > 1) timeframes.splice(idx, 1)
}
</script>
