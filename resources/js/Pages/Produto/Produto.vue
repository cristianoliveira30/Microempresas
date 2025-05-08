<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AddProdutoForm from './Partials/AddProdutoForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import EditProdutoForm from './Partials/EditProdutoForm.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Produto
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <AddProdutoForm :user="$page.props.auth.user" />

                    <SectionBorder />

                    <EditProdutoForm :user="$page.props.auth.user" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
