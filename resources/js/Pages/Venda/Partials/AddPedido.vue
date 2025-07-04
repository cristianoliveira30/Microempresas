<script setup>
import { ref, computed } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue'
import { showSuccess, showError, showLoading, close } from '@/src/utils/alerts'
import AgGridCustomPedido from '@/Components/AgGridCustomPedido.vue'

const props = defineProps({
    produtos: {
        type: Object,
        default: () => []
    }
});
const produtoSelecionado = ref('')
const listaProdutos = ref([])
const alcunhaPedido = ref('') // <-- novo campo
const totalPedido = computed(() => {
    return listaProdutos.value.reduce((total, produto) => {
        return total + (produto.price * produto.quantity)
    }, 0).toFixed(2)
})
const addProduto = async () => {
    if (!produtoSelecionado.value) {
        showError('Erro', 'Selecione um produto para adicionar.')
        return
    }
    if (listaProdutos.value.find(p => p.id === produtoSelecionado.value)) {
        showError('Erro', 'Produto já adicionado.')
        return
    }
    // Corrigido: buscar no props.produto, pois é o que está no <select>
    const produto = props.produtos.find(p => p.id === produtoSelecionado.value)
    if (!produto) {
        showError('Erro', 'Produto não encontrado.')
        return
    }
    listaProdutos.value.push({
        ...produto,
        quantity: 1
    })
    console.log('produtoSelecionado.value', produtoSelecionado.value)
    console.log('produto encontrado', listaProdutos.value)
    produtoSelecionado.value = ''
}
const removerProduto = (id) => {
    listaProdutos.value = listaProdutos.value.filter(p => p.id !== id)
}
const enviarVenda = async () => {
    if (listaProdutos.value.length === 0) {
        showError('Erro', 'Adicione pelo menos um produto.')
        return
    }

    try {
        const response = await axios.post(route('pedido.store'), {
            alcunha: alcunhaPedido.value ? alcunhaPedido.value : 'Sem Identificação', // <-- só no pedido
            total: totalPedido.value,
            products: listaProdutos.value.map(p => ({
                id: p.id,
                name: p.name,
                quantity: p.quantity,
                price: p.price
            }))
        });
        listaProdutos.value = [];
        alcunhaPedido.value = '';
        console.log(response)
        console.log('Pedido enviado com sucesso:', response.data);
        await showSuccess('Pedido salvo!', 'Acesse o pedido no caixa!');
    } catch (e) {
        await showError('Erro ao salvar o pedido.');
    }
}
</script>

<template>
    <FormSection>
        <!-- Título e Descrição -->
        <template #title>
            Nova Venda
        </template>

        <template #description>
            Adicione produtos, selecione a forma de pagamento e envie para o caixa.
        </template>

        <template #form>
            <!-- Campo Alcunha do Pedido -->
            <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-300 dark:text-gray-100">Alcunha do Pedido</label>
                <input v-model="alcunhaPedido" type="text"
                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-800 dark:text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Nome do Pedido (opcional)">
            </div>

            <!-- Bloco 1: Seleção de Produto -->
            <div class="col-span-6">
                <label for="produto" class="block text-sm font-medium text-gray-300 dark:text-gray-100">Selecionar
                    Produto</label>
                <select v-model="produtoSelecionado"
                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-800 dark:text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option disabled value="">Escolha um produto</option>
                    <option v-for="produto in props.produtos" :key="produto.id" :value="produto.id">
                        {{ produto.name }} - R$ {{ produto.price }}
                    </option>
                </select>
                <PrimaryButton class="mt-2" @click="addProduto">Adicionar</PrimaryButton>
            </div>

            <!-- Bloco 2: Lista de Produtos Adicionados -->
            <div class="col-span-6">
                <AgGridCustomPedido 
                    ref="gridRef" 
                    :rowData="listaProdutos" 
                    @remove-produto="removerProduto"
                    @update:edits="edits = $event"
                    @update:rowData="listaProdutos = [...$event]" />
            </div>

            <!-- Bloco 3: Forma de Pagamento -->
            <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-300 dark:text-gray-100">Forma de Pagamento</label>
                <div class="mt-2 flex justify-between items-center flex-wrap gap-4">

                    <!-- Total -->
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Total do Pedido: R$ {{ totalPedido }}
                    </span>
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="listaProdutos.length === 0" @click="enviarVenda">
                Enviar para o Caixa
            </PrimaryButton>
        </template>
    </FormSection>
</template>