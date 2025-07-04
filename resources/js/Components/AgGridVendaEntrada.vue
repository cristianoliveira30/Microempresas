<script setup>
import { AgGridVue } from 'ag-grid-vue3'
import { ClientSideRowModelModule, colorSchemeDarkBlue, themeAlpine, themeMaterial } from 'ag-grid-community';
import { onMounted, ref } from 'vue';
import FileNotFound from './FileNotFound.vue';

const props = defineProps({
    rowData: {
        type: Array,
        default: () => []
    },
    reportName: String
})
const columnDefs = ref([
    { field: 'dia', headerName: 'Data', sortable: true, flex: 1, filter: true },
    { field: 'total_entrada', headerName: 'Entrada', sortable: true, flex: 1, filter: true }
]);
const defaultColDef = {
    filter: true,
    flex: 1,
    minWidth: 100,
};
const theme = ref(themeAlpine);
const agGridRef = ref(null);
const getApi = () => agGridRef.value?.api ?? null;
defineExpose({ getApi });
onMounted(() => {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeMaterial;
});
</script>

<template>
    <div class="col-span-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ reportName }}</h3>
        <div v-if="rowData.length > 0" style="width: 100%; height: 300px;">
                <AgGridVue
                    :modules="[ClientSideRowModelModule]"
                    :defaultColDef="defaultColDef"
                    :columnDefs="columnDefs"
                    :rowData="rowData"
                    :theme="theme"
                    ref="agGridRef"
                    style="width: 100%; height: 300px;"
                />
            </div>
    
            <div v-else
                class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
                <FileNotFound />
            </div>
    </div>
</template>
