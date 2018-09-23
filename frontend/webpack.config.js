global.Promise = require('bluebird');

const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

const publicPath = `http://${process.env.ASSETS_HOST}/public/assets`;
const cssName = process.env.NODE_ENV === 'production' ? 'styles-[hash].css' : 'styles.css';
const jsName = process.env.NODE_ENV === 'production' ? 'bundle-[hash].js' : 'bundle.js';

const plugins = [
  new MiniCssExtractPlugin({
    // Options similar to the same options in webpackOptions.output
    // both options are optional
    filename: cssName,
    chunkFilename: '[id].css'
  }),
  new CleanWebpackPlugin(['public/assets/'], {
    root: __dirname,
    verbose: true,
    dry: false
  }),
  new webpack.DefinePlugin({
    BACKEND_URL: JSON.stringify(`${process.env.BACKEND_HOST}/files`)
  })
];

if (process.env.NODE_ENV === 'production') {
  plugins.push(new webpack.optimize.OccurrenceOrderPlugin());
}

module.exports = {
  entry: ['@babel/polyfill', './src/client.js'],
  resolve: {
    modules: ['node_modules', 'src'],
    extensions: ['.js', '.jsx']
  },
  plugins,
  output: {
    path: `${__dirname}/public/assets/`,
    filename: jsName,
    publicPath
  },
  mode: process.env.NODE_ENV === 'production' || 'development',
  module: {
    rules: [
      {
        test: /\.css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: '../'
            }
          },
          'css-loader'
        ]
      },
      {
        test: /\.jsx?$/,
        use: process.env.NODE_ENV !== 'production' ? [
          {
            loader: 'babel-loader'
          },
          {
            loader: 'eslint-loader',
            options: {
              configFile: '.eslintrc'
            }
          }
        ] : 'babel-loader',
        exclude: [/node_modules/, /public/]
      }
    ]
  },
  devtool: process.env.NODE_ENV !== 'production' ? 'cheap-eval-source-map' : false,
  devServer: {
    contentBase: `${__dirname}/public/`,
    disableHostCheck: true,
    hot: false,
    host: 'sf-assets.local',
    port: 80,
    headers: { 'Access-Control-Allow-Origin': '*' }
  }
};
