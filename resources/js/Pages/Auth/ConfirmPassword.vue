<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import LoadingButton from '@/Components/LoadingButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <TextInput
                    id="password"
                    :label="__('Password')"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :error="form.errors.password"
                    autofocus
                />
            </div>

            <div class="flex justify-end mt-4">
                <LoadingButton class="ml-4" :loading="form.processing" :disabled="form.processing">
                    Confirm
                </LoadingButton>
            </div>
        </form>
    </AppLayout>
</template>