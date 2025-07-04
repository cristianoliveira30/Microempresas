<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaProduto from '@/Components/AgGridVendaProduto.vue';

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    }
})
console.log('VendaProduto recebido:', props.data);
// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.data])
const csvExported = ref(false);
const gridRef = ref(null);
const exportToCsv = () => {
    const api = gridRef.value?.getApi();
    exportGridToCsv(api, 'produtos.csv');
};
</script>

<template>
    <FormSection>
        <template #title>
            Relatório de Venda por Produto
        </template>

        <template #description>
            Veja aqui os 10 produtos mais vendidos.
        </template>

        <template #form>
            <AgGridVendaProduto :rowData="rowData" reportName="Relatório Produtos" />
        </template>

        <template #actions>
            <ActionMessage :on="csvExported" class="me-3">
                Feito
            </ActionMessage>
            <SecondaryButton :class="{ 'opacity-25': csvExported }" @click="exportToCsv" ref="gridRef">
                CSV
            </SecondaryButton>
        </template>
    </FormSection>
</template>
