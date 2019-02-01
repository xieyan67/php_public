<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'git@git.tsingzone.com:web/OnlineClassroom-CMS.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

set('ssh_multiplexing', false);
set('writable_mode', 'chmod');// 采用哪种方式控制可写权限，有4中：chown、chgrp、chmod、acl（默认方式）
set('writable_chmod_mode', '0755'); // 当使用chmod控制可写权限的时候，赋予的可写权限值
set('clear_path', []);  // 设置在代码发布的时候需要被删除的目录

// Hosts

//host('project.com')
//    ->set('deploy_path', '~/{{application}}');

$path = '/Applications/MAMP/www/DeployerTest';
host('127.0.0.1')
    ->identityFile('~/.ssh/deployerkey') // 指定公钥的位置
    ->set('deploy_path', $path) // 代码部署目录，注意：你的webserver，比如nginx，设置的root目录应该是/var/www/tb/current，
    ->stage('prod');

// Tasks
task('success', function () {
    Deployer::setDefault('terminate_message', '<info>发布成功!</info>');
})->once()->setPrivate();   // 增加once调用那么这个任务将会在本地执行，而非远端服务器，并且只执行一次

task('deploy', [    // 可以设置复合任务，第二个参数是这个复合任务包括的所有子任务，将会依次执行
    'deploy:prepare',   // 发布前准备，检查一些需要的目录是否存在，不存在将会自动创建
    'deploy:lock',  // 生成锁文件，避免同时在一台服务器上执行两个发布流程，造成状态混乱
    'deploy:release',   // 创建代码存放目录
    'deploy:update_code',   // 更新代码，通常是git，你也可以重写这个task，使用upload方法，采用sftp方式上传
    'deploy:shared',    // 处理共享文件或目录
    'deploy:writable',  // 设置目录可写权限
    'deploy:vendors',   // 根据composer配置，安装依赖
    'deploy:clear_paths',   // 根据设置的clear_path参数，执行删除操作
    'deploy:symlink',   // 设置符号连接到最新更新的代码，线上此时访问的就是本次发布的代码了
    'deploy:unlock',     // 删除锁文件，以便下次发布
    'cleanup',  // 根据keep_releases参数，清楚过老的版本，释放服务器磁盘空间
    'success'   // 执行成功任务，上面自己定义的，一般用来做提示
]);

//测试执行php文件
task('test',function (){
//    writeln(runLocally('git config --get user.name'));//获取当前用户

//    writeln(run('mkdir {{deploy_path}}/tests3'));//测试创建在目标主机项目中创建个文件夹
//    writeln(run('touch {{deploy_path}}/tests3/test.php'));//测试创建在目标主机项目中创建个文件
//    writeln(run('touch {{deploy_path}}/tests3/update'));//测试创建在目标主机项目中创建个文件
    writeln(run('php -f {{deploy_path}}/tests3/test.php'));//测试执行该文件
//    writeln(run('ls -l {{deploy_path}}/tests3'));
})->onStage('prod');

//测试
task('test2',function (){
//    writeln(run('cp {{deploy_path}}/shared/.env {{deploy_path}}/tests3/.env2'));
    $path = run('readlink {{deploy_path}}/current');
    run("echo $path");
    writeln();
});

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
