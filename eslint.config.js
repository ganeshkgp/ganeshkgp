import js from '@eslint/js';
import prettier from 'eslint-config-prettier/flat';
import importPlugin from 'eslint-plugin-import';
import vue from 'eslint-plugin-vue';

export default [
    js.configs.recommended,
    ...vue.configs['flat/essential'],
    {
        plugins: {
            import: importPlugin,
        },
        settings: {
            'import/resolver': {
                node: true,
            },
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'import/order': [
                'error',
                {
                    groups: ['builtin', 'external', 'internal', 'parent', 'sibling', 'index'],
                    alphabetize: {
                        order: 'asc',
                        caseInsensitive: true,
                    },
                },
            ],
            curly: ['error', 'all'],
        },
    },
    {
        ignores: [
            'vendor',
            'node_modules',
            'public',
            'bootstrap/ssr',
        ],
    },
    prettier,
];
