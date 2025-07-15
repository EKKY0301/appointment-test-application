// src/stores/calendar.js
import { defineStore } from 'pinia'

export const useCalendarStore = defineStore('calendar', {
  state: () => ({
    viewMode: 'monthly',      // 'monthly' | 'daily'
    selectedDate: new Date()  // fecha activa para la vista diaria
  }),
  actions: {
    setMonthly() {
      this.viewMode = 'monthly'
    },
    setDaily() {
      this.viewMode = 'daily'
    },
    toggleView() {
      this.viewMode = this.viewMode === 'monthly' ? 'daily' : 'monthly'
    },
    setDate(date) {
      this.selectedDate = date
    }
  }
})
