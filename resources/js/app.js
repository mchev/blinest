import './bootstrap'
import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

// APP
createInertiaApp({
  resolve: (name) => require(`./Pages/${name}`),
  title: (title) => `${title} - Blinest`,
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mixin({ methods: { route: window.route } })
      .mixin(require('./translation'))
      .mount(el)
  },
})

// PROGRESS
InertiaProgress.init()