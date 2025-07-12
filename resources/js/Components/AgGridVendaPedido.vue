<script setup>
import { AgGridVue } from 'ag-grid-vue3'
import { ClientSideRowModelModule, colorSchemeDarkBlue, themeAlpine, themeMaterial } from 'ag-grid-community';
import { computed, onMounted, ref } from 'vue';
import FileNotFound from './FileNotFound.vue';
import Chart from './Chart.vue';
import useSalesChart from '@/Composables/useSalesChart';

const props = defineProps({
    rowData: {
        type: Array,
        default: () => []
    },
    reportName: String
})
const columnDefs = ref([
    { field: 'id', headerName: 'ID', sortable: true, flex: 1, filter: true },
    { field: 'alcunha', headerName: 'Alcunha', sortable: true, flex: 1, filter: true },
    { field: 'total', headerName: 'Venda', sortable: true,  flex: 1},
    { field: 'payment_method', headerName: 'Pagamento', sortable: true,  flex: 1},
    { field: 'date', headerName: 'Data', sortable: true,  flex: 1},
]);
const defaultColDef = {
    filter: true,
    flex: 1,
    minWidth: 100,
};
const agGridRef = ref(null);
const viewMode = ref('table');
const theme = ref(themeAlpine);
const chartData = computed(() => useSalesChart(props.rowData));
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
        <div class="flex space-x-2 mb-2">
            <button
                @click="viewMode = 'table'"
                :class="[
                'px-4 py-1 text-sm rounded-lg border',
                viewMode === 'table' 
                    ? 'bg-blue-600 text-white border-blue-700' 
                    : 'bg-white text-gray-600 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'
                ]">
                Tabela
            </button>
            <button
                @click="viewMode = 'chart'"
                :class="[
                'px-4 py-1 text-sm rounded-lg border',
                viewMode === 'chart' 
                    ? 'bg-green-600 text-white border-green-700' 
                    : 'bg-white text-gray-600 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'
                ]">
                Gráfico
            </button>
            <button
                @click="viewMode = 'summary'"
                :class="[
                'px-4 py-1 text-sm rounded-lg border',
                viewMode === 'summary' 
                    ? 'bg-purple-600 text-white border-purple-700' 
                    : 'bg-white text-gray-600 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600'
                ]">
                Resumo
            </button>
        </div>
        <div v-if="viewMode === 'table' && rowData.length > 0" style="width: 100%; height: 300px;">
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

        <Chart 
            v-if="viewMode === 'chart'" 
            :type="chartData.type" 
            :options="chartData.options" 
            :series="chartData.series" 
        />

        <div v-if="viewMode === 'summary'" class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-gray-700 dark:text-gray-300">Resumo futuro aqui...</p>
        </div>

        <div v-if="rowData.length === 0"
            class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
            <FileNotFound />
        </div>
    </div>
</template>
