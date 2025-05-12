<script setup>
import { ref } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import AgGridVenda from '@/Components/AgGridVenda.vue';

const props = defineProps({
    vendas: {
        type: Array,
        default: () => []
    }
})

// Cópia reativa da lista de vendas para o AgGrid
const rowData = ref([...props.vendas])
</script>

<template>
    <FormSection>
        <template #title>
            Relatório de Custo por Estoque
        </template>

        <template #description>
            Veja aqui os 10 maiores custos por estoque no período selecionado.
        </template>

        <template #form>
            <AgGridVenda :rowData="rowData" reportName="Custo por Estoque"/>
        </template>

        <template #actions>
            <!-- Relatório apenas leitura, sem ações -->
        </template>
    </FormSection>
</template>
