module.exports = {
  dist: {
    options: {
      watch: true,
      sassDir: '<%= pkg.assets_dir %>/scss',
      cssDir: '<%= pkg.assets_dir %>/css'
    }
  }
}