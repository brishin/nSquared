task 'default', ->
  jake.exec 'jake -T',
    printStdout: true


desc 'Runs the test server.'
task 'server', ['build'], ->
  jake.exec './scripts/web-server.js',
    printStdout: true
, async: true

desc 'Builds the coffescript files'
task 'build', ->
  jake.exec 'coffee -bc -o app/js/ app/coffee',
    printStdout: true