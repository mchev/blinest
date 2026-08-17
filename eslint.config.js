import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';

export default [
    {
        ignores: [
            'vendor/**',
            'public/**',
            'node_modules/**',
            'storage/**',
            'bootstrap/ssr/**',
            'resources/js/ziggy.js',
        ],
    },
    js.configs.recommended,
    ...pluginVue.configs['flat/essential'],
    {
        files: ['**/*.{js,vue}'],
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                ...globals.browser,
                ...globals.node,
            },
        },
        rules: {
            'no-unused-vars': 'off',
            'vue/no-unused-vars': ['error', { ignorePattern: '^_' }],
            'vue/multi-word-component-names': 'off',
            'vue/no-reserved-component-names': 'off',
            'vue/no-mutating-props': 'off',
            'vue/require-v-for-key': 'error',
            'vue/no-v-text-v-html-on-component': 'off',
            'vue/require-prop-type-constructor': 'off',
            'no-undef': 'off',
        },
    },
];
