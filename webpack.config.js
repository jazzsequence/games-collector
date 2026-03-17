const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

const isProd = 'production' === process.env.NODE_ENV;

module.exports = {
	entry: {
		editor: './assets/js/blocks/index.js',
		main: [ './assets/js/blocks/frontend.js', './assets/js/build/frontend.isotope.js' ],
	},
	output: {
		path: path.resolve( __dirname ),
		filename: 'assets/js/[name].js',
	},
	mode: isProd ? 'production' : 'development',
	devtool: isProd ? false : 'cheap-source-map',
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
				},
			},
			{
				test: /style\.s?css$/,
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
								style: isProd ? 'compressed' : 'expanded',
							},
						},
					},
				],
			},
			{
				test: /editor\.s?css$/,
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
								style: isProd ? 'compressed' : 'expanded',
							},
						},
					},
				],
			},
		],
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: 'assets/css/[name].css',
		} ),
	],
};
