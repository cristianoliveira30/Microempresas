<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaLucro from '@/Components/AgGridVendaLucro.vue';

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    }
})
console.log('VendaLucro recebido:', props.data);
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
            Relatório de gestão de lucro.
        </template>

        <template #description>
            Veja os 10 produtos que mais geraram lucro.
        </template>

        <template #form>
            <AgGridVendaLucro :rowData="rowData" reportName="Relatório Lucrativo"/>
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
