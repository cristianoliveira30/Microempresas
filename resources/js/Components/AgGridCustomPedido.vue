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
  }
});

const emit = defineEmits(['remove-produto']);
const edits = ref([]);
const theme = ref(themeAlpine);
const internalGridRef = ref(null);
const columnDefs = ref([
  { field: 'id', headerName: 'ID', editable: false, flex: 1 },
  { field: 'name', headerName: 'Nome', editable: false, flex: 1 },
  { field: 'description', headerName: 'Descrição', editable: false, flex: 1 },
  { field: 'price', headerName: 'Preço', editable: false, flex: 1 },
  {
    field: 'quantity',
    headerName: 'Quantidade',
    editable: true,
    flex: 1,
    valueParser: params => {
      const val = parseInt(params.newValue)
      return isNaN(val) || val <= 0 ? 1 : val
    },
    cellClassRules: {
      'bg-green-200 dark:bg-teal-600': params =>
        edits.value.some(e => e.id === params.data.id && e.field === 'quantity')
    }
  },
  {
    headerName: 'Ações',
    field: 'actions',
    cellRenderer: 'AgGridBtnDel',
    onCellClicked: params => {
      emit('remove-produto', params.data.id)
    },
    editable: false,
    flex: 1
  }
]);
const defaultColDef = {
  filter: true,
  resizable: true,
};
const onCellValueChanged = (event) => {
  const field = event.colDef.field
  if (field === 'quantidade') {
    const novaQtd = parseInt(event.newValue)
    if (isNaN(novaQtd) || novaQtd <= 0) {
      event.node.setDataValue('quantidade', event.oldValue)
      alert('A quantidade deve ser maior que zero.')
      return
    }
  }
  const change = {
    id: event.data.id,
    field,
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
  emit('update:edits', edits.value)
}
const getApi = () => internalGridRef.value?.api ?? null;
defineExpose({ getApi });
onMounted(() => {
  const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeAlpine;
});
</script>

<template>
  <div class="col-span-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Produtos do Pedido</h3>

    <div v-if="rowData.length > 0" style="width: 100%; height: 300px;">
      <AgGridVue 
        :modules="[ClientSideRowModelModule]" 
        :defaultColDef="defaultColDef" 
        :columnDefs="columnDefs"
        :rowData="rowData" 
        :theme="theme" 
        :components="{ AgGridBtnDel }"
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
