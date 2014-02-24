module.export = {
  img: {
    files: [{
      expand: true,
      cwd: '<%= pkg.assets_dir %>/img/ups',
      src: ['**/*.svg'],
      dest: '<%= pkg.assets_dir %>/img/ups'
    }, {
      expand: true,
      cwd: '<%= pkg.assets_dir %>/fonts',
      src: ['**/*.svg'],
      dest: '<%= pkg.assets_dir %>/fonts'
    }]
  }
}