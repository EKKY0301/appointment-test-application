<!-- resources/js/components/ToastContainer.vue -->
<template>
  <div class="fixed top-4 right-4 space-y-2 z-50">
    <div
      v-for="t in toasts"
      :key="t.id"
      @click="removeToast(t.id)"
      :class="[
        'px-4 py-2 rounded shadow cursor-pointer',
        {
          'bg-green-500 text-white': t.type === 'success',
          'bg-red-500   text-white': t.type === 'error',
          'bg-blue-500  text-white': t.type === 'info',
        }
      ]"
    >
      {{ t.message }}
    </div>
  </div>
</template>

<script setup>
import { toRefs }    from 'vue'
import { toastState, removeToast } from '../utils/useToast'

const { toasts } = toRefs(toastState)
</script>
