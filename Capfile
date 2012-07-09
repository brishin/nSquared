load 'deploy'
load 'config/deploy' # remove this line to skip loading any of the default tasks

namespace :deploy do
  task :restart, :roles => :web do
    # run "touch #{ current_path }/tmp/restart.txt"
    run "cd #{current_path}; jake build"
    run "if [ -f #{shared_path}/gunicorn.pid ]; then kill `cat #{shared_path}/gunicorn.pid`; fi;"
    run ". #{shared_path}/venv/bin/activate;"\
        "start-stop-daemon --start --pidfile #{shared_path}/gunicorn.pid -d #{current_path}/api --exec "\
        "/usr/local/bin/gunicorn api:app -- --daemon "\
        "--access-logfile #{current_path}/logs/access.log --log-level debug "\
        "--log-file #{current_path}/logs/api.log -p #{shared_path}/gunicorn.pid "\
  end

  task :restart_daemons, :roles => :app do
    # sudo "monit restart all -g daemons"
  end
end