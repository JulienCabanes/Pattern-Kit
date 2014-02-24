var grunt = require('grunt');

// Génère l'array des fichiers à resizer
function getPathsForCrop(src, dest) {
  dest = dest || src;
  var files = grunt.file.expand(src + '{,*/}*.png'),
      list = [];
  files.forEach(function(file) {
    var item = {};
    item[dest + file.replace(src, '')] = file;
    list.push(item);
  });

  return list;
}

module.exports = {
  screenshots: {
    options: {
      width: 600,
      crop: false
    },
    files: getPathsForCrop('<%= pkg.tmp_dir %>/screenshots/desktop', '<%= pkg.tmp_dir %>/screenshots/desktop')
  }
}
