<template>
  <tr>
    <td>
      {{'до'|localized}}
      <input type="number" class="short numerical" min="1" step="1" :name="addns('to', ns)" v-model.number="tariff.to">
      {{'км.'|localized}}
    </td>
    <td>
      <input type="number" class="short numerical" min="0" step="0.01" :name="addns('base', ns)"
             v-model.number="tariff.base">
      ₽
    </td>
    <td><i class="icon16 plus"></i></td>
    <td>
      <input type="number" class="short numerical" min="0" step="0.01" :name="addns('price', ns)"
             v-model.number="tariff.price">
      ₽
    </td>
    <td>
      <a href="#" @click.prevent="$emit('delete')"><i class="icon16 no"></i></a>
    </td>
  </tr>
</template>

<script>
import WaL10n from "../wa-l10n";

export default {
  mixins: [WaL10n],
  props: {
    value: Object,
    ns: String
  },
  data() {
    return {
      tariff: this.value
    }
  }
}
</script>
