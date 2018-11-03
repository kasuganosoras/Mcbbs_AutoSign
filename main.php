<?php
/**
 *
 *	ZeroDream MCBBS 我的世界中文论坛自动签到工具
 *
 *	by Akkariin (Email: akkariins@gmail.com)
 *
 *	请务必保持本文件编码为 UTF-8，否则可能会乱码！
 *
 */
class ZeroDream {
	public function http($url, $post = '', $cookie = '', $returnCookie = 0, $referer = "") {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_REFERER, $referer);
		if ($post) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if ($cookie) {
			curl_setopt($curl, CURLOPT_COOKIE, str_replace("\n", "", $cookie));
		}
		curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		curl_close($curl);
		return $data;
	}
	
	public function Println($str) {
		echo date("[Y-m-d H:i:s] ") . "{$str}\n";
	}
	
	public function randomText() {
		$text = "";
		if(file_exists("rand.txt")) {
			$text = @file_get_contents("rand.txt");
		}
		if($text !== "") {
			$text = explode("\n", $text);
		} else {
			$text = Array(
				'快乐咸鱼每一天~',
				'我来签到啦~',
				'祝 MCBBS 越来越好',
				'滑稽，签到签到',
				'我什么也不说，这是坠吼的',
				'这个签到有点意思',
				'签到真棒wwwww',
				'签到有积分真好'
			);
		}
		$text = $text[mt_rand(0, count($text) - 1)];
		$text = $text == "" ? "ZeroDream" : $text;
		return $text;
	}
	
	public function sendMail($mailto, $mailsub, $mailbd) {
		global $smtpserver;
		global $smtpserverport;
		global $smtpusermail;
		global $smtpuser;
		global $smtppass;
		$smtpemailto = $mailto;
		$mailsubject = $mailsub;
		$mailsubject = "=?UTF-8?B?" . base64_encode($mailsubject) . "?=";
		$mailbody    = $mailbd;
		$mailtype    = "HTML";
		$smtp        = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);
		$smtp->debug = false;
		$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
	}
}
include("smtp.php");
include("config.php");
$ZeroDream = new ZeroDream();
date_default_timezone_set('Asia/Shanghai');
$text = $ZeroDream->randomText();
if(!file_exists("cookie.txt")) {
	@file_put_contents("cookie.txt", "");
	$ZeroDream->Println("系统已在目录下生成一个 cookie.txt。");
	$ZeroDream->Println("请将您的 Cookie 复制到 cookie.txt 中。");
	exit;
}
while(true) {
	if(intval(date("H")) == 4 || $retry == true) {
		$cookie = @file_get_contents("cookie.txt");
		$data = $ZeroDream->http("http://www.mcbbs.net/forum.php", false, $cookie);
		$data = mb_stristr($data, '<input type="hidden" name="formhash" value="');
		$formhash = mb_substr($data, 44, 8);
		if($formhash == "" || mb_strlen($formhash) !== 8) {
			$ZeroDream->Println("formhash 值读取错误，重试中...");
			continue;
		} else {
			$ZeroDream->Println("读取到论坛 formhash 值为：{$formhash}");
			$ZeroDream->Println("开始执行签到...");
			$ZeroDream->Println("随机签到语：{$text}");
			$post = Array(
				'formhash' => $formhash,
				'signsubmit' => 'yes',
				'handlekey' => 'signin',
				'emotid' => '1',
				'referer' => 'http://www.mcbbs.net/plugin.php?id=dc_signin',
				'content' => $text
			);
			$resu = $ZeroDream->http("http://www.mcbbs.net/plugin.php?id=dc_signin:sign&inajax=1", $post, $cookie, false, 'http://www.mcbbs.net/plugin.php?id=dc_signin');
			$data = simplexml_load_string($resu);
			if(mb_stristr($resu, "成功")) {
				$data = mb_substr($resu, mb_stripos($resu, "签到成功"), mb_stripos($resu, "', {});}</script>"));
				$data = explode("'", $data);
				$data = $data[0];
				$ZeroDream->Println("签到成功！服务器返回：{$data}");
				if($enable_mail) {
					$ZeroDream->sendMail($adminmail, "MCBBS 签到成功", "<p>ZeroDream MCBBS 签到助手已帮您完成签到啦~</p><p>以下是签到后服务器返回的内容：{$data}</p><p><i>MCBBS 签到助手 by Akkariin</i></p>");
				}
				$retry = false;
			} elseif(mb_stristr($resu, "已经")) {
				$data = mb_substr($data, 0, mb_stripos($data, "<script"));
				$ZeroDream->Println("签到失败！服务器返回：{$data}，原始数据：{$resu}");
				$ZeroDream->Println("由于已经签到或手动签到，本次任务取消，等待明天继续执行。");
				$retry = false;
			} else {
				$data = mb_substr($data, 0, mb_stripos($data, "<script"));
				$ZeroDream->Println("签到失败！服务器返回：{$data}，原始数据：{$resu}");
				$ZeroDream->Println("系统将在稍后重新尝试签到！");
				if($enable_mail) {
					$ZeroDream->sendMail($adminmail, "MCBBS 签到出错", "<p>ZeroDream MCBBS 签到助手在签到时发生了一些错误！</p>
						<p>以下是签到后服务器返回的内容：{$data}</p>
						<p>您无需担心，系统会在稍后再次尝试签到。</p>
						<p>原始数据：</p>
						<pre>" . htmlspecialchars($resu) . "</pre>
						<p><i>MCBBS 签到助手 by Akkariin</i></p>");
				}
				$retry = true;
			}
		}
	} else {
		$ZeroDream->Println("正在等待凌晨 4 点，当前时间：" . date("H") . " 点。");
	}
	if($randtime) {
		$timeout = $timeout + mt_rand(0, $maxrandom);
	}
	sleep($timeout);
}