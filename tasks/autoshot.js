var grunt = require('grunt');

function getScreenshotsPaths(jsonPath) {

  var pages = grunt.file.readJSON(grunt.template.process(jsonPath)),
      paths = [];

  grunt.util._.forEach(pages, function(page, page_name) {
    paths.push({
      src: '<%= pkg.http_path %>/pages/' + page_name + '.html',
      dest: page_name + '.png',
      delay: 2000
    });
  });

  return paths;
};

module.exports = {
  desktop: {
    options: {
      // necessary config
      path: '<%= pkg.tmp_dir %>/screenshots/desktop/',
      // optional config, must set either remote or local
      remote: {
        files: getScreenshotsPaths('data/pages.json')
        // files: [{"src": "http://upsud/page.homeen.html", "dest": "homeen.png"}]
      },
      local: false,
      viewport: ['1400x4000'] 
    }
  },
  mobile: {
    options: {
      // necessary config
      path: '<%= pkg.tmp_dir %>/screenshots/mobile/',
      // optional config, must set either remote or local
      remote: {
        files: getScreenshotsPaths('data/pages.json')
      },
      local: false,
      viewport: ['320x4000'] 
    }
  }
}
