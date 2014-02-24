module.exports = {
  watch: {
    options: {
      logConcurrentOutput: true
    },
    tasks: ['compass:dist', 'watch:css', 'watch:js']
  },
  fast_watch: {
    options: {
      logConcurrentOutput: true
    },
    tasks: ['watch:sass', 'watch:css', 'watch:js']
  },
  sync_watch: {
    options: {
      logConcurrentOutput: true
    },
    tasks: ['compass:dist', 'browser_sync:dev2', 'watch:css']
  }
}
