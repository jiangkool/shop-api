<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@github.com:jiangkool/shop-api.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('project.com')
    ->set('deploy_path', '~/{{application}}');    

host('ip1')
	->user('root') // 使用 root 账号登录
	->identityFile('~/.ssh/api.pem') // 指定登录密钥文件路径
	->become('www-data') // 以 www-data 身份执行命令
	->set('deploy_path', '/var/www/api-deployer'); // 指定部署目录

host('ip2')
    ->user('root')
    ->identityFile('~/.ssh/api.pem')
    ->become('www-data')
    ->set('deploy_path', '/var/www/api');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// 定义一个上传 .env 文件的任务
desc('Upload .env file');
task('env:upload', function() {
    // 将本地的 .env 文件上传到代码目录的 .env
    upload('.env', '{{release_path}}/.env');

});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('artisan:config:cache', 'artisan:route:cache');

after('deploy:update_code', 'artisan:migrate');