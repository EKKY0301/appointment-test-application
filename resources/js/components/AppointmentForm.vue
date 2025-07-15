<template>
  <form @submit.prevent="onSubmit" class="mb-4 flex gap-2">
    <input v-model="title" placeholder="Título" required class="border p-2 flex-1" />
    <input type="date" v-model="date" required class="border p-2" />
    <button type="submit" class="bg-green-600 text-white px-4">Añadir</button>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { defineEmits } from 'vue'

const emit = defineEmits(['created'])
const title = ref('')
const date  = ref('')

async function onSubmit() {
  const payload = { title: title.value, date: date.value }
  emit('created', payload)
  title.value = ''
  date.value  = ''
}
</script>
