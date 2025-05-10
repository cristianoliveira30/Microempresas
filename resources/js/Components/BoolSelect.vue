<script setup>
const props = defineProps({
  modelValue: { type: Boolean, default: ''},
  trueLabel: { type: String, default: 'Sim' },
  falseLabel: { type: String, default: 'Não' }
})
const emit = defineEmits(['update:modelValue'])

const updateValue = (event) => {
  emit('update:modelValue', event.target.value === 'true')
}
</script>

<template>
  <select
    :value="modelValue.toString()"
    @change="updateValue"
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
           focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500
           dark:focus:ring-indigo-600 rounded-md shadow-sm"
  >
    <option :value="true">{{ trueLabel }}</option>
    <option :value="false">{{ falseLabel }}</option>
  </select>
</template>
