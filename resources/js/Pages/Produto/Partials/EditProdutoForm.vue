<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import FileNotFound from '@/Components/FileNotFound.vue';

import { AgGridVue } from "ag-grid-vue3";
import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model';
import { ModuleRegistry } from '@ag-grid-community/core';
import '@ag-grid-community/styles/ag-theme-alpine.css';

// Registrar o módulo
ModuleRegistry.registerModules([ClientSideRowModelModule]);


const props = defineProps({
    user: Object,
    produto: Object,
});
const form = useForm({
    _method: 'PUT',
    name: props.produto?.name || '',
    price: props.produto?.price || '',
    photo: null,
});
// Dados da tabela (pode começar vazio)
const columnDefs = ref([
  { field: 'nome', headerName: 'Nome', flex: 1 },
  { field: 'preco', headerName: 'Preço', flex: 1 },
  { field: 'categoria', headerName: 'Categoria', flex: 1 },
]);
const rowData = ref([
  { nome: 'Produto A', preco: 100, categoria: 'Categoria 1' },
  { nome: 'Produto B', preco: 200, categoria: 'Categoria 2' },
]);
const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);
const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('produtos.update', props.produto.id), { // <-- ajuste a rota conforme o nome da sua
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};
const selectNewPhoto = () => {
    photoInput.value.click();
};
const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};
const deletePhoto = () => {
    router.delete(route('produto-photo.destroy', props.produto.id), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};
const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Editar Produto
        </template>

        <template #description>
            Edite informações de um produto ou delete.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="produto.photo_url" :alt="produto.name" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Exibir tabela ag grid de produto -->
            <div class="col-span-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tabela de Produtos</h3>

                <div v-if="rowData.length > 0" class="ag-theme-alpine-dark"
                    style="width: 100%; height: 300px;">
                    <AgGridVue
                    :modules="[ClientSideRowModelModule]"
                    :columnDefs="columnDefs"
                    :rowData="rowData"
                    class="ag-theme-alphine-dark"
                    style="width: 100%; height: 300px;"
                    />
                </div>

                <div v-else
                    class="flex flex-col items-center justify-center h-48 text-center text-gray-400 dark:text-gray-500 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
                    <FileNotFound />
                </div>
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
