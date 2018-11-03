<?php
/**
 *
 *	ZeroDream MCBBS 助手设置
 *
 */

// 是否启用邮件提醒功能，（true 是 false 否）
$enable_mail = true;
 
// SMTP 服务器地址，例如 smtp.tcotp.cn
$smtpserver = "smtp.tcotp.cn";

// SMTP 服务器端口，例如 25
$smtpserverport = 25;

// SMTP 用户邮箱，也就是发信邮箱
$smtpusermail = "noreply@tcotp.cn";

// SMTP 用户账号，登录用，一般与发信邮箱相同
$smtpuser = "noreply@tcotp.cn";

// SMTP 用户密码
$smtppass = "12345678";

// 用于接收签到结果的邮箱
$adminmail = "akkariins@gmail.com";

// 时间检测间隔，默认是 3600 秒（一小时），单位：秒
$timeout = 3600;

// 是否开启随机签到时间？（true 是 false 否）
$randtime = false;

// 随机增加时间范围最大值，即 0 ~ $maxrandom，单位：秒
$maxrandom = 100;

/**
 *
 *	提示：支持自定义签到语，只需要在当前目录下新建 rand.txt，然后每行输入一句话即可。
 *
 *	然后系统就会自动抽取其中一行作为签到语，最后一行不要换行，也就是结尾不要留出空行。
 *
 *	如果不创建 rand.txt，系统将会自动从内置签到语录中随机抽取一条。
 *
 */