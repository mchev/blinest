<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Tip from '@/Components/Tip.vue'

const props = defineProps({
  room: Object,
})

const form = useForm({
  password: null,
})

const submit = () => {
  if (form.password && form.password.length > 0)
    form.get(`/rooms/${props.room.slug}`)
}
</script>
<template>
  <AppLayout>
    <Card class="mx-auto max-w-xl p-12">
      <template #header>
        <div class="flex flex-col">
          <h1 class="text-xl uppercase">{{ room.name }}</h1>
          <p class="mt-6 flex items-center text-orange-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Cette room est protégée par un mot de passe
          </p>
        </div>
      </template>
      <form @submit.prevent="submit">
        <TextInput type="passsword" v-model="form.password" :error="form.errors.password" :label="__('Password')" />
        <LoadingButton :loading="form.processing" class="btn-primary mt-4 ml-auto"
          ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
          {{ __('Login') }}</LoadingButton
        >
      </form>
    </Card>
    <Tip class="mx-auto max-w-xl">Si vous êtes le propriétaire de la room et que vous avez oublié le mot de passe, vous pouvez le modifier sur <Link :href="route('rooms.index')" class="underline">la page d'édition de la room.</Link></Tip>
  </AppLayout>
</template>
