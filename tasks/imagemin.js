module.exports = {
  assets: {
    options: {
      optimizationLevel: 5,
      progressive: true
    },
    files: [{
      expand: true,
      cwd: '<%= pkg.assets_dir %>/img',
      src: [
        '{,*/}*.{png,jpg,jpeg}'
      ],
      dest: '<%= pkg.assets_dir %>/img'
    }]
  },
  screenshots: {
    options: {
      optimizationLevel: 5,
      progressive: true
    },
    files: [{
      expand: true,
      cwd: '<%= pkg.tmp_dir %>/screenshots',
      src: [
        '{,*/}*.{png,jpg,jpeg}'
      ],
      dest: '<%= pkg.tmp_dir %>/screenshots',
    }]
  }
}
