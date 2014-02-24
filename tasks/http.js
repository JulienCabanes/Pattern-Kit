var grunt = require('grunt');
// grunt.renameTask('http', 'http_pages');
// grunt.loadNpmTasks('grunt-http');

function getHttpPathsFromJSON(jsonPath) {
  var pages = grunt.file.readJSON(jsonPath),
      paths = {};

  grunt.util._.forEach(pages, function(page, page_name) {
    paths[page_name] = {
      options: {url: '<%= pkg.http_path %>/pages/' + page_name + '.html?prod'},
      dest: '<%= pkg.dist_dir %>/pages/' + page_name + '.html'
    }
  });

  return paths;
};

module.exports = grunt.util._.extend(getHttpPathsFromJSON('data/pages.json'), {
  /** /
  dist_index: {
    options: {url: '<%= pkg.http_path %>/pages/index.html'},
    dest: '<%= pkg.dist_dir %>/index.html'
  }
  /**/
});
