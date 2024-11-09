export default [
  {
    files: ['**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        window: 'readonly',
        document: 'readonly',
      },
    },
    rules: {
      semi: ['error', 'always'],
      quotes: ['error', 'single'],
      'no-console': 'warn',
      indent: ['error', 2],
      'no-unused-vars': ['warn'],
    },
    ignores: ['node_modules', 'dist', 'eslint.config.js', 'src/main.js'],
  },
];
