<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'my_project');
// 指定你的代码所在的服务器 SSH 地址
set('repository', 'git@git.tsingzone.com:server/YiQiSchool.git');
//为git clone命令分配TTY 。false默认。这允许您输入密钥的密码或添加主机known_hosts
set('git_tty', true);
//共享文件列表 这里面列出的文件会被移动到项目根目录的shared目录下，并做软链
set('shared_files', []);
//共享目录 同上
set('shared_dirs', []);
//可写目录 规定那些目录是需要可以被web server写入的
set('writable_dirs', []);
//是否开启ssh通道复用技术（开启可以降低服务器和本地负载，并提升速度）
set('ssh_multiplexing', false);
//采用哪种方式控制可写权限，有4中：chown、chgrp、chmod、acl（默认方式）
set('writable_mode', 'chmod');
//当使用chmod控制可写权限的时候，赋予的可写权限值
set('writable_chmod_mode', '0755');
//设置在代码发布的时候需要被删除的目录
set('clear_path', []);

//set('keep_releases', -1);
set('app_path','/Applications/MAMP/www');

$path = '/Applications/MAMP/www/DeployerTest2';
// 这里填写目标服务器的 IP 或者域名
host('127.0.0.1')
//    ->user('username') // 这里填写 登录主机的用户名
    // 指定公钥的位置 可以免输入密码操作
    ->identityFile('~/.ssh/deployerkey')
    // 代码部署目录，注意：你的webserver，比如nginx，设置的root目录应该是/var/www/tb/current，
    ->set('deploy_path', $path)
    //设置阶段名称 默认时prod 注：若设置则在执行dep命令时需要标明运行哪个阶段
    ->stage('prod');

// Tasks
task('success', function () {
    writeln('发布成功!');
})->onStage('prod');//onStage 按阶段筛选主机

desc('Deploy your project');
task('deploy', [
    //发布前准备，检查一些需要的目录是否存在，不存在将会自动创建
    'deploy:prepare',
    //生成锁文件，避免同时在一台服务器上执行两个发布流程，造成状态混乱
    'deploy:lock',
    // 创建代码存放目录
    'deploy:release',
    // 更新代码，通常是git，你也可以重写这个task，使用upload方法，采用sftp方式上传
    'deploy:update_code',
    // 处理共享文件或目录
    'deploy:shared',
    // 设置目录可写权限
    'deploy:writable',
    // 根据composer配置，安装依赖
//    'deploy:vendors',
    // 根据设置的clear_path参数，执行删除操作
    'deploy:clear_paths',
    // 设置符号连接到最新更新的代码，线上此时访问的就是本次发布的代码了
//    'deploy:symlink',
    // 删除锁文件，以便下次发布
    'deploy:unlock',
    // 根据keep_releases参数，清楚过老的版本，释放服务器磁盘空间
//    'cleanup',
    // 执行成功任务，上面自己定义的，一般用来做提示
    'success'
]);

//第一个任务 创建项目存放目录
task('test1',function (){
    writeln(run("mkdir {{deploy_path}}/test2"));
});

//第二个任务 创建项目存放目录
task('test2',function (){
//    writeln(runLocally('git config --get user.name'));//获取当前用户
    writeln(run("touch {{deploy_path}}/test2/test.php"));

});
// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// 执行数据库迁移，建议删掉，迁移虽好，但毕竟高风险，只推荐用于开发环境。
before('deploy:symlink', 'database:migrate');
