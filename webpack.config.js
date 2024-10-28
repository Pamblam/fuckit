const path = require('path');
const fs = require('fs');
const TerserPlugin = require("terser-webpack-plugin");

let theme = null;
try{
	let config_path = path.resolve(__dirname, 'config', 'app.json');
	let config = JSON.parse(fs.readFileSync(config_path, 'utf8'));
	if(config?.theme && fs.existsSync(path.resolve(__dirname, 'src', 'themes', config.theme, 'main.jsx'))){
		theme = config?.theme;
	}
}catch(e){}

let entry = theme ? 
	path.resolve(__dirname, 'src', 'themes', theme, 'main.jsx') : 
	path.resolve(__dirname, 'src', 'core', 'main.jsx') ;

module.exports = {
	devtool: 'source-map',
	mode: "production", // "production" or "development"
	entry: entry,
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