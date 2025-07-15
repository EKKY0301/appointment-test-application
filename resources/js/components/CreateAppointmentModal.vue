<!-- components/AppointmentModal.vue -->
<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full md:max-w-md max-w-[80vw] p-6">
      <h2 class="text-xl font-bold mb-4">Crear Appointment</h2>
      <form @submit.prevent="submit">
        <!-- Título -->
        <div class="mb-4">
          <label class="block mb-1">Título</label>
          <input
            v-model="form.title"
            type="text"
            required
            class="w-full border rounded p-2"
          />
        </div>

        <!-- Inicio -->
        <div class="mb-4">
          <label class="block mb-1">Inicio</label>
          <input
            v-model="form.start_local"
            type="datetime-local"
            required
            class="w-full border rounded p-2"
          />
        </div>

        <!-- Fin -->
        <div class="mb-4">
          <label class="block mb-1">Fin</label>
          <input
            v-model="form.end_local"
            :min="form.start_local"
            :max="endOfDay"
            type="datetime-local"
            required
            class="w-full border rounded p-2"
          />
        </div>

        <!-- Paciente -->
        <div class="mb-4">
          <label class="block mb-1">Paciente</label>
          <select
            v-model="form.patient_id"
            required
            class="w-full border rounded p-2"
          >
            <option disabled value="">Selecciona paciente</option>
            <option
              v-for="p in patients"
              :key="p.id"
              :value="p.id"
            >{{ p.name }}</option>
          </select>
        </div>

        <!-- Doctor -->
        <div class="mb-4">
          <label class="block mb-1">Doctor</label>
          <select
            v-model="form.doctor_id"
            required
            class="w-full border rounded p-2"
          >
            <option disabled value="">Selecciona doctor</option>
            <option
              v-for="d in doctors"
              :key="d.id"
              :value="d.id"
            >{{ d.name }}</option>
          </select>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="close"
            class="px-4 py-2 border rounded cursor-pointer hover:bg-black/10 transition-colors duration-200"
          >Cancelar</button>
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 bg-blue-600 text-white rounded"
          >Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { DateTime } from 'luxon'
import { useAppStore } from '../stores/useAppStore'
import { toastError, toastSuccess } from '../utils/useToast'

// Props & Emite
const props = defineProps({
  isOpen: Boolean
})
const emit = defineEmits(['update:isOpen'])

// Store de clínca
const appStore = useAppStore()
const { addAppointment } = appStore
const { patients, doctors, loading } = storeToRefs(appStore)

// Cargar listas
onMounted(() => {
  appStore.fetchPatients()
  appStore.fetchDoctors()
})

const a = computed(()=>{
    return patients
})

// Formulario local
const form = ref({
  title: '',
  start_local: '',
  end_local: '',
  patient_id: '',
  doctor_id: ''
})

// Cierra modal
function close() {
  emit('update:isOpen', false)
}

function handleCloseModal(ev) {
    ev.stopPropagation()
    close()
}

// Envía el appointment
async function submit() {
  // convertir a UTC ISO
  const startISO = DateTime.fromISO(form.value.start_local)
    .toUTC()
    .toFormat("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'")
  const endISO = DateTime.fromISO(form.value.end_local)
    .toUTC()
    .toFormat("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'")

  const payload = {
    title:       form.value.title,
    start_at:    startISO,
    end_at:      endISO,
    status:      'pending',
    patient_id:  Number(form.value.patient_id),
    doctor_id:   Number(form.value.doctor_id)
  }

  try {
    await addAppointment(payload)
    close()
  } catch (err) {
    if(`${err}`.includes('422')){
      toastError('Error al agendar una cita, Horario ya ocupado')
    }else{
      toastError('Error al agendar una cita')
    }
  } finally {
    toastSuccess('Creado con éxito', 2000)
  }
}

const endOfDay = computed(() => {
  if (!form.value.start_local) return ''
  const date = new Date(form.value.start_local)
  date.setHours(23, 59, 59, 999)
  // formato para datetime-local: "YYYY-MM-DDTHH:MM"
  return date.toISOString().slice(0,16)
})
</script>
