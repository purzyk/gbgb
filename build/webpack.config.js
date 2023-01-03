const webpack = require("webpack");
const path = require("path");
const glob = require("glob");
const VueLoaderPlugin = require("vue-loader/lib/plugin");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const PurgecssPlugin = require("purgecss-webpack-plugin");
const ManifestPlugin = require("webpack-manifest-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const config = require("./config");
const StyleLintPlugin = require("stylelint-webpack-plugin");

const inProduction = process.env.NODE_ENV === "production";
const styleHash = inProduction ? "contenthash" : "hash";
const scriptHash = inProduction ? "chunkhash" : "hash";

// LOADER HELPERS
const extractCss = {
  loader: MiniCssExtractPlugin.loader,
  options: {
    publicPath: `${config.assetsPath}static/css/`,
  },
};

const cssLoader = {
  loader: "css-loader",
  options: { minimize: inProduction },
};

module.exports = {
  entry: {
    app: glob.sync("./resources/assets/+(s[ac]ss|js)/main.+(s[ac]ss|js)"),
    home: glob.sync("./resources/assets/+(s[ac]ss|js)/home.+(s[ac]ss|js)"),
    login: glob.sync("./resources/assets/sass/wordpress/wp-login.scss"),
    admin: glob.sync("./resources/assets/sass/wordpress/wp-admin.scss"),
    vendor: ["jquery", "vue"],
  },
  output: {
    path: path.resolve(__dirname, "../static/"),
    filename: `js/[name].[${scriptHash}].js`,
  },

  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
      {
        test: /\.js$/,
        loader: ["babel-loader", "eslint-loader"],
      },
      {
        test: /\.css$/,
        use: ["vue-style-loader", extractCss, cssLoader],
      },
      {
        test: /\.scss$/,
        use: [
          "vue-style-loader",
          extractCss,
          cssLoader,
          {
            loader: "sass-loader",
            options: {
              includePaths: [
                path.resolve(__dirname, "../resources/assets/sass"),
              ],
              data: '@import "common/variables";',
            },
          },
        ],
      },
      {
        test: /\.sass$/,
        use: [
          "vue-style-loader",
          extractCss,
          cssLoader,
          {
            loader: "sass-loader",
            options: {
              indentedSyntax: true,
              includePaths: [
                path.resolve(__dirname, "../resources/assets/sass"),
              ],
              data: '@import "common/variables";',
            },
          },
        ],
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].[ext]",
              outputPath: "images/",
              publicPath: `${config.assetsPath}static/images/`,
            },
          },
          "image-webpack-loader",
        ],
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: "url-loader",
        options: {
          limit: 10000,
          name: "[name].[hash:7].[ext]",
          outputPath: "fonts/",
          publicPath: `${config.assetsPath}static/fonts/`,
        },
      },
    ],
  },

  optimization: {
    splitChunks: {
      cacheGroups: {
        vendor: {
          chunks: "all",
          name: "vendor",
          test: "vendor",
          enforce: true,
        },
      },
    },
  },

  resolve: {
    alias: {
      vue$: "vue/dist/vue.esm.js",
      images: path.join(__dirname, "../resources/assets/images"),
    },
    extensions: ["*", ".js", ".vue", ".json"],
  },

  node: {
    fs: "empty",
  },

  plugins: [
    new VueLoaderPlugin(),

    new StyleLintPlugin({ failOnError: !!inProduction }),

    // new CleanWebpackPlugin(['static/css/*', 'static/js/*'], {
    //   root: path.join(__dirname, '../'),
    //   watch: true,
    // }),

    new MiniCssExtractPlugin({
      filename: `css/[name].[${styleHash}].css`,
    }),

    //   new PurgecssPlugin({
    //     paths: () =>
    //       glob.sync(path.join(__dirname, '../resources/**/*'), { nodir: true }),
    //     only: ['app'],
    //   }),

    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      "window.jQuery": "jquery",
    }),

    new ManifestPlugin(),

    new BrowserSyncPlugin({
      host: "localhost",
      port: 3000,
      proxy: config.devUrl, // YOUR DEV-SERVER URL
      files: ["./*.php", "./resources/views/**/*.twig", "./static/*.*"],
    }),
  ],
};
