module.exports = {
  main: {
    options: {
      baseUrl: '<%= pkg.assets_dir %>',
      mainConfigFile: '<%= pkg.assets_dir %>/js/config.js',
      // name: 'components/almond/almond',
      name: 'js/main',
      optimize: 'none',

      include: ['js/config'],
      insertRequire: ['js/main'],
      out: '<%= pkg.dist_dir %>/<%= pkg.main %>',
      wrap: {
        start: '(function($){',
        end: 'require([\'js/main\'], function(main) {main();});})(jQuery);'
      }
    }
  }
}
