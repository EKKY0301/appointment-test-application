<template>
  <h1 class="text-2xl mb-4">Gestor de Citas</h1>
  <AppointmentForm @created="handleCreate" />

  <ul class="space-y-2">
    <AppointmentItem
      v-for="app in appointments"
      :key="app.id"
      :appointment="app"
      @delete="handleDelete"
    />
  </ul>
</template>

<script setup>
import { onMounted } from 'vue'
import useAppointments from '../utils/useAppointments'
import AppointmentForm from './AppointmentForm.vue'
import AppointmentItem from './AppointmentItem.vue'

const { appointments, load, create, remove } = useAppointments()

onMounted(load)

async function handleCreate(payload) {
  await create(payload)
}

async function handleDelete(id) {
  await remove(id)
}
</script>
