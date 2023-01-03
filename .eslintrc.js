module.exports = {
	parser: 'vue-eslint-parser',
	extends: ['standard'],
	plugins: ['babel', 'promise', 'vue'],
	env: {
		browser: true,
		es6: true,
	},
	extends: ['eslint:recommended', 'plugin:vue/recommended'],
	parserOptions: {
		ecmaVersion: 2015,
		sourceType: 'module',
	},
	rules: {
		'key-spacing': 0,
		quotes: [2, 'double'],
		'max-len': [2, 120, 2],
		'object-curly-spacing': [2, 'always'],
		semi: [2, 'always'],
		'space-before-function-paren': ['error', 'never'],
		'comma-dangle': [2, 'always-multiline'],
		'no-return-assign': 'off',
		'no-console': 'off',
		'no-undef': 2,
	},
};
