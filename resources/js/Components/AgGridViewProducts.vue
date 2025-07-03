<script setup>
import { ref, defineExpose, onMounted } from 'vue';
import { AgGridVue } from "ag-grid-vue3";
import { ModuleRegistry } from '@ag-grid-community/core';
import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model';
import { AllCommunityModule, themeAlpine, colorSchemeDarkBlue } from 'ag-grid-community';
import FileNotFound from './FileNotFound.vue';
import AgGridBtnDel from './AgGridBtnDel.vue';
ModuleRegistry.registerModules([AllCommunityModule]);

const props = defineProps({
    rowData: {
        type: Array,
        default: () => []
    },
    produtos: {
        type: Array,
        default: () => []
    }
});
console.log(props.rowData)
const emit = defineEmits(['remove-produto', 'update:edits']);
const theme = ref(themeAlpine);
const internalGridRef = ref(null);
const columnDefs = ref([
    { field: 'id', headerName: 'ID', editable: false, flex: 1 },
    {
        field: 'name',
        headerName: 'Nome',
        editable: false,
        flex: 2,
    },
    {
        field: 'price',
        headerName: 'Preço',
        flex: 1,
        editable: false // ou true se quiser editar e implementar lógica
    },
    {
        headerName: 'Quantidade',
        valueGetter: (params) => {
            return params.data.pivot?.quantity ?? params.data.quantity ?? 0;
        },
        editable: true,
        flex: 1
    },
    {
        field: 'total',
        headerName: 'Total',
        editable: false,
        flex: 1,
        valueGetter: (params) => {
            const price = params.data.price ?? 0;
            const quantity = params.data.pivot?.quantity ?? params.data.quantity ?? 0;
            return (price * quantity).toFixed(2);
        }
    },
    {
        headerName: 'Ações',
        field: 'actions',
        cellRenderer: 'AgGridBtnDel',
        cellRendererParams: params => ({
            onClick: () => emit('remove-produto', params.data.id)
        }),
        editable: false,
        flex: 1
    }
]);
const defaultColDef = {
    filter: true,
    resizable: true,
};
const getApi = () => internalGridRef.value?.api ?? null;
defineExpose({ getApi });
onMounted(() => {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeAlpine;
});
</script>

<template>
    <div class="col-span-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Pedidos</h3>

        <div v-if="rowData.length > 0" style="width: 100%; height: 270px;">
            <AgGridVue :modules="[ClientSideRowModelModule]" :defaultColDef="defaultColDef" :columnDefs="columnDefs"
                :rowData="rowData" :theme="theme" :components="{ AgGridBtnDel }"
                ref="internalGridRef" style="width: 100%; height: 270px;" />
        </div>
        <div v-else
            class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
            <FileNotFound />
        </div>
    </div>
</template>
