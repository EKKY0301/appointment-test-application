<template>
    <div class="w-full px-6 bg-white py-4 max-h flex flex-row justify-between shadow">
        <div class="flex flex-row gap-2 items-center">
            <h1 class="text-xl font-medium"><strong class="text-2xl font-extrabold">Schedule</strong>me</h1>
        </div>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useSidebarStore } from '../stores/sidebar'
const sidebar = useSidebarStore()

// extraemos lo que necesitamos
const { toggle } = sidebar
const { opened } = storeToRefs(sidebar)

</script>