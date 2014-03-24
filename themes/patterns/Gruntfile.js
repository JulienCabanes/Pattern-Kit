module.exports = function(grunt) {

  function loadConfig(cwd, path) {
    var glob = require('glob');
    var object = {};
    var key;
    path = path || '*';

    glob.sync(path, {cwd: cwd}).forEach(function(option) {
      key = option.replace(/\.js$/,'');
      key = key.split('/').pop();
      object[key] = require(cwd + option);
    });

    return object;
  };

  
  // var confTest = grunt.util._.extend(loadConfig('./tasks/test/'), loadConfig('./views/', '**/tasks/*'));
  // console.log(JSON.stringify(confTest));
  // return;
  

  // Load every grunt dependencies
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  var config = {
    pkg: grunt.file.readJSON('package.json'),
    env: process.env
  };

  grunt.util._.extend(config, loadConfig('./tasks/options/'));

  grunt.initConfig(config);

  grunt.registerTask('simple-watch', [
    'concurrent:watch'
  ]);

  grunt.registerTask('fast-watch', [
    'concurrent:fast_watch'
  ]);

  grunt.registerTask('sync-watch', [
    'concurrent:sync_watch'
  ]);

  grunt.registerTask('build-html', [
    'http-pages',
    'http-documentation',
    'htmlhint:pages'
  ]);

  grunt.registerTask('build-assets', [
    'imagemin:assets',
    'copy:assets',
    'cssmin:dist',
    'requirejs:main'
  ]);

  grunt.registerTask('build-pages', [
    'build-html',
    'build-assets'
  ]);

  grunt.registerTask('build-screenshots', [
    'autoshot',
    'image_resize:screenshots',
    'imagemin:screenshots',
    'copy:screenshots',
    'notify:build'
  ]);

  grunt.registerTask('build-zip', [
    'clean:dist_zip',
    'compress:dist'
  ]);

  grunt.registerTask('build', [
    'clean:dist',
    'build-pages',
    'build-screenshots',
    'build-zip',
    'notify:build'
  ]);

  grunt.registerTask('build-patch', [
    'build-pages',
    'build-zip',
    'notify:build'
  ]);

  grunt.registerTask('deploy', [
    'ftp-deploy:dev',
    'notify:deploy'
  ]);

  grunt.registerTask('deploy-patch', [
    'ftp-deploy:patch_dev',
    'notify:deploy'
  ]);

  grunt.registerTask('build-and-deploy', [
    'build',
    'deploy'
  ]);

  grunt.registerTask('default', [
    'simple-watch'
  ]);

};
