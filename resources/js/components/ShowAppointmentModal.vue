<!-- components/AppointmentShowModal.vue -->
<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-2xl font-bold mb-4">Detalles de la Cita</h2>
      <div class="space-y-3">
        <div class="flex">
          <span class="font-semibold w-32">Título:</span>
          <span>{{ appointment.title }}</span>
        </div>
        <div class="flex">
          <span class="font-semibold w-32">Inicio:</span>
          <span>{{ startFormatted }}</span>
        </div>
        <div class="flex">
          <span class="font-semibold w-32">Fin:</span>
          <span>{{ endFormatted }}</span>
        </div>
        <div class="flex">
          <span class="font-semibold w-32">Estado:</span>
          <span class="capitalize">{{ appointment.status==="pending"? "Pendiente":"Finalizado" }}</span>
        </div>
        <div class="flex">
          <span class="font-semibold w-32">Paciente:</span>
          <span>{{ appointment.patient?.name || '—' }}</span>
        </div>
        <div class="flex">
          <span class="font-semibold w-32">Doctor:</span>
          <span>{{ appointment.doctor?.name || '—' }}</span>
        </div>
      </div>

      <div class="mt-6 flex justify-between">
        <button
          @click="handleDeleteSelected"
          class="px-4 py-2 bg-red-200 rounded hover:bg-red-400 transition cursor-pointer"
        >
          Borrar
        </button>
        <button
          @click="close"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition"
        >
          Cerrar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { DateTime } from 'luxon'
import { useAppStore } from '../stores/useAppStore'

const appStore = useAppStore()

const { deteleAppointment, fetchAppointments } = appStore

const props = defineProps({
  isOpen: Boolean,
  appointment: {
    type: Object,
    default: () => ({})
  }
})
const emit = defineEmits(['update:isOpen'])

function close() {
  emit('update:isOpen', false)
}

// Formatea con Luxon
const startFormatted = computed(() => {
  if (!props.appointment.start_at) return '—'
  const time = DateTime
    .fromISO(props.appointment.start_at, { zone: 'utc' })
    .toLocal()

  return time.toLocaleString(DateTime.TIME_WITH_LONG_OFFSET)
})
const endFormatted = computed(() => {
  if (!props.appointment.end_at) return '—'
  const time = DateTime
    .fromISO(props.appointment.end_at, { zone: 'utc' })
    .toLocal()

  return time.toLocaleString(DateTime.TIME_WITH_LONG_OFFSET)
})

async function handleDeleteSelected(){
    try {
        await deteleAppointment(props.appointment.id)
        await fetchAppointments()   // refresca lista
        close()
    } catch (err) {
        console.error('Error creando appointment:', err)
    }
}
</script>

