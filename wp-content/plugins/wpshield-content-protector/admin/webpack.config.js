const path = require( 'path' );

module.exports = {
	entry: './admin/js/admin.js',
	output: {
		filename: 'admin.min.js',
		path: path.resolve( __dirname, 'dist' ),
	},
	mode: "development",
	watchOptions: {
		aggregateTimeout: 200,
		poll: 1000,
	}
};
