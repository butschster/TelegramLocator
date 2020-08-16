<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'TelegramLocator');
set('repository', 'git@github.com:butschster/TelegramLocator.git');
set('git_tty', false);
set('keep_releases', 5);

add('shared_files', []);
add('shared_dirs', []);

set('release_use_sudo', true);
set('allow_anonymous_stats', false);

host('142.93.171.85')
    ->port(22)
    ->user('root')
    ->set('branch', 'master')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www')
    ->addSshOption('StrictHostKeyChecking', 'no');

task('php:reload', function () {
    run('sudo systemctl restart php7.4-fpm.service');
});

task('nginx:reload', function () {
    run('sudo systemctl restart nginx.service');
});

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'artisan:migrate');

after('deploy:symlink', 'php:reload');
after('deploy:symlink', 'nginx:reload');
