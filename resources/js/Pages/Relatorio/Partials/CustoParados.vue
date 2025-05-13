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
            Relatório de Venda por Parada
        </template>

        <template #description>
            Veja aqui os 10 produtos mais parados.
        </template>

        <template #form>
            <AgGridVenda :rowData="rowData" reportName="Venda Parada"/>
        </template>

        <template #actions>
            <!-- Relatório apenas leitura, sem ações -->
        </template>
    </FormSection>
</template>
