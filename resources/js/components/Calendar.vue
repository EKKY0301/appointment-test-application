<template>
  <div class="relative w-full aspect-square bg-white rounded shadow select-none">
    <!-- Botón de alternar vista -->
    <button
      v-if="viewMode === 'daily' && isAfterToday"
      @click="modalOpen = true"
      class="absolute right-5 bottom-2 px-2 py-1 bg-amber-500 text-2xl font-bold rounded z-50 text-white w-10 aspect-square cursor-pointer hover:bg-amber-400/90 transition-all duration-300 shadow hover:scale-110"
    >
      +
    </button>

    <!-- Cabecera: mes/año o día seleccionado y controles -->
    <div class="flex items-center justify-between h-[10%] bg-amber-300 rounded-t-md px-4">
      <button @click="prev" class="text-xl font-bold h-full cursor-pointer w-8">‹</button>
      <h2 class="font-bold text-center flex-1">{{ headerTitle }}</h2>
      <button @click="next" class="text-xl font-bold h-full cursor-pointer w-8">›</button>
    </div>

    <!-- Vista Mensual -->
    <div v-if="viewMode === 'monthly'" class="md:h-[90%] h-[40%] flex flex-col">
      <!-- Días de la semana -->
      <div class="flex text-center font-semibold md:h-[7.5%] h-[15%]">
        <div v-for="d in weekDays" :key="d" class="flex-1 flex items-center justify-center bg-amber-100">
          {{ d }}
        </div>
      </div>
      <!-- Cuadrícula de mes -->
      <div class="grid grid-cols-7 auto-rows-fr gap-1 flex-1 p-1">
        <div
          v-for="day in calendarDays"
          :key="day.date.toISOString()"
          :class="[
            'relative flex items-center justify-center p-2 rounded cursor-pointer hover:bg-amber-500/20',
            day.currentMonth ? 'bg-white/50' : 'text-gray-400 bg-black/5',
            isSelected(day.date) ? 'border-2 border-amber-500' : ''
          ]"
          @click="onDayClick(day.date)"
        >
          <span>{{ day.date.getDate() }}</span>
          <span
            v-if="day.hasEvent"
            class="absolute top-1 right-1 w-4 h-4 bg-amber-400 rounded-full"
          />
        </div>
      </div>
    </div>

    <!-- Vista Diaria -->
    <div v-else class="h-[90%] overflow-y-auto border-t border-b border-gray-200 p-2">
      <div
        v-for="hour in hours"
        :key="hour"
        class="relative h-16 border-b border-gray-200"
      >
        <!-- Etiqueta de hora -->
        <span class="absolute left-2 top-1 text-xs text-gray-500">{{ hourLabel(hour) }}</span>
        <!-- Eventos en esta hora -->
        <div
          v-for="appointment in eventsForHour(hour)"
          :key="appointment.id"
          class="absolute
+          left-16   /* reduce el offset en pantallas pequeñas */
+          right-2
+          bg-amber-400 text-white rounded p-1 text-sm overflow-hidden z-30" 
          :style="eventStyle(appointment)"
          @click="handleOpenAppintment(appointment)"
        >
          {{ appointment.title }}
        </div>
      </div>
    </div>
  </div>
  <CreateAppointmentModal
    :is-open="modalOpen"
    @update:isOpen="modalOpen = $event"
  />
  <ShowAppointmentModal
    :is-open="showModalOpen"
    :appointment="selectedAppointment"
    @update:isOpen="showModalOpen = $event"
  />
</template>

<script setup>
import { ref } from 'vue'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useCalendarStore } from '../stores/calendar'
import CreateAppointmentModal from './CreateAppointmentModal.vue'
import ShowAppointmentModal from './ShowAppointmentModal.vue'

// Store global de vista
const calendar = useCalendarStore()
const { viewMode, selectedDate } = storeToRefs(calendar)

// Props de eventos
const props = defineProps({ appointments: Array })

const modalOpen = ref(false)
const showModalOpen = ref(false)
const selectedAppointment = ref(props.appointments[0])

// Computar año y mes desde selectedDate
const current = computed(() => ({
  year: selectedDate.value.getFullYear(),
  month: selectedDate.value.getMonth()
}))

// Helpers de nombres
const monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
const weekDays = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom']

// Función para chequear si un día es el seleccionado
function isSelected(d) {
  return new Date(selectedDate.value).toDateString() === d.toDateString()
}

