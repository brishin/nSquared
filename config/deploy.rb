set :application, "nSquared"
set :repository,  "git@github.com:brishin/nSquared.git"

set :scm, :git
set :user, "ubuntu"
set :use_sudo, false

set :branch, "master"
set :deploy_via, :remote_cache
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `git`, `mercurial`, `perforce`, `subversion` or `none`

server "ty", :app, :web, :db, :primary => true
set :deploy_to, "/home/ubuntu/www/nSquared"

# if you want to clean up old releases on each deploy uncomment this:
after "deploy:restart", "deploy:cleanup"
