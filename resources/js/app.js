import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import Translation from './translation';
import { scheduleEzoicSync } from './ezoic';

const appName = import.meta.env.VITE_APP_NAME || 'Blinest Music Quiz';

router.on('finish', (event) => {
    const path = new URL(event.detail.visit.url, window.location.origin).pathname;

    scheduleEzoicSync(path);
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue'),
    ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mixin(Translation)
            .mount(el);

        scheduleEzoicSync(window.location.pathname);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});