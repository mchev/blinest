import './bootstrap'
import '../css/app.css'
import { createApp, createSSRApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js'
import { route as ziggyRoute } from 'ziggy-js'
import Translation from './translation'
import { createLocalizedRoute } from './localizedRoute'

createInertiaApp({
  serverHead: true,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const createVueApp = el.hasAttribute('data-server-rendered') ? createSSRApp : createApp

    const app = createVueApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mixin(Translation)

    app.config.globalProperties.route = createLocalizedRoute(ziggyRoute)

    app.mount(el)

    return app
  },
  progress: {
    color: '#4B5563',
  },
})
