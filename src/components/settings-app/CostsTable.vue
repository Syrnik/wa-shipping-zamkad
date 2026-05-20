<template>
  <WaField :name="l10n('Тарифы доставки')">
    <div class="value no-shift">
      <table class="zebra" style="width: auto">
        <thead>
          <tr>
            <th>{{ l10n('Расстояние') }}</th>
            <th>{{ l10n('Фикс. цена') }}</th>
            <th colspan="2">{{ l10n('Цена за 1 км.') }}</th>
            <th></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td colspan="5">
              <a href="#" @click.prevent="addRow"><i class="icon16 add"></i> {{ l10n('Добавить тариф') }}</a>
            </td>
          </tr>
        </tfoot>
        <tbody>
          <TariffRow
            v-for="(t, idx) in setting"
            :key="array_unique_key()"
            :modelValue="t"
            :ns="addns(''+idx, ns)"
            :currency="currency"
            @delete="deleteRow(idx)"
          />
        </tbody>
      </table>
    </div>
  </WaField>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import WaField from '../WaField.vue'
import TariffRow from './TariffRow.vue'
import { useL10n } from '../../composables/useL10n'
import { useNamespace } from '../../composables/useNamespace'
import { useArrayKey } from '../../composables/useArrayKey'

interface Currency { sign: string; precision?: number }
interface Tariff { to: number; base: number; price: number }

const props = defineProps<{ modelValue: Tariff[]; ns: string; currency: Currency }>()
const { l10n } = useL10n()
const { addns } = useNamespace()
const { array_unique_key } = useArrayKey()
const setting = reactive(props.modelValue)

function addRow(): void {
  const last = setting[setting.length - 1]
  const newRow = { ...last }
  setting.forEach(t => {
    if (t.to >= newRow.to) {
      newRow.to = t.to + 1
      newRow.base = t.base
      newRow.price = t.price
    }
  })
  setting.push(newRow)
}

function deleteRow(index: number): void {
  if (setting.length === 1) {
    setting[0].to = 1
    setting[0].base = setting[0].price = 0
  } else {
    setting.splice(index, 1)
  }
}
</script>
