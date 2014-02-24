module.exports = {
  dist: {
    options: {
      includePaths: [
        '<%= pkg.assets_dir %>/scss/'
	  ]
	},
    files: {
      // '<%= pkg.assets_dir %>/css/screen.css': '<%= pkg.assets_dir %>/scss/screen.scss',
      '<%= pkg.assets_dir %>/css/test.css': '<%= pkg.assets_dir %>/scss/test.scss'
      // 'css/screen-ie.css': 'scss/screen-ie.scss',
      // 'css/print.css': 'scss/print.scss'
      // 'css/integration.css': 'scss/integration.scss',
      // 'css/docs.css': 'scss/docs.scss'
    }
  }
}
