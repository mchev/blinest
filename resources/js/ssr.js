import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { route as ziggyRoute } from 'ziggy-js';
import Translation from './translation';
import { createLocalizedRoute } from './localizedRoute';

createServer((page) =>
    createInertiaApp({
        page,
        serverHead: true,
        render: renderToString,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                })
                .mixin(Translation);

            app.config.globalProperties.route = createLocalizedRoute(ziggyRoute);

            return app;
        },
    })
);
