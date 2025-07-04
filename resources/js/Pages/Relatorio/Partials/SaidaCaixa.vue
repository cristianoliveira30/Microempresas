<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { exportGridToCsv } from '@/src/utils/exportGrid';
import AgGridVendaSaida from '@/Components/AgGridVendaSaida.vue';

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    }
})
console.log('Saida recebida:', props.data);
// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.data])
const csvExported = ref(false);
const gridRef = ref(null);
const exportToCsv = () => {
    const api = gridRef.value?.getApi();
    exportGridToCsv(api, 'RelatorioSaida.csv');
};

</script>

<template>
    <FormSection>
        <template #title>
            Relatório de Saída de Caixa
        </template>

        <template #description>
            Veja aqui a saída de caixa dos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVendaSaida :rowData="rowData" reportName="Saída de Caixa" ref="gridRef"/>
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
