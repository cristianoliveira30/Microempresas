<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
const props = defineProps({
  produtos: {
    type: Array,
    default: () => []
  }
})

console.log('Produtos recebidos:', props.produtos)
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Venda
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <SectionBorder />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
