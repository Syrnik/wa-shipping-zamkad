<template>
    <wa-field :name="name">
        <div class="value" :class="value_class"><slot /></div>
    </wa-field>
</template>

<script>
    import waField from './wa-field.vue'

    export default {
        props: {name: {default: ''}, value_class: {default: ''}},
        components: { waField }
    }
</script>
