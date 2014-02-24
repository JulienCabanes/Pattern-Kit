module.exports = {
  options: {
    banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> CANT TOUCH THIS */\n',
    report: false
  },
  dist: {
    files: {
      '<%= pkg.public_dir %>/dist/assets/css/screen.css': [
        '<%= pkg.public_dir %>/dist/assets/css/screen.css'
      ]
      /** /
      ,
      '<%= pkg.public_dir %>/dist/assets/css/screen-ie.css': [
        '<%= pkg.public_dir %>/dist/assets/css/screen-ie.css'
      ]
      /**/
    }
  }
 }
