<script setup>
import { ref } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue'
import { showSuccess, showError, showLoading } from '@/src/utils/alerts'
import AgGridCustomPedido from '@/Components/AgGridCustomPedido.vue'

const props = defineProps({
    produtos: {
        type: Object,
        default: () => []
    }
});
const produtoSelecionado = ref('')
const listaProdutos = ref([])
const formaPagamento = ref('pix')
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

    showLoading('Aguarde', 'Enviando para o caixa...')

    // Simular envio
    setTimeout(() => {
        showSuccess('Feito', 'Venda enviada para o caixa com sucesso!')
        listaProdutos.value = []
    }, 1000)
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
                />
            </div>

            <!-- Bloco 3: Forma de Pagamento -->
            <div class="col-span-6">
                <label class="block text-sm font-medium text-gray-300 dark:text-gray-100">Forma de Pagamento</label>
                <div class="mt-2 space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" value="dinheiro" v-model="formaPagamento"
                            class="form-radio text-indigo-600 border-gray-600" />
                        <span class="ml-2 dark:text-white">Dinheiro</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" value="pix" v-model="formaPagamento"
                            class="form-radio text-indigo-600 border-gray-600" />
                        <span class="ml-2 dark:text-white">PIX</span>
                    </label>
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