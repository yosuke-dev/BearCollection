require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueTailwind from 'vue-tailwind'

const settings = {
    TTable: {
        classes: {
            table: 'shadow min-w-full divide-y divide-gray-200',
            tbody: 'bg-white divide-y divide-gray-200',
            td: 'px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-700',
            theadTh: 'px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider'
        },
        variants: {
            thin: {
                td: 'p-1 whitespace-no-wrap text-sm leading-4 text-gray-700',
                theadTh: 'p-1 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider'
            }
        }
    },
    TRichSelect: {
        fixedClasses: {
            wrapper: 'relative',
            buttonWrapper: 'inline-block relative w-full',
            selectButton: 'w-full flex text-left justify-between items-center',
            selectButtonLabel: 'block truncate',
            selectButtonPlaceholder: 'block truncate',
            selectButtonIcon: 'fill-current flex-shrink-0 ml-1 h-4 w-4',
            selectButtonClearButton: 'flex flex-shrink-0 items-center justify-center absolute right-0 top-0 m-2 h-6 w-6',
            selectButtonClearIcon: 'fill-current h-3 w-3',
            dropdown: 'absolute w-full z-10',
            dropdownFeedback: '',
            loadingMoreResults: '',
            optionsList: 'overflow-auto',
            searchWrapper: 'inline-block w-full',
            searchBox: 'inline-block w-full',
            optgroup: '',
            option: '',
            highlightedOption: '',
            selectedOption: '',
            selectedHighlightedOption: '',
            optionContent: '',
            optionLabel: 'truncate block',
            selectedIcon: 'fill-current h-4 w-4',
            enterClass: '',
            enterActiveClass: '',
            enterToClass: '',
            leaveClass: '',
            leaveActiveClass: '',
            leaveToClass: ''
        },
        classes: {
            wrapper: '',
            buttonWrapper: '',
            selectButton: 'bg-white border rounded p-2 focus:outline-none focus:shadow-outline',
            selectButtonLabel: '',
            selectButtonPlaceholder: 'text-gray-500',
            selectButtonIcon: '',
            selectButtonClearButton: 'hover:bg-gray-200 text-gray-500 rounded',
            selectButtonClearIcon: '',
            dropdown: 'rounded bg-white shadow',
            dropdownFeedback: 'text-sm text-gray-500',
            loadingMoreResults: 'text-sm text-gray-500',
            optionsList: '',
            searchWrapper: 'bg-white p-2',
            searchBox: 'p-2 bg-gray-200 text-sm rounded border focus:outline-none focus:shadow-outline',
            optgroup: 'text-gray-500 uppercase text-xs py-1 px-2 font-semibold',
            option: '',
            highlightedOption: 'bg-gray-300',
            selectedOption: 'font-semibold bg-gray-100',
            selectedHighlightedOption: 'bg-gray-300 font-semibold',
            optionContent: 'flex justify-between items-center p-2 cursor-pointer',
            optionLabel: 'truncate block',
            selectedIcon: '',
            enterClass: '',
            enterActiveClass: 'opacity-0 transition ease-out duration-100',
            enterToClass: 'opacity-100',
            leaveClass: 'transition ease-in opacity-100',
            leaveActiveClass: '',
            leaveToClass: 'opacity-0 duration-75'
        },
        variants: {
            danger: {
                selectButton: 'border-red-500 text-red-500 bg-red-100 border rounded p-2 focus:outline-none focus:shadow-outline',
                selectButtonPlaceholder: 'text-red-400',
                selectButtonClearButton: 'hover:bg-red-200 text-red-500 rounded'
            },
            success: {
                selectButton: 'border-green-500 text-green-500 bg-green-100 border rounded p-2 focus:outline-none focus:shadow-outline',
                selectButtonPlaceholder: 'text-green-400',
                selectButtonClearButton: 'hover:bg-green-200 text-green-500 rounded'
            }
        }
        }
}

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueTailwind, settings)

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
