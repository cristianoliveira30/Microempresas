<script setup>
import PrimaryButton from '../PrimaryButton.vue';
import AgGridViewProducts from '../AgGridViewProducts.vue';
import { showCashPaymentModal, showError, showPixPaymentModal, showSuccess, baseConfig } from '@/src/utils/alerts';
import { X, Trash } from 'lucide-vue-next'
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    pedido: {
        type: Object,
        default: () => ({})
    },
    produtos: {
        type: Array,
        default: () => []
    }
});

console.log('Pedido recebido:', props.pedido)
const emit = defineEmits(['fechar']);
const produtoSelecionado = ref('');
const houveAlteracao = ref(false);
const salvandoAlcunha = ref(false);
const listaProdutos = ref([])
const alcunhaPedido = ref(props.pedido.alcunha || '') // v-model para alcunha

const addProduto = async () => {
    try {
        console.log('Tentando adicionar produto:', produtoSelecionado.value);
        if (!produtoSelecionado.value) {
            showError('Erro', 'Selecione um produto para adicionar.');
            return;
        }
        if (listaProdutos.value.find(p => p.id === produtoSelecionado.value)) {
            showError('Erro', 'Produto já adicionado.');
            return;
        }
        const produto = props.produtos.find(p => p.id === produtoSelecionado.value);
        if (!produto) {
            showError('Erro', 'Produto não encontrado.');
            return;
        }
        listaProdutos.value.push({
            ...produto,
            quantity: 1
        });
        console.log('Produto adicionado:', produto);
        produtoSelecionado.value = '';
        await enviarVenda(); // Aguarda o envio para capturar erros
    } 
    catch (err) {
        console.error('Erro no addProduto:', err);
        showError('Erro ao adicionar produto.');
    }
};

const removerProduto = async (produtoId) => {
    try {
        console.log('Tentando remover produto:', produtoId);
        await axios.delete(route('pedido.produto.destroy', {
            pedido: props.pedido.id,
            produto: produtoId
        }));

        listaProdutos.value = listaProdutos.value.filter(p => p.id !== produtoId);
        houveAlteracao.value = true;
        console.log('Produto removido com sucesso:', produtoId);
        showSuccess('Sucesso', 'Produto removido com sucesso.');
    } catch (error) {
        console.error('Erro ao remover produto:', error);
        showError('Erro', 'Não foi possível remover o produto.');
    }
};

const salvarAlcunha = async () => {
    salvandoAlcunha.value = true;
    try {
        await axios.post(route('pedido.update', { pedido: props.pedido.id }), {
            alcunha: alcunhaPedido.value,
            total: props.pedido.total,
            products: listaProdutos.value.map(p => ({
                id: p.id,
                name: p.name,
                quantity: p.quantity,
                price: p.price
            }))
        });
        showSuccess('Alcunha atualizada!');
        houveAlteracao.value = true;
    } catch (e) {
        showError('Erro ao salvar alcunha.');
    }
    salvandoAlcunha.value = false;
};

const enviarVenda = async () => {
    try {
        console.log('Enviando venda. Produtos:', listaProdutos.value);
        if (listaProdutos.value.length === 0) {
            showError('Erro', 'Adicione pelo menos um produto.');
            return;
        }

        const payload = {
            alcunha: alcunhaPedido.value !== '' ? alcunhaPedido.value : 'Sem Identificação',
            total: calcularTotal(),
            products: listaProdutos.value.map(p => ({
                id: p.id,
                name: p.name,
                quantity: p.quantity,
                price: p.price
            }))
        };
        console.log('Payload para update:', payload);
        const response = await axios.post(route('pedido.update', { pedido: props.pedido.id }), payload);
        // Atualize o total do pedido com o valor do backend
        if (response.data && response.data.venda) {
            props.pedido.total = response.data.venda.total;
        }
        await showSuccess('Pedido atualizado!', 'As alterações foram salvas.');
        houveAlteracao.value = true; 
    } catch (e) {
        console.error('Erro ao atualizar o pedido:', e);
        showError('Erro ao atualizar o pedido.');
    }
}

