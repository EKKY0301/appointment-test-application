// resources/js/composables/useToast.js
import { reactive } from 'vue'

/** estado reactivo compartido */
export const toastState = reactive({
  toasts: []
})

let uid = 0

/** añade un toast y programa su auto‑dismiss */
export function addToast(message, type = 'info', duration = 3000) {
  const id = uid++
  toastState.toasts.push({ id, message, type })
  setTimeout(() => removeToast(id), duration)
}

/** elimina un toast por su id */
export function removeToast(id) {
  const i = toastState.toasts.findIndex(t => t.id === id)
  if (i !== -1) toastState.toasts.splice(i, 1)
}

/** helpers para cada tipo */
export function toastSuccess(msg, dur) { addToast(msg, 'success', dur) }
export function toastError(msg, dur)   { addToast(msg, 'error',   dur) }
export function toastInfo(msg, dur)    { addToast(msg, 'info',    dur) }

/** en componentes: */
export function useToast() {
  return { addToast, removeToast, toastSuccess, toastError, toastInfo }
}
