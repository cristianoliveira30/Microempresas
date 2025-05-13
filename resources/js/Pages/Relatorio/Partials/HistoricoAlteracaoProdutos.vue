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
            Histórico de Alteração de Produtos
        </template>

        <template #description>
            Veja aqui as alterações de Produtos dos últimos 30 dias.
        </template>

        <template #form>
            <AgGridVenda :rowData="rowData" reportName="Histórico Alteração Produto"/>
        </template>

        <template #actions>
            <!-- Relatório apenas leitura, sem ações -->
        </template>
    </FormSection>
</template>
