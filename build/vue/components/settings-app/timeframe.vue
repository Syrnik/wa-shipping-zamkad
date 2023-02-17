<template>
  <tr>
    <td>{{'с'|localized}}
      <input type="text" class="short" :name="addns('from_hour', ns)" v-model="fromHour" required>
      :
      <input type="text" class="short" :name="addns('from_minutes', ns)" v-model="fromMinutes" required>
    </td>
    <td>{{'до'|localized}}
      <input type="text" class="short" :name="addns('to_hour', ns)" v-model="toHour" required>
      :
      <input type="text" class="short" :name="addns('to_minutes', ns)" v-model="toMinutes" required>
    </td>
    <td>
      <input type="hidden" :name="addns('1', ns)" value="0">
      <input type="checkbox" :name="addns('1', ns)" value="1" v-model="timeframe[1]">
    </td>
    <td>
      <input type="hidden" :name="addns('2', ns)" value="0">
      <input type="checkbox" :name="addns('2', ns)" value="1" v-model="timeframe[2]">
    </td>
    <td>
      <input type="hidden" :name="addns('3', ns)" value="0">
      <input type="checkbox" :name="addns('3', ns)" value="1" v-model="timeframe[3]">
    </td>
    <td>
      <input type="hidden" :name="addns('4', ns)" value="0">
      <input type="checkbox" :name="addns('4', ns)" value="1" v-model="timeframe[4]">
    </td>
    <td>
      <input type="hidden" :name="addns('5', ns)" value="0">
      <input type="checkbox" :name="addns('5', ns)" value="1" v-model="timeframe[5]">
    </td>
    <td>
      <input type="hidden" :name="addns('6', ns)" value="0">
      <input type="checkbox" :name="addns('6', ns)" value="1" v-model="timeframe[6]">
    </td>
    <td>
      <input type="hidden" :name="addns('7', ns)" value="0">
      <input type="checkbox" :name="addns('7', ns)" value="1" v-model="timeframe[7]">
    </td>
    <td>
      <input type="hidden" :name="addns('holidays', ns)" value="0">
      <input type="checkbox" :name="addns('holidays', ns)" value="1" v-model="timeframe['holidays']">
    </td>
    <td>
      <input type="hidden" :name="addns('workdays', ns)" value="0">
      <input type="checkbox" :name="addns('workdays', ns)" value="1" v-model="timeframe['workdays']">
    </td>
    <td><a href="javascript:void(0)" @click.prevent="$emit('delete')" v-if="showDelete"><i class="icon16 no"></i></a></td>
  </tr>
</template>

<script>
import _toNumber from 'lodash.tonumber'
import format from 'number-formatter'
import WaL10n from "../wa-l10n";

export default {
  mixins:[WaL10n],
  props: {
    value: Object,
    ns: String,
    showDelete: Boolean
  },
  data() {
    return {
      timeframe: this.value
    }
  },
  methods: {
    setTime(value, type, maximum) {
      if ((typeof value === 'string') && !value.length) {
        this.timeframe[type] = null;
        return;
      }
      let v;
      if (/^[0-9]+$/i.test(value) && ((v = _toNumber(value)) < maximum))
        this.timeframe[type] = v;
      else {
        v = this.timeframe[type];
        this.timeframe[type] = 0;
        this.$nextTick(() => this.timeframe[type] = v);
      }
    },
    getTime(type) {
      if (this.timeframe[type] === null) return '';
      return format('0#', this.timeframe[type])
    }
  },
  computed: {
    fromHour: {
      get() {
        return this.getTime('from_hour');
      },
      set(hr) {
        this.setTime(hr, 'from_hour', 24);
      }
    },
    toHour: {
      get() {
        return this.getTime('to_hour');
      },
      set(hr) {
        this.setTime(hr, 'to_hour', 24);
      }
    },
    fromMinutes: {
      get() {
        return this.getTime('from_minutes');
      },
      set(minutes) {
        this.setTime(minutes, 'from_minutes', 60);
      }
    },
    toMinutes: {
      get() {
        return this.getTime('to_minutes');
      },
      set(minutes) {
        this.setTime(minutes, 'to_minutes', 60);
      }
    }
  }
}
</script>
