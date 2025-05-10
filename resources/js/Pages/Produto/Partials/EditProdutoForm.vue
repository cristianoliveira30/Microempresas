<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AgGridCustomProduto from '@/Components/AgGridCustomProduto.vue';
import { showLoading, showSuccess, showError, showInfo } from '@/src/utils/alerts';

const props = defineProps({
    user: Object,
    produtos: {
        type: Array,
        default: () => []
    }
})
// Use `ref` reativo para passar ao AgGrid
const rowData = ref([...props.produtos]); // cria cópia reativa para edição
const edits = ref([]);
const form = useForm({
    _method: 'PUT',
    name: props.produtos?.name || '',
    price: props.produtos?.price || ''
});
const enviarAlteracoes = async () => {
  if (edits.value.length === 0) {
    showInfo('Nenhuma alteração', 'Você ainda não fez nenhuma edição.')
    return
  }

  try {
    showLoading('Salvando alterações...')

    await axios.post('/produtos/atualizar', {
      updates: edits.value
    })

    edits.value = []
    showSuccess('Alterações salvas!', 'Os dados foram atualizados com sucesso.')
  } catch (error) {
    console.error(error)
    showError('Erro ao salvar', 'Verifique sua conexão ou tente novamente mais tarde.')
  }
}
</script>

<template>
    <FormSection @submitted="enviarAlteracoes">
        <template #title>
            Editar Produto
        </template>

        <template #description>
            Edite informações de um produto ou delete.
        </template>

        <template #form>
            <!-- Exibir tabela ag grid de produto -->
            <AgGridCustomProduto :rowData="rowData" @update:edits="edits = $event" />
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Salvo
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                Salvar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
