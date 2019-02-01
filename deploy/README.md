Deployer文档

一．Deployer介绍说明
Deployer 是一个基于 SSH 协议的无侵入 web 项目部署工具，因为它不需要你在目标服务器上装什么服务之类的东西即可使用，它只需要在你的开发机，或者你的笔记本，就是发起部署动作的一方安装即可。
Deployer 安装在本地，它的原理就是通过 SSH协议登录到服务器 web server 上执行一系列我们预定的操作，去创建目录，移动文件，执行指定的动作来完成项目的部署。其中包含从代码库 Git Server 拉取我们的项目代码部署到 web 服务器指定的目录完成部署。

二．使用 deployer 的前提条件
	1．本地机器（也就是你执行 dep 命令时所在的机器）能够 SSH 连接到目标机器（代码要部署到的机器，不管是在线的云主机还是局域网中的虚拟机）
	2. 有登录目标机器并调整一些设置的权限，或者能让负责人协助调整。
	3. 目标主机有拉取项目仓库的权限。

三．安装Deployer
	1．打开终端运行以下命令
		curl -LO https://deployer.org/deployer.phar
mv deployer.phar /usr/local/bin/dep
chmod +x /usr/local/bin/dep

 2．通过composer安装(未测试)
		composer require deployer/deployer –dev
		使用：php vendor/bin/dep
		
四. 服务器免密码登录 deployer
在本地（或者开发机）执行部署任务时我们不想每次输入密码，所以我们需要将 deployer 用户设置 SSH 免密码登录
1.	在本机生成 deployer 专用密钥，然后拷贝公钥：
ssh-keygen -t rsa -b 4096 -f  ~/.ssh/deployerkey
2.	然后将公钥保存到目标服务器（注意，这一步还是在本机操作）
ssh-copy-id -i  ~/.ssh/deployerkey.pub 用户名@主机IP
3.	然后你应该就可以直接以 deployer 用户免密码登录到服务器了，测试方式
ssh 用户名@主机IP -i ~/.ssh/deployerkey
	
五. Deployer配置与使用
安装完成后，切换到自己的项目目录，执行dep init，按照自己项目使用的框架选择生成的部署模板

如果你的框架未使用上面列出的任何一个框架，则选择0，然后回车，就会生成通用的发布模板，执行完这一步应该会在你的项目根目录生成一个deploy.php文件，你所需要的做的一切就是编辑这个脚本，填写一些自己的服务器和项目配置，然后定制一些task。
	注：set('allow_anonymous_stats',false);在执行 init 的时候，deployer 会询问你是否允许发送统计信息，如果允许，则会像https://deployer.org/api/stats 这个地址发送你的 php 版本，系统等信息。可以通过上文的设置禁用统计
	
deployer.php文件部分配置参数说明（详见官方文档）

host主机配置说明，可以配置多个host主机链接（详见官方文档）

部署deploy任务说明（详见deploy.php的task配置）

注：deployer中的任务参数根据需求配置
若想在部署项目前先执行一些任务，可以使用deployer自带的before()，也可以在deploy的任务中添加，在操作的任务前面加上要先执行的任务名称。
	
配置完成后在项目终端执行  dep deploy prod–vvv 
在 deploy 命令后加上 -vvv 选项可以输出详细错误信息，方便调试。

六. 关于 Deployer 部署结构
其中，.dep 为 Deployer 的一些版本信息
@current -> releases - 它是指向一个具体的版本的软链接，你的 nginx 配置中 root 应该指向它，比如 laravel 项目的话 root 就指向：/var/www/demo-app/current/public
releases - 部署的历史版本文件夹，里面可能有很多个最近部署的版本，可以根据你的配置来设置保留多少个版本，建议 5 个。保留版本可以让我们在上线出问题时使用 dep rollback 快速回滚项目到上一个版本。
shared - 共享文件夹，它的作用就是存储我们项目中版本间共享的文件，比如 Laravel 项目的 .env 文件，storage 目录，或者你项目的上传文件夹，它会以软链接的形式链接到当前版本中。

