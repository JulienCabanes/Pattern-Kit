module.exports = {
  dist: {
    options: {
      force: true
    },
    src: ['<%= pkg.dist_dir %>']
  },
  dist_zip: {
    options: {
      force: true
    },
    src: ['<%= pkg.dist_dir %>/dist.zip']
  }
}
