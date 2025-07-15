import { ref } from 'vue'
import axios from 'axios'
import { DateTime } from 'luxon'

export default function useAppointments() {
    const appointments = ref([])
    const zone = Intl.DateTimeFormat().resolvedOptions().timeZone

    const load = async () => {
        const { data } = await axios.get('/api/appointments')
        appointments.value = data.map(a => ({
            ...a,
            date: DateTime.fromISO(a.date).setZone(zone).toISODate()
        }))
    }

    const create = async ({ title, date }) => {
        const { data } = await axios.post('/api/appointments', { title, date })
        const app = {
            ...data,
            date: DateTime.fromISO(data.date).setZone(zone).toISODate()
        }
        appointments.value.push(app)
        return app
    }

    const remove = async id => {
        await axios.delete(`/api/appointments/${id}`)
        appointments.value = appointments.value.filter(a => a.id !== id)
    }

    return { appointments, load, create, remove }
}
