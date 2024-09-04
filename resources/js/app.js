import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import AppLayout from './Layouts/AppLayout.vue';
import Icon from './Components/Icons/Icon.vue';
import formatCurrency from './utils/currencyFormatter';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page =  resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
        page.then((module) => {
            module.default.layout = module.default.layout || AppLayout; // Assign the layout
        });

        return page; // Return the page component
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mixin({
                methods: {
                  currencyFormat(amount) {
                    return formatCurrency(amount); // Use the utility function
                  },
                },
              })
            .component('Icon', Icon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
