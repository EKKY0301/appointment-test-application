import './bootstrap.js'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import Container from './components/Container.vue'

const el = document.getElementById('app')
if (el) createApp(Container).use(createPinia()).mount('#app')
