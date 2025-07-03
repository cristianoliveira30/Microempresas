    <script setup>
    import { ref, onMounted } from 'vue';
    import { AgGridVue } from "ag-grid-vue3";
    import { ModuleRegistry } from '@ag-grid-community/core';
    import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model';
    import { AllCommunityModule, themeAlpine, colorSchemeDarkBlue } from 'ag-grid-community';
    import FinalizarPedido from './Modals/FinalizarPedido.vue';
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

    const emit = defineEmits(['remove-produto', 'pedido-atualizado']);
    const theme = ref(themeAlpine);
    const internalGridRef = ref(null);
    const modalaberto = ref(false);
    const pedidoSelecionado = ref(null);
    const columnDefs = ref([
        { field: 'id', headerName: 'ID', editable: false, flex: 1 },
        {
            field: 'alcunha',
            headerName: 'Alcunha',
            flex: 1,
        },
        { field: 'quantity', headerName: 'Qtd. Produtos', editable: false, flex: 1 },
        { field: 'status', headerName: 'Status', editable: false, flex: 1 },
        {
            field: 'created_at',
            headerName: 'Criado em',
            width: 180,
            valueFormatter: (params) => {
                const data = new Date(params.value)
                return data.toLocaleString('pt-BR')
            }
        },
        { field: 'total', headerName: 'Total', editable: false, flex: 1 },
    ]);
    const defaultColDef = {
        filter: true,
        resizable: true,
    };
    const handleRowClick = (event) => {
        pedidoSelecionado.value = event.data;
        modalaberto.value = true;
    }
    const onFecharModal = (houveAlteracao) => {
        console.log('chegou no grid');
        modalaberto.value = false;
        if (!modalaberto.value) {
            houveAlteracao ?? emit('pedido-atualizado');
        };
    };
    onMounted(() => {
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        theme.value = isDark ? themeAlpine.withPart(colorSchemeDarkBlue) : themeAlpine;
    });
    </script>

    <template>
        <div class="col-span-8">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Pedidos</h3>

            <div v-if="rowData.length > 0" style="width: 100%; height: 300px;">
                <AgGridVue :modules="[ClientSideRowModelModule]" :defaultColDef="defaultColDef" :columnDefs="columnDefs"
                    :rowData="rowData" :theme="theme" :components="{ AgGridBtnDel }" ref="internalGridRef"
                    @row-clicked="handleRowClick" style="width: 100%; height: 300px;" />
                <!-- modal  -->
                <FinalizarPedido 
                    v-if="modalaberto" 
                    :produtos="produtos" 
                    :pedido="pedidoSelecionado" 
                    @fechar="onFecharModal" 
                />
            </div>
            <div v-else
                class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
                <FileNotFound />
            </div>
        </div>
    </template>
