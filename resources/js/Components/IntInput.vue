<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  modelValue: [Number, String]
})
const emit = defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })

const updateValue = (event) => {
  const val = parseInt(event.target.value)
  emit('update:modelValue', isNaN(val) ? null : val)
}
</script>

<template>
  <input
    ref="input"
    type="number"
    step="1"
    :value="modelValue"
    @input="updateValue"
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
           focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500
           dark:focus:ring-indigo-600 rounded-md shadow-sm"
  />
</template>
