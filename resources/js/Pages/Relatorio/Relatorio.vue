<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import VendaPeriodo from './Partials/VendaPeriodo.vue';
import VendaProduto from './Partials/VendaProduto.vue';
import VendaLucro from './Partials/VendaLucro.vue';
import EntradaCaixa from './Partials/EntradaCaixa.vue';
import SaidaCaixa from './Partials/SaidaCaixa.vue';
import CustoParados from './Partials/CustoParados.vue';
import CustoEstoque from './Partials/CustoEstoque.vue';
import HistoricoAlteracaoProdutos from './Partials/HistoricoAlteracaoProdutos.vue';

const props = defineProps({
  produtos: {
    type: Array,
    default: () => []
  }
})

console.log('Produtos recebidos:', props.produtos)
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Relatório
            </h2>
        </template>
        
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-sky-500 leading-tight">
                Relatórios Positivos
            </h2>
        </div>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <VendaPeriodo />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <VendaProduto />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <VendaLucro />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <EntradaCaixa />
            </div>
        </div>
        <SectionBorder />

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-red-500 leading-tight">
                Relatórios Negativos
            </h2>
        </div>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <SaidaCaixa />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CustoParados />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CustoEstoque />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <HistoricoAlteracaoProdutos />
            </div>
        </div>
        <SectionBorder />
    </AppLayout>
</template>
