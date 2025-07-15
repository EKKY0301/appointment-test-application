<template>
  <ToastContainer />
  <div class="flex flex-col w-screen h-screen">   <!-- padre con altura -->
    <NavBar />

    <div class="flex flex-1 overflow-hidden flex-col">
      <Sidebar />
      <div class="flex-1 min-h-0 overflow-auto py-6 md:px-10 px-3 flex">
        <Calendar :appointments="appointments"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import Sidebar from './Sidebar.vue'
import NavBar from './NavBar.vue'
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useAppStore } from '../stores/useAppStore'
import Calendar from './Calendar.vue'
import ToastContainer from './ToastContainer.vue'

// 1) Obtén la instancia del store
const appStore = useAppStore()

// 2) Extrae con storeToRefs sólo las props reactivas que uses en el template
const { appointments } = storeToRefs(appStore)

function reloadAll(){
  
  appStore.fetchDoctors()
  appStore.fetchPatients()
  appStore.fetchAppointments()
} 

onMounted(() => {
  // 3) Llama a las actions sobre la instancia original
  reloadAll()

  window.Echo
    .channel('appointments')
    .listen('AppointmentDeleted', () => {
      // cuando alguien borre, recarga
      console.log("se recibio")
      appStore.fetchAppointments()
    })
    window.Echo
    .channel('relaod')
    .listen('ReloadAll', () => {
      // cuando alguien borre, recarga
      console.log("se recibio")
      reloadAll()
    })
})

</script>