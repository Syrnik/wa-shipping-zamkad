<template>
  <wa-field :name="l10n(name)">
    <div class="value no-shift">
      <select v-model="setting.country" :name="addns('country', ns)">
        <option value="" :selected="setting.country === ''"></option>
        <option v-for="o in countries" :value="o.code" :selected="setting.country === o.code">{{ o.name }}</option>
      </select><i class="icon16 loading" v-if="view.loading.countries"></i>
    </div>
    <div class="value">
      <select v-model="setting.region" :name="addns('region', ns)">
        <option value=""></option>
        <option v-for="o in regions" :value="o.code">{{ o.name }}</option>
      </select><i class="icon16 loading" v-if="view.loading.regions"></i>
    </div>
    <div class="value"><span class="hint">{{'Доставка будет ограничена только выбранной страной или регионом страны'|localized}}</span></div>
  </wa-field>
</template>

<script>
import WaL10n from "../wa-l10n";

export default {
  mixins: [WaL10n],
  props: {
    name: {type: String, default: 'Ограничения по географии'},
    urls: Object,
    value: Object,
    ns: String
  },
  data() {
    return {
      setting: this.value,
      countries: [],
      regions: null,
      view: {
        loading: {
          countries: false,
          regions: false
        }
      }
    }
  },
  created() {
    if(this.setting.country !== '') this.loadRegions().done(this.loadCountries);
    else this.loadCountries();
  },
  methods: {
    loadCountries() {
      this.view.loading.countries = true;
      return $.get(
          this.urls.countries,
          r => {
            if (r.data && Array.isArray(r.data)) this.countries = r.data;
            if (this.setting.country.length && (this.countries.findIndex(c => c.code === this.setting.country) === -1))
              this.setting.country = '';
          }
      ).always(() => this.view.loading.countries = false)
    },
    loadRegions() {
      if (this.setting.country === '') {
        this.regions = [];
        this.setting.region = '';
        return;
      }
      this.view.loading.regions = true;
      return $.get(
          this.urls.regions,
          {country: this.setting.country},
          r => {
            if (r.data && Array.isArray(r.data)) this.regions = r.data;
            if (!this.setting.region || (this.setting.region.length && (this.regions.findIndex(r => r.code === this.setting.region) === -1)))
              this.setting.region = '';
          }
      ).always(() => this.view.loading.regions = false)
    }
  },
  watch: {
    'setting.country': function (newV, oldV) {
      if (newV !== oldV) this.loadRegions();
    }
  }
}
</script>
