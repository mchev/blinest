import './bootstrap';
import '../css/app.css';
import { createApp, createSSRApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { route as ziggyRoute } from 'ziggy-js';
import Translation from './translation';
import { scheduleEzoicSync } from './ezoic';

function createLocalizedRoute(route) {
    return function localizedRoute(name, params, absolute, config) {
        const page = this?.$page ?? this?.page;
        const locale = page?.props?.locale ?? 'fr';
        const ziggy = config ?? page?.props?.ziggy;
        const localizedName = locale && locale !== 'fr' ? `${locale}.${name}` : name;

        try {
            return route(localizedName, params, absolute, ziggy);
        } catch {
            return route(name, params, absolute, ziggy);
        }
    };
}

router.on('finish', (event) => {
    const path = new URL(event.detail.visit.url, window.location.origin).pathname;

    scheduleEzoicSync(path);
});

createInertiaApp({
    serverHead: true,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue'),
    ),
    setup({ el, App, props, plugin }) {
        const createVueApp = el.hasAttribute('data-server-rendered') ? createSSRApp : createApp;

        const app = createVueApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mixin(Translation);

        app.config.globalProperties.route = createLocalizedRoute(ziggyRoute);

        app.mount(el);

        scheduleEzoicSync(window.location.pathname);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