const confirmarPagamento = async () => {
  const result = await Swal.fire({
    title: 'Escolha a forma de pagamento',
    icon: 'question',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'Pix',
    denyButtonText: 'Dinheiro',
    cancelButtonText: 'Cancelar',
    ...baseConfig
  });

  if (result.isConfirmed) {
    const confirmed = await showPixPaymentModal('/assets/img/PixQRCode.jpg');
    if (confirmed) {
      try {
        const response = await axios.post(route('pedido.pagar', {id: props.pedido.id}), {
          produtos: listaProdutos.value,
          method: 'pix',
        });
        if (response.data) {
            houveAlteracao.value = true; // Marca que houve alteração
            showSuccess('Sucesso', 'Pagamento via Pix confirmado.');
            emit('fechar', houveAlteracao.value); // Emitir evento para fechar o modal
            location.reload(); // Recarrega a página para atualizar o estado
        } else {
            showError('Erro', 'Não foi possível confirmar o pagamento via Pix.');
            console.error('Resposta inesperada do servidor:', response);
        }
      } catch (error) {
        console.error(error);
        showError('Erro', 'Erro ao confirmar pagamento Pix.');
      }
    }
  } else if (result.isDenied) {
    const cashData = await showCashPaymentModal(props.pedido.total);
    if (cashData) {
      try {
        const response = await axios.post(route('pedido.pagar', {id: props.pedido.id}), {
          produtos: listaProdutos.value,
          method: 'cash',
        });
        if (response.data) {
            houveAlteracao.value = true; // Marca que houve alteração
            showSuccess('Sucesso', `Pagamento em dinheiro confirmado. Troco: R$ ${cashData.troco.toFixed(2)}`);
            emit('fechar', houveAlteracao.value);
            location.reload(); // Recarrega a página para atualizar o estado
        }
      } catch (error) {
        console.error(error);
        showError('Erro', 'Erro ao confirmar pagamento em dinheiro.');
      }
    }
  }
};

const confirmarExclusaoPedido = async () => {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: 'Essa ação não poderá ser desfeita!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        ...baseConfig
    });

    if (!result.isConfirmed) return;

    try {
        await axios.delete(route('pedido.destroy', props.pedido.id));
        showSuccess('Sucesso', 'Pedido excluído com sucesso.');
        houveAlteracao.value = true; // Marca que houve alteração
        emit('fechar', houveAlteracao.value); // Emitir evento para fechar o modal
    } catch (error) {
        console.error('Erro ao excluir pedido:', error);
        showError('Erro', 'Não foi possível excluir o pedido.');
    }
};

const calcularTotal = () => {
    return listaProdutos.value.reduce((total, produto) => {
        // Garante que quantity e price são números
        const quantidade = Number(produto.quantity) || 0;
        const preco = Number(produto.price) || 0;
        return total + (quantidade * preco);
    }, 0);
};

onMounted(() => {
    if (props.pedido && props.pedido.produtos) {
        // Garante que cada produto tenha o campo quantity
        listaProdutos.value = props.pedido.produtos.map(p => ({
            ...p,
            quantity: p.quantity ?? p.pivot?.quantity ?? 1 // tenta pegar de p.quantity, depois do pivot, senão 1
        }));
    }
    if (props.pedido && props.pedido.alcunha) {
        alcunhaPedido.value = props.pedido.alcunha
    }
});
</script>

<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-[70%] shadow-lg">
            <div class="d-flex justify-between items-center mb-4 relative">
                <h2 class="text-xl font-bold mb-4 dark:text-white">Detalhes do Pedido #{{ pedido.id }}</h2>
                <button @click="$emit('fechar', houveAlteracao.value)" class="absolute top-2 right-2">
                    <X class="w-6 h-6 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100" />
                </button>
            </div>
            <!-- Bloco 1: Informações de Pedido -->
            <ul class="space-y-2 dark:text-white">
                <li>
                    <strong>Alcunha:</strong>
                    <input
                        v-model="alcunhaPedido"
                        @blur="salvarAlcunha"
                        class="ml-2 px-2 py-1 rounded border dark:bg-gray-700 dark:text-white"
                        placeholder="Sem Identificação"
                    />
                    <span v-if="salvandoAlcunha" class="ml-2 text-xs text-gray-400">Salvando...</span>
                </li>
                <li><strong>Status:</strong> {{ pedido.status }}</li>
                <li><strong>Criado em:</strong> {{ new Date(pedido.created_at).toLocaleString('pt-BR') }}</li>
                <li><strong>Total:</strong> {{ pedido.total }}</li>
            </ul>

            <!-- Bloco 3: Adição de Produto -->
            <div class="col-span-6 m-3 ">
                <AgGridViewProducts :row-data="listaProdutos" :produtos="produtos"
                    @remove-produto="removerProduto" @update:edits="edits = $event" />
            </div>
            <!-- Bloco 3: Adição de Produto -->
            <div class="col-span-6 m-3 ">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Adicionar Produtos</h3>
                <form @submit.prevent="addProduto">
                    <select v-model="produtoSelecionado"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option disabled value="">Adicionar Produto</option>
                        <option v-for="produto in props.produtos" :key="produto.id" :value="produto.id">
                            {{ produto.name }} - R$ {{ produto.price }}
                        </option>
                    </select>
                    <PrimaryButton class="mt-2" type="submit">Adicionar</PrimaryButton>
                </form>
            </div>
            <div class="flex justify-between m-3 relative">
                <button @click="confirmarExclusaoPedido" class="mt-6 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <Trash class="w-4 h-4 inline-block" />
                </button>
                <button @click="confirmarPagamento" class="mt-6 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Pagar
                </button>
            </div>
        </div>
    </div>
</template>