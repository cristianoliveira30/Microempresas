<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaPeriodo from '@/Components/AgGridVendaPeriodo.vue';

const props = defineProps({
    data: {
        type: Array,
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
            Relatório de Venda por Período
        </template>

        <template #description>
            Veja aqui todas as vendas realizadas nos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVendaPeriodo :rowData="rowData" reportName="Relatório Período" ref="gridRef"/>
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