function handleOpenAppintment(appointment) {
  selectedAppointment.value = appointment;
  showModalOpen.value = true;
}

const isAfterToday = computed(()=>{
  const today = new Date()
  today.setDate(today.getDate() - 1)
  const selected = new Date(selectedDate.value)
  return today < selected
})

// Generar celdas del mes
const calendarDays = computed(() => {
  const { year, month } = current.value
  const first = new Date(year, month, 1)
  const last = new Date(year, month + 1, 0)
  const startWeekday = (first.getDay() + 6) % 7
  const days = []
  for (let i = startWeekday - 1; i >= 0; i--) {
    const d = new Date(first)
    d.setDate(first.getDate() - (i + 1))
    days.push({ date: d, currentMonth: false, hasEvent: hasEvent(d) })
  }
  for (let d = 1; d <= last.getDate(); d++) {
    const dd = new Date(year, month, d)
    days.push({ date: dd, currentMonth: true, hasEvent: hasEvent(dd) })
  }
  while (days.length % 7 !== 0) {
    const d = new Date(days[days.length - 1].date)
    d.setDate(d.getDate() + 1)
    days.push({ date: d, currentMonth: false, hasEvent: hasEvent(d) })
  }
  return days
})

// Estado diario usa selectedDate
const today = selectedDate

// Horas 0..23
const hours = Array.from({ length: 24 }, (_, i) => i)

// Título dinámico
const headerTitle = computed(() => {
  if (viewMode.value === 'monthly') {
    return `${monthNames[current.value.month]} ${current.value.year}`
  }
  return today.value.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
})

// Navegar vistas
function prev() {
  viewMode.value === 'monthly' ? prevMonth() : prevDay()
}
function next() {
  viewMode.value === 'monthly' ? nextMonth() : nextDay()
}
function toggleView() {
  calendar.toggleView()
}

// Prev/Next ajustar selectedDate según modo
function prevMonth() {
  const d = new Date(selectedDate.value)
  d.setMonth(d.getMonth() - 1)
  calendar.setDate(d)
}
function nextMonth() {
  const d = new Date(selectedDate.value)
  d.setMonth(d.getMonth() + 1)
  calendar.setDate(d)
}
function prevDay() {
  const d = new Date(selectedDate.value)
  d.setDate(d.getDate() - 1)
  calendar.setDate(d)
}
function nextDay() {
  const d = new Date(selectedDate.value)
  d.setDate(d.getDate() + 1)
  calendar.setDate(d)
}

function onDayClick(d) {
  calendar.setDate(d)
  calendar.setDaily()
}

// Filtrar eventos del día
const filteredEvents = computed(() =>
  props.appointments.filter(e => 
    new Date(e.start_at).toDateString() === new Date(selectedDate.value).toDateString()
  )
)

function eventsForHour(hour) {
  return filteredEvents.value.filter(e => new Date(`${e.start_at}`).getHours() === hour)
}

// Labels y estilo de evento
function hourLabel(h) {
  return h.toString().padStart(2, '0') + ':00'
}

function eventStyle(e) {
  const start = new Date(e.start_at)
  const end   = new Date(e.end_at)

  const startMinutes = start.getHours() * 60 + start.getMinutes()
  let durationMins   = (end - start) / 60000

  // 1) Calcula cuántos minutos quedan hasta el fin del día
  const minsUntilMidnight = 24 * 60 - startMinutes

  // 2) Limita la duración para que no pase de ese máximo
  if (durationMins > minsUntilMidnight) {
    durationMins = minsUntilMidnight
  }

  // 3) Cada hora ocupa 4rem => 4rem / 60min = 0.066666... rem/min
  const remPerMin = 4 / 60

  // 4) Calcula top y height en rem
  const topRem    = startMinutes * remPerMin
  const heightRem = durationMins * remPerMin

  return {
    top:    `${topRem}rem`,
    height: `${heightRem}rem`
  }
}

// Comprobación de evento en mes
function hasEvent(d) {
  return props.appointments.some(e => {
    const ev = new Date(e.start_at)
    return (
      ev.getFullYear() === d.getFullYear() &&
      ev.getMonth() === d.getMonth() &&
      ev.getDate() === d.getDate()
    )
  })
}

</script>

<style scoped>
/* Ajusta colores, sombras o tipografía si quieres */
</style>
