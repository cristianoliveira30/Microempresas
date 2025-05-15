<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import AgGridVenda from '@/Components/AgGridVenda.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';

const props = defineProps({
    vendas: {
        type: Array,
        default: () => []
    }
})

// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.vendas])
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
            Relatório de entrada
        </template>

        <template #description>
            Veja aqui a entrada de caixa dos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVenda :rowData="rowData"  reportName="Entrada de Caixa"/>
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
