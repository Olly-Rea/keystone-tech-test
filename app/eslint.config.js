import eslintPluginVue from 'eslint-plugin-vue';
import js from '@eslint/js';
import noRelativeImportPaths from 'eslint-plugin-no-relative-import-paths';
import ts from 'typescript-eslint';

export default ts.config(
  js.configs.recommended,
  ...ts.configs.recommended,
  ...eslintPluginVue.configs['flat/recommended'],
  {
    files: ["*.js", "src/**/*"],
    plugins: {
      noRelativeImportPaths,
    },
    languageOptions: {
      parserOptions: {
        parser: '@typescript-eslint/parser',
      },
    },
    rules: {
      semi: 'error',
      indent: [
        'error',
        2,
      ],
      'comma-dangle': [
        2,
        'always-multiline',
      ],
      'max-len': [
        'error',
        {
          code: 100,
          ignoreComments: true,
          ignoreStrings: true,
          ignoreTemplateLiterals: true,
          ignorePattern: '\\{/\\*',
        },
      ],
      'no-plusplus': 'off',
      'noRelativeImportPaths/no-relative-import-paths': [
        'warn',
        {
          allowSameFolder: false,
          rootDir: 'src',
          prefix: '@',
        },
      ],
      'no-undefined': 'error',
      'no-unused-vars': 'warn',
      'sort-imports': [
        'error',
        {
          ignoreCase: true,
          memberSyntaxSortOrder: [
            'none',
            'all',
            'multiple',
            'single',
          ],
        },
      ],
    },
    settings: {
      'import/resolver': {
        alias: {
          map: [['@', './src/']],
          extensions: [
            '.ts',
            '.vue',
          ],
        },
      },
    },
  },
);