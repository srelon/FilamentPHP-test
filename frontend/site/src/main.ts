import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './routes/router'
import App from './App.vue'
import './assets/scss/main.scss'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.mount('#app')
