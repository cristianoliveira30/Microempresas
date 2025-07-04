<script setup>
import { ref, onMounted, defineExpose } from 'vue';
import { AgGridVue } from "ag-grid-vue3";
import { ModuleRegistry } from '@ag-grid-community/core';
import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model';
import { AllCommunityModule, themeAlpine, colorSchemeDarkBlue } from 'ag-grid-community';
import FileNotFound from './FileNotFound.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

const props = defineProps({
    rowData: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:edits'])
const edits = ref([]);
const theme = ref(themeAlpine);
const internalGridRef = ref(null);
const columnDefs = ref([
    { field: 'id', headerName: 'ID', flex: 1, editable: false },
    {
        field: 'name', headerName: 'Nome', flex: 1, editable: true,
        cellClassRules: {
            'bg-green-200 dark:bg-teal-600': params =>
                edits.value.some(e => e.id === params.data.id && e.field === 'name')
        }
    },
    {
        field: 'price', headerName: 'Preço', flex: 1, editable: true,
        cellClassRules: {
            'bg-green-200 dark:bg-teal-600': params =>
                edits.value.some(e => e.id === params.data.id && e.field === 'price')
        }
    },
    {
        field: 'stock',
        headerName: 'Estoque',
        flex: 1,
        editable: true,
        cellClassRules: {
            'bg-green-200 dark:bg-teal-600': params =>
                edits.value.some(e => e.id === params.data.id && e.field === 'stock')
        }
    },
    {
        field: 'is_active',
        headerName: 'Status',
        editable: true,
        cellEditor: 'agSelectCellEditor',
        cellEditorParams: {
            values: ['Ativo', 'Inativo', 'Pendente'],
        },
        cellClassRules: {
            'bg-green-200 dark:bg-teal-600': params =>
                edits.value.some(e => e.id === params.data.id && e.field === 'is_active')
        }
    }
]);
const defaultColDef = {
    editable: true,
    filter: true,
    flex: 1,
    minWidth: 100,
};
// Evento ao editar células
const onCellValueChanged = (event) => {
    const change = {
        id: event.data.id,
        field: event.colDef.field,
        oldValue: event.oldValue,
        newValue: event.newValue
    }

    if (change.oldValue === change.newValue) return

    const index = edits.value.findIndex(e =>
        e.id === change.id && e.field === change.field
    )

    if (index !== -1) {
        edits.value[index].newValue = change.newValue
    } else {
        edits.value.push(change)
    }

    // Emite para o pai
    emit('update:edits', edits.value)
}
// Função reativa para expor `api` corretamente após estar carregada
const getApi = () => internalGridRef.value?.api ?? null;
defineExpose({
    getApi
});
onMounted(() => {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeAlpine;
});
</script>
<template>
    <div class="col-span-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tabela de Produtos</h3>

        <div v-if="rowData.length > 0" style="width: 100%; height: 300px;">
            <AgGridVue 
                :modules="[ClientSideRowModelModule]" 
                :defaultColDef="defaultColDef" 
                :columnDefs="columnDefs"
                :rowData="rowData" 
                :theme="theme" 
                ref="internalGridRef" 
                @cellValueChanged="onCellValueChanged"
                style="width: 100%; height: 300px;" />
        </div>

        <div v-else
            class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
            <FileNotFound />
        </div>
    </div>
</template>