module.exports = {
  dist: {
    options: {
      archive: '<%= pkg.dist_dir %>/dist.zip'
    },
    expand: true,
    cwd: '<%= pkg.dist_dir %>',
    src: ['**/*'],
    dest: ''
  }
}
