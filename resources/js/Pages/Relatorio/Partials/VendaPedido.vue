<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaPedido from '@/Components/AgGridVendaPedido.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => []
    }
})
console.log('VendaPeriodo recebido:', props.data);
// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.data]);
const csvExported = ref(false);
const gridRef = ref(null);
const exportToCsv = () => {
    const api = gridRef.value?.getApi();
    exportGridToCsv(api, 'RelatorioVendaPeriodo.csv');
};
</script>

<template>
    <FormSection>
        <template #title>
            Relatório de Venda de Pedidos
        </template>

        <template #description>
            Veja aqui todos os pedidos realizados nos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVendaPedido :rowData="rowData" reportName="Relatório Pedido" ref="gridRef"/>
        </template>

        <template #actions>
            <ActionMessage :on="csvExported" class="me-3">
                Feito
            </ActionMessage>
            <SecondaryButton :class="{ 'opacity-25': csvExported }" @click="exportToCsv">
                CSV
            </SecondaryButton>
        </template>
    </FormSection>
</template>
