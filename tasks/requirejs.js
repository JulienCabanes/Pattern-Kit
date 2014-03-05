module.exports = {
  main: {
    options: {
      baseUrl: '<%= pkg.assets_dir %>',
      mainConfigFile: '<%= pkg.assets_dir %>/js/config.js',
      // name: 'components/almond/almond',
      // optimize: 'uglify',
      // appDir: '<%= pkg.dist_dir %>/<%= pkg.assets_dir %>',
      
      
      name: 'js/main',
      include: [
        'components/requirejs/require',
        'js/config'
      ],
      insertRequire: ['js/main'],
      out: '<%= pkg.dist_dir %>/<%= pkg.main %>',

      wrap: {
        start: '(function($){',
        end: 'require([\'js/main\'], function(main) {main();});})(jQuery);'
      }
    }
  }
}
