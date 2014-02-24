module.exports = {
  dev: {
    bsFiles: {
      src : '<%= pkg.assets_dir %>/css/*.css'
    },
    options: {
      host : "localhost",
      ghostMode: {
        clicks: true,
        scroll: true,
        links: true,
        forms: true
      }
    }
  },
  dev2: {
    options: {
      proxy: {
        host : "localhost"  
      },
      ghostMode: {
        clicks: true,
        scroll: true,
        links: true,
        forms: true
      }
    }
  }
}
