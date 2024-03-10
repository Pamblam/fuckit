const path = require('path');
const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
	devtool: 'source-map',
	mode: "production", // "production" or "development"
	entry: path.resolve(__dirname, 'src', 'main.jsx'),
	output: {
		path: path.resolve(__dirname, 'public', 'assets', 'js'),
		filename: 'main.js'
	},
	module: {
		rules: [{
			test: /\.css$/i,
			use: ['style-loader', 'css-loader'],
		},{
			test: /\.jsx$/,
			exclude: [/node_modules/],
			use: {
				loader: "babel-loader",
				options: {
					presets: ['@babel/preset-env', ['@babel/preset-react', { "runtime": "automatic" }]]
				}
			}
		},{
			test: /\.(jpg|png)$/,
			use: {
				loader: 'url-loader',
			}
		}]
	},
	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({
				extractComments: false,
				terserOptions: {
					format: {
						comments: false,
					},
				},
			}),
		],
	}
};