<script setup>
import { Trash } from 'lucide-vue-next'
defineProps(['data'])
const emit = defineEmits(['remove'])

const remover = () => {
  emit('remove', data.id)
}
</script>

<template>
  <div class="h-full w-full flex items-center justify-center">
    <button @click="remover" class="text-red-500 hover:text-red-700">
      <Trash class="w-4 h-4" />
    </button>
  </div>
</template>