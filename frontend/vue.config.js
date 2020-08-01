// vue.config.js
module.exports = {
  devServer: { port: 8080, open: true },
  css: {
    loaderOptions: {
      // pass options to sass-loader
      sass: {
        // @/ is an alias to src/
        // so this assumes you have a file named `src/variables.scss`
        additionalData: `
        @import "@/assets/css/_variables.scss";
        `
      }
    }
  }
}