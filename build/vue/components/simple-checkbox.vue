<template>
    <div><input
            type="hidden"
            :value="falseValue"
            :name="ns"><input type="checkbox"
                              :value="trueValue"
                              :name="ns"
                              v-model="val"
                              @change="$emit('input', val)"><template v-if="!!$slots.default"> &mdash; </template><slot /><br v-if="!!$slots.description"><slot name="description" /></div>
</template>

<script>
    export default {
        props: {
            ns: {type: String},
            trueValue: {default: '1'},
            falseValue: {default: '0'},
            value: {}
        },
        data() {
            return {
                val: this.value
            }
        }
    }
</script>
