module.exports = {
    root: true,
    env: {
        node: true,
    },
    extends: [
        'plugin:vue/vue3-essential',
        'eslint:recommended',
        '@vue/typescript/recommended',
        'plugin:prettier/recommended',
    ],
    parserOptions: {
        ecmaVersion: 2020,
    },
    rules: {
        'no-undef': 'off',
        'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'vue/multi-word-component-names': 'off',
        'no-unreachable': 'warn',
        'prettier/prettier': [
            'warn',
            {
                semi: false,
                trailingComma: 'all',
                singleQuote: true,
                tabWidth: 4,
                endOfLine: 'auto',
                printWidth: 120,
            },
        ],
    },
}
