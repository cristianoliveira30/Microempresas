<script setup>
import { ref, onMounted } from 'vue';
import { AgGridVue } from "ag-grid-vue3";
import { ModuleRegistry } from '@ag-grid-community/core';
import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model';
import { AllCommunityModule, themeAlpine, colorSchemeDarkBlue } from 'ag-grid-community';
import FileNotFound from './FileNotFound.vue';

ModuleRegistry.registerModules([AllCommunityModule]);

const props = defineProps({
    RowSent: {
        type: Array,
        default: () => []
    }
});
// Dados da tabela (pode começar vazio)
const columnDefs = ref([
    {
        field: 'nome',
        headerName: 'Nome',
        flex: 1,
        editable: true,
    },
    {
        field: 'preco',
        headerName: 'Preço',
        flex: 1,
        editable: true,
    },
    {
        field: 'categoria',
        headerName: 'Categoria',
        flex: 1,
        editable: true,
        cellEditor: 'agSelectCellEditor',
        cellEditorParams:
        {
            values: ['Alimento', 'Bebida', 'Indefinido']
        },
    },
    {
        field: 'status',
        headerName: 'Status',
        editable: true,
        cellEditor: 'agSelectCellEditor',
        cellEditorParams: {
            values: ['Ativo', 'Inativo', 'Pendente'],
        }
    }
]);

const defaultColDef = {
    editable: true,
    filter: true,
    flex: 1,
    minWidth: 100,
};
// seta o row data e passa reatividade
const rowData = ref(props.RowSent);

// define o tema do ag-grid
const theme = ref(themeAlpine);

// Evento ao editar células
const onCellValueChanged = (event) => {
  console.log('Coluna:', event.colDef.field)
  console.log('Valor antigo:', event.oldValue)
  console.log('Novo valor:', event.newValue)
  console.log('Linha editada:', event.data)
}

onMounted(() => {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeMaterial;
});
</script>
<template>
    <div class="col-span-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tabela de Produtos</h3>

        <div v-if="rowData.length > 0" style="width: 100%; height: 300px;">
            <AgGridVue 
                :modules="[ClientSideRowModelModule]" 
                :columnDefs="columnDefs" 
                :rowData="rowData" 
                :theme="theme"
                @cell-value-changed="onCellValueChanged"
                style="width: 100%; height: 300px;" />
        </div>

        <div v-else
            class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
            <FileNotFound />
        </div>
    </div>
</template>