import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

// AXIOS
window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// PROGRESS
InertiaProgress.init()

// APP
createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  title: title => `${title} - Blinest`,
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mixin({ methods: { route: window.route } })
      .mixin(require('./translation'))
      .mount(el)
  },
})
