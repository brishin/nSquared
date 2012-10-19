load 'deploy'
load 'config/deploy' # remove this line to skip loading any of the default tasks

namespace :deploy do
  task :restart, :roles => :web do
    # run "touch #{ current_path }/tmp/restart.txt"
    run "cd #{current_path}; coffee --bare --join app/js/app.js --compile app/coffee/*.coffee"
    run "if [ -f #{shared_path}/gunicorn.pid ]; then kill `cat #{shared_path}/gunicorn.pid`; fi;"
    run "if [ -f #{shared_path}/color-gunicorn.pid ]; then kill `cat #{shared_path}/color-gunicorn.pid`; fi;"
    run "if [ -f #{shared_path}/worker.pid ]; then kill `cat #{shared_path}/worker.pid`; rm #{shared_path}/worker.pid; fi;"
    # ". #{shared_path}/venv/bin/activate;"\
    # "start-stop-daemon --start --pidfile #{shared_path}/gunicorn.pid -d #{current_path}/api --exec "\
    run "cd #{current_path}/api;"\
        "/usr/local/bin/gunicorn api:app --daemon "\
        "--access-logfile #{current_path}/logs/access.log --log-level debug "\
        "--log-file #{current_path}/logs/api.log -p #{shared_path}/gunicorn.pid "\
        "--workers 8 "
    run "cd #{current_path}/api/rainbowss;"\
        "/usr/local/bin/gunicorn color_api:app --daemon "\
        "--access-logfile #{current_path}/logs/color-access.log --log-level debug "\
        "--log-file #{current_path}/logs/color-api.log -p #{shared_path}/color-gunicorn.pid "\
        "--workers 1 --bind 0.0.0.0:9051"
    run "cd #{current_path}/app/partials;"\
        "python #{current_path}/api/generate_json.py"
    run "cd #{current_path}/api/rainbowss/;"\
        "supervisord"
  end

  task :restart_daemons, :roles => :app do
    # sudo "monit restart all -g daemons"
  end
end