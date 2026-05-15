const path = require( 'path' );

module.exports = {
	entry: './src/Features/assets/js/index.js',
	output: {
		filename: 'index.min.js',
		path: path.resolve( __dirname, './assets/js' ),
	},
	mode: "development",
	watchOptions: {
		aggregateTimeout: 200,
		poll: 1000,
	},
	externals:{
		fs:    "commonjs fs",
		path:  "commonjs path"
	},
	target: "node"
};
