const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const TerserPlugin = require( 'terser-webpack-plugin' );

const isProd = 'production' === process.env.NODE_ENV;

/**
 * Shared module rules for SCSS → CSS compilation (always expanded).
 * Minification is handled separately via CssMinimizerPlugin.
 */
const scssRule = ( testPattern ) => ( {
	test: testPattern,
	use: [
		MiniCssExtractPlugin.loader,
		'css-loader',
		{
			loader: 'postcss-loader',
			options: {
				postcssOptions: {
					plugins: [ 'autoprefixer' ],
				},
			},
		},
		{
			loader: 'sass-loader',
			options: {
				sassOptions: {
					style: 'expanded',
				},
			},
		},
	],
} );

const sharedRules = [
	{
		test: /\.js$/,
		exclude: /(node_modules|bower_components)/,
		use: { loader: 'babel-loader' },
	},
	scssRule( /style\.s?css$/ ),
	scssRule( /editor\.s?css$/ ),
];

/**
 * Config 1: readable (unminified) assets.
 * Outputs main.js, editor.js, main.css, editor.css.
 */
const readableConfig = {
	entry: {
		editor: './assets/js/blocks/index.js',
		main: [ './assets/js/blocks/frontend.js', './assets/js/build/frontend.isotope.js' ],
	},
	output: {
		path: path.resolve( __dirname ),
		filename: 'assets/js/[name].js',
	},
	mode: 'development',
	devtool: false,
	optimization: { minimize: false },
	module: { rules: sharedRules },
	plugins: [
		new MiniCssExtractPlugin( {
			filename: 'assets/css/[name].css',
		} ),
	],
};

/**
 * Config 2: minified assets (only in production).
 * Outputs main.min.js, editor.min.js, main.min.css, editor.min.css.
 */
const minifiedConfig = {
	entry: {
		editor: './assets/js/blocks/index.js',
		main: [ './assets/js/blocks/frontend.js', './assets/js/build/frontend.isotope.js' ],
	},
	output: {
		path: path.resolve( __dirname ),
		filename: 'assets/js/[name].min.js',
	},
	mode: 'production',
	devtool: false,
	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin( { extractComments: false } ),
			new CssMinimizerPlugin(),
		],
	},
	module: { rules: sharedRules },
	plugins: [
		new MiniCssExtractPlugin( {
			filename: 'assets/css/[name].min.css',
		} ),
	],
};

module.exports = isProd ? [ readableConfig, minifiedConfig ] : readableConfig;
