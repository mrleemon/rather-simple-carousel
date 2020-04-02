// Load default configuration
const defaultConfig = require('./node_modules/@wordpress/scripts/config/webpack.config.js');
// To work with file and directory paths
const path = require('path');
// For extracting CSS (and SASS) into separate files
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// For preventing the creation of the useless editor.js and style.js files
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');

const production = process.env.NODE_ENV === '';

// Start of main webpack config
const config = {
    ...defaultConfig,
    // Go through each entry point
    entry: {
        index: path.resolve(process.cwd(), 'src', 'index.js'),
        style: path.resolve(process.cwd(), 'src', 'style.scss'),
        editor: path.resolve(process.cwd(), 'src', 'editor.scss'),
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                editor: {
                    name: 'editor',
                    test: /editor\.(sc|sa|c)ss$/,
                    chunks: 'all',
                    enforce: true,
                },
                style: {
                    name: 'style',
                    test: /style\.(sc|sa|c)ss$/,
                    chunks: 'all',
                    enforce: true,
                },
                default: false,
            },
        },
    },
    module: {
        ...defaultConfig.module,
        rules: [
            ...defaultConfig.module.rules,
            {
                // Setup SASS (and CSS) to be extracted
                test: /\.(sc|sa|c)ss$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: !production,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: [require('autoprefixer')]
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: !production,
                        },
                    },
                ],
            },
        ]
    },
    plugins: [
        ...defaultConfig.plugins,
        new MiniCssExtractPlugin({
            filename: '[name].css',
        }),
        new IgnoreEmitPlugin(['editor.js', 'style.js', 'index.asset.php', 'editor.asset.php', 'style.asset.php']),
    ],
};

module.exports = config;