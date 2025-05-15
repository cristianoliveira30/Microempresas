<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import AddPedido from './Partials/AddPedido.vue';

const props = defineProps({
    produtos: {
        type: Array,
        default: () => []
    }
})
const rowData = ref([...props.produtos])
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
                <div>
                    <div class="margin-3">
                        <AddPedido :produtos="rowData" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
