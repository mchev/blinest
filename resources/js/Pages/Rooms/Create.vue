<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

defineProps({
  categories: Object,
})

const form = useForm({
  name: '',
  category_id: 1,
})

const store = () => {
  form.post(route('rooms.store'))
}
</script>
<template>
  <Head title="Create Room" />
  <AppLayout>
    <form @submit.prevent="store">
      <div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold tracking-tight">{{ __('Rooms') }}</h2>
          <p class="font-light sm:text-xl">Invites tes amis en privé, ou partage tes playlists en laissant ta room ouverte pour que tout le monde puisse jouer.</p>
          <div class="mt-6 flex justify-center">
            <SelectInput v-model="form.category_id" :error="form.errors.category_id" class="text-xl md:w-1/2" :label="__('Category')">
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </SelectInput>
          </div>
          <div class="mt-16 flex justify-center">
            <text-input v-model="form.name" :error="form.errors.name" class="text-xl" :placeholder="__('Room name')" />
          </div>
          <div class="my-8 flex justify-center">
            <loading-button :loading="form.processing" class="btn-primary btn-lg" type="submit">{{ __('Create the room') }}</loading-button>
          </div>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
