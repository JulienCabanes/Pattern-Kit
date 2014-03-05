module.exports = {
  options: {
    banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> CANT TOUCH THIS */\n',
    report: false
  },
  dist: {
    expand: true,
    cwd: '<%= pkg.dist_dir %>/<%= pkg.assets_dir %>/css/',
    dest: '<%= pkg.dist_dir %>/<%= pkg.assets_dir %>/css/',
    src: ['*.css']
  }
}
