import { defineStore } from 'pinia'
import axios from 'axios'

export const useAppStore = defineStore('clinic', {
  state: () => ({
    patients: [],
    appointments: [],
    doctors: [],
    loading: false,
  }),
  actions: {
    async fetchPatients() {
      this.loading = true
      const { data } = await axios.post('/api/users/search',  { "role": "patient" } )
      this.patients = data.data
      this.loading = false
    },
    async fetchDoctors() {
      this.loading = true
      const { data } = await axios.post('/api/users/search', { "role": "doctor" } )
      this.doctors = data.data
      this.loading = false
    },
    async fetchAppointments() {
      this.loading = true
      const { data } = await axios.get('/api/appointments')
      this.appointments = data
      this.loading = false
    },
    async addAppointment(appt) {
      this.loading = true
      await axios.post('/api/appointments', appt)
      this.loading = false
    },
    // …otros métodos (update, delete)
    async deteleAppointment(id){
      this.loading = true
      const { data } = await axios.delete('/api/appointments/'+id)
      this.loading = false
    }
  },
  getters: {
    appointmentsByDate: (state) => (date) =>
      state.appointments.filter(a => a.date.slice(0,10) === date),
  }
})
