<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaHistorico from '@/Components/AgGridVendaHistorico.vue';

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    }
})
console.log('HistoricoAlt recebido:', props.data);
// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.data])
const csvExported = ref(false);
const gridRef = ref(null);
const exportToCsv = () => {
    const api = gridRef.value?.getApi();
    exportGridToCsv(api, 'RelatorioHistoricoAlteracaoProduto.csv');
};
</script>

<template>
    <FormSection>
        <template #title>
            Histórico de Produtos
        </template>

        <template #description>
            Veja aqui as alterações e adições de Produtos dos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVendaHistorico :rowData="rowData" reportName="Histórico Alteração Produto" ref="gridRef"/>
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
