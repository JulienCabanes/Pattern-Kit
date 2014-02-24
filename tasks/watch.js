module.exports = {
  sass: {
    files: ['<%= pkg.assets_dir %>/scss/*.scss'],
    tasks: ['sass:dist']
  },
  css: {
    options: {
      livereload: true
    },
    files: ['<%= pkg.assets_dir %>/css/*.css'],
    tasks: ['notify:css']
  },
  js: {
    files: ['<%= pkg.assets_dir %>/js/main.js'],
    tasks: ['jshint']
  }
}
