<script setup>
import { Trash } from 'lucide-vue-next'

const props = defineProps(['params'])
const remover = () => {
  if (typeof props.params.onClick === 'function') {
    props.params.onClick()
  } else {
    console.warn('onClick não definido em cellRendererParams')
  }
}
</script>

<template>
  <div class="h-full w-full flex items-center justify-center">
    <button @click="remover" class="text-red-500 hover:text-red-700">
      <Trash class="w-4 h-4" />
    </button>
  </div>
</template>
