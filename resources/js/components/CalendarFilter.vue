<template>
  <div>
    <label>{{ label }}</label>
    <input 
      type="date" 
      class="text"
      :value="modelValue" 
      @input="$emit('update:modelValue', $event.target.value)" 
    />
  </div>
</template>

<script setup>
defineProps({
  modelValue: String,
  label: String,
})
</script>
