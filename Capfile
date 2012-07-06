load 'deploy'
load 'config/deploy' # remove this line to skip loading any of the default tasks

namespace :deploy do
  task :restart, :roles => :web do
    # run "touch #{ current_path }/tmp/restart.txt"
    run "cd #{current_path}; jake build"
    run "if [ -f /var/run/gunicorn.pid ]; then kill `cat /var/run/gunicorn.pid`; fi;"
    run "start-stop-daemon --start --pidfile /var/run/gunicorn.pid -d #{current_path}/api --exec "\
        "/usr/local/bin/gunicorn api:app -- --daemon"
  end

  task :restart_daemons, :roles => :app do
    # sudo "monit restart all -g daemons"
  end
end