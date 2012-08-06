task 'default', ->
  jake.exec 'jake -T',
    printStdout: true

desc 'Runs the test server.'
task 'server', [], ->
  jake.Task['watch'].invoke()
  jake.exec './scripts/web-server.js',
    printStdout: true
, async: true

desc 'Watches for changes in the build files'
task 'watch', ->
  jake.exec 'coffee --bare -w --join app/js/app.js --compile app/coffee/*.coffee',
    printStdout: false
, async: true

desc 'Builds the coffescript files'
task 'buildOld', ->
  jake.exec 'coffee -bc -o app/js/ app/coffee',
    printStdout: true

desc 'Builds the coffescript files'
task 'build', ->
  jake.exec 'coffee --bare --join app/js/app.js --compile app/coffee/*.coffee',
    printStdout: true