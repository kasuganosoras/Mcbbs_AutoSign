# Mcbbs AutoSignin
MCBBS 我的世界中文论坛自动签到工具

MCBBS 最近搞了个签到...闲着没事做了这个工具，可以帮你在每天自动签到

使用 PHP 开发，需要 PHP 5 以上才能运行。

安装 PHP 最简单的方法
````bash
# CentOS / RedHat / Fedora 安装方法
yum install php -y
# Windows 32 位
https://windows.php.net/downloads/releases/php-7.2.11-Win32-VC15-x86.zip
# Windows 64 位
https://windows.php.net/downloads/releases/php-7.2.11-Win32-VC15-x64.zip
# 其他系统自行百度吧...尽我所能教这么多了
````
建议使用 Linux 服务器运行，不需要很高配置，1 核 1G 足够。

### 食用方法
将项目 clone 到本地（你也可以使用网页上的 Download 功能）
````bash
git clone https://github.com/kasuganosoras/Mcbbs_AutoSignin/
cd Mcbbs_AutoSignin/
````
输入以下命令启动并生成 cookie.txt
````bash
php main.php
````
按下 Ctrl + C 掐掉进程。

然后打开 MCBBS，登录账号，记得勾选 “自动登录” 避免 Cookie 失效！

登录成功后，按下 F12，转到 Network，此时再按下 F5 即可看到请求。

![image](https://i.natfrp.org/a46c50dd53f6806b9deb8fb49869e799.png?s=1)

选中第一个请求，点击右侧 Request Headers 旁边的 view source，切换到原始数据格式

复制 Cookie: 后面的内容，粘贴到 cookie.txt 里。

然后编辑 config.php，根据自己的情况进行设置，里面有说明。

再次运行 main.php 即可开始自动签到。
````bash
php main.php
````
如果要停止，直接按 Ctrl + C 即可。

使用 screen 等软件将签到程序挂到后台
````bash
# 如果没有安装 screen，先安装
yum install screen -y
# Ubuntu 等系统
apt install screen -y
# 然后创建新的 screen 会话
screen -S autosign
# 接着运行主程序
php main.php
````
运行起来后，按下 Ctrl + A + D 将会话挂起到后台运行。

如果要返回会话，输入以下命令
````bash
screen -r autosign
````
