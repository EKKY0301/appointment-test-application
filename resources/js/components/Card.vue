<template>
  <div class="card">
    <slot name="header"/>
    <div>{{ item.title }}</div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({ item: Object })
const emit  = defineEmits(['delete'])
function onDelete() { emit('delete', props.item.id) }
</script>

<style scoped>
.card { 
    
 }
</style>