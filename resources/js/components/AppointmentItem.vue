<template>
  <li class="flex justify-between items-center border p-2">
    <span>{{ appointment.title }} — {{ appointment.date }}</span>
    <button @click="emitDelete" class="text-red-600">🗑️</button>
  </li>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  appointment: { type: Object, required: true }
})
const emit = defineEmits(['delete'])

function emitDelete() {
  emit('delete', props.appointment.id)
}
</script>
