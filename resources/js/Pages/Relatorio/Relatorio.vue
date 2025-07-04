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
  },
  positivos: {
    type: Object,
    default: () => []
  },
  negativos: {
    type: Object,
    default: () => []
  }
})
console.log('Parametros recebidos:', props.produtos, props.positivos, props.negativos);
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
                <VendaPeriodo
                :data="props.positivos.vendaperiodo" 
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <VendaProduto 
                :data="props.positivos.vendaproduto"
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <VendaLucro 
                :data="props.positivos.gestaolucro"
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <EntradaCaixa 
                :data="props.positivos.entrada"
                />
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
                <SaidaCaixa 
                :data="props.negativos.saida"
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CustoParados 
                :data="props.negativos.vendaparada"
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CustoEstoque 
                :data="props.negativos.custoestoque"
                />
            </div>
        </div>
        <SectionBorder />
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <HistoricoAlteracaoProdutos 
                :data="props.negativos.historicoalteracao"
                />
            </div>
        </div>
        <SectionBorder />
    </AppLayout>
</template>
