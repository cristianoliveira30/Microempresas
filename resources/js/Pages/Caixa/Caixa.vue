<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import AgGridCustomCaixa from '@/Components/AgGridCustomCaixa.vue';
import FinalizarPedido from '../../Components/Modals/FinalizarPedido.vue';
import { ref } from 'vue';

const props = defineProps({
  pedidos: {
    type: Array,
    default: () => []
  },
  produtos: {
    type: Array,
    default: () => []
  }
})
console.log('Pedidos recebidos:', props.pedidos)

const edits = ref([]);
const produtosSelecionados = ref([]);
const pedidos = ref([...props.pedidos]);
const handleRemoveProduto = (id) => {
    produtosSelecionados.value = produtosSelecionados.value.filter(p => p.id !== id);
}
const handleEditsUpdate = (novos) => {
    edits.value = novos;
}
const carregarPedidos = async () => {
    console.log('chegou aqui no caixa');
    const response = await axios.get('/pedidos/data');
    pedidos.value = response.data;
    console.log('Pedidos atualizados:', pedidos.value);
}
produtosSelecionados.value = props.pedidos[0]?.produtos ?? []
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Caixa
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <AgGridCustomCaixa 
                    :row-data="pedidos"
                    :produtos="produtos"
                    @remove-produto="handleRemoveProduto"
                    @update:edits="handleEditsUpdate"
                    @pedido-atualizado="carregarPedidos"
                />
            </div>
        </div>
    </AppLayout>
</template>
