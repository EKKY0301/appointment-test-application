// src/stores/sidebar.ts
import { defineStore } from 'pinia'

export const useSidebarStore = defineStore('sidebar', {
    state: () => ({
        opened: true,    // estado inicial
    }),
    actions: {
        toggle() {
            this.opened = !this.opened
        }
    }
})
