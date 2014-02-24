module.exports = {
  assets: {
    files: [{
      expand: true,
      cwd: '<%= pkg.assets_dir %>',
      src: ['**'],
      dest: '<%= pkg.dist_dir %>/<%= pkg.assets_dir %>'
    }]
  },
  data: {
    files: [{
      expand: true,
      cwd: '<%= pkg.data_dir %>',
      src: ['**'],
      dest: '<%= pkg.dist_dir %>/<%= pkg.data_dir %>'
    }]
  },
  screenshots: {
    files: [{
      expand: true,
      cwd: '<%= pkg.tmp_dir %>',
      src: ['screenshots/**'],
      dest: '<%= pkg.dist_dir %>'
    }]
  }
}
