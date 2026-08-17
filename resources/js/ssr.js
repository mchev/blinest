import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { route as ziggyRoute } from 'ziggy-js';
import Translation from './translation';

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
