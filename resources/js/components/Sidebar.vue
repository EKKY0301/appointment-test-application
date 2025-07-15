<template>
  <aside
    :class="[
      'transition-all duration-300 overflow-hidden bg-white/50 rounded',
      opened ? 'w-full p-6' : 'w-0 p-0'
    ]"
  >
    <div class="w-full md:px-[25%] flex justify-center">
        <button :class="[
            'border-2 flex-1 text-center rounded-l-md cursor-pointer transition-all duration-200 hover:bg-amber-400/50',
            viewMode === 'monthly'? 'bg-amber-400/50' : ''
        ]" @click="setMonthly"> Mensual </button>
        <button :class="[
            'border-2 flex-1 text-center rounded-r-md cursor-pointer transition-all duration-200 hover:bg-amber-400/50',
            viewMode === 'daily'? 'bg-amber-400/50' : ''
        ]" @click="setDaily"> Diario </button>
    </div>
    
  </aside>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useSidebarStore } from '@/stores/sidebar'
import { useCalendarStore } from '../stores/calendar'

const { opened } = storeToRefs(useSidebarStore())
const calendarStore = useCalendarStore()
const { viewMode } = storeToRefs(calendarStore)
const { setDaily, setMonthly } = calendarStore
</script>