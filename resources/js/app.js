import './bootstrap'
import '../css/app.css'
import { createApp, createSSRApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js'
import { route as ziggyRoute } from 'ziggy-js'
import Translation from './translation'
import { scheduleEzoicSync } from './ezoic'
import { createLocalizedRoute } from './localizedRoute'

router.on('finish', (event) => {
  const path = new URL(event.detail.visit.url, window.location.origin).pathname
  const adsDisabled = event.detail.page?.props?.donation_goal?.ads_disabled ?? false

  scheduleEzoicSync(path, { adsDisabled })
})

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

    scheduleEzoicSync(window.location.pathname, {
      adsDisabled: props.initialPage?.props?.donation_goal?.ads_disabled ?? false,
    })

    return app
  },
  progress: {
    color: '#4B5563',
  },
})
