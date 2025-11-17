<script setup>
import { router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import FloatInput from '@/Components/FloatInput.vue';
import IntInput from '@/Components/IntInput.vue';
import BoolSelect from '@/Components/BoolSelect.vue';
import { showError, showInfo, showLoading, showSuccess } from '@/src/utils/alerts';
import axios from 'axios';

const props = defineProps({
    produto: Object,
});

const form = useForm({
    name: '',
    description: '',
    price: 0.0,
    cost_price: 0.0,
    stock: 0,
    status: false,
})

const addProduto = async () => {
    if (
        form.name &&
        form.description &&
        form.price !== 0 &&
        form.cost_price !== 0 &&
        form.stock !== null &&
        form.status !== null
    ) {
        try {
            showLoading('Adicionando Produto...');

            await axios.post('/produtos/adicionar', {
                name: form.name,
                description: form.description,
                price: form.price,
                cost_price: form.cost_price,
                stock: form.stock,
                is_active: form.status == true ? 1 : 0
            }, { withCredentials: true});

            showSuccess('Produto adicionado com sucesso!', 'Os dados foram adicionados com sucesso.');
            location.reload();
        } catch (error) {
            console.error(error);
            showError('Erro ao adicionar...', 'Verifique sua conexão ou tente novamente mais tarde.');
        }
    } else {
        showInfo('Campo Incorreto', 'Por favor preencha corretamente os campos.');
    }
}
</script>

<template>
    <FormSection @submitted="addProduto">
        <template #title>
            Adicionar Produto
        </template>

        <template #description>
            Crie um novo produto.
        </template>

        <template #form>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Nome" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                    autocomplete="name" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="description" value="Descrição" />
                <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" required autocomplete="off" />
                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Price -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="price" value="Preço" />
                <FloatInput id="price" v-model="form.price" class="mt-1 block w-full" required autocomplete="off" />
                <InputError :message="form.errors.price" class="mt-2" />
            </div>

            <!-- Cost Price -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="cost_price" value="Custo" />
                <FloatInput id="cost_price" v-model="form.cost_price" class="mt-1 block w-full" required autocomplete="off" />
                <InputError :message="form.errors.cost_price" class="mt-2" />
            </div>

            <!-- Stock -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="stock" value="Estoque" />
                <IntInput id="stock" v-model="form.stock" class="mt-1 block w-full" required autocomplete="off" />
                <InputError :message="form.errors.stock" class="mt-2" />
            </div>

            <!-- Status -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="status" value="Status" />
                <BoolSelect id="status" v-model="form.status" trueLabel="Ativo" falseLabel="Inativo"
                    class="mt-1 block w-full" />
                <InputError :message="form.errors.status" class="mt-2" />
            </div>

        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Salvo
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Salvar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
