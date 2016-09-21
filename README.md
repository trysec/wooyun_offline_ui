## 说明

花了10分钟重做了一套乌云离线Web UI，重做总共有两个目的，

1. 没必要使用14GB的虚拟机，经过处理，40K漏洞只占用500MB mongodb 硬盘空间
2. 上述作者没有提取 关注数、是否为有奖励或者漏洞预警的漏洞 等几个关键字段

## 特性

支持漏洞标题搜索、漏洞wooyun id搜索

![alt tag](https://raw.githubusercontent.com/CaledoniaProject/wooyun_offline_ui/master/contrib/screen.jpg)

支持漏洞查看（删除了评论），原始 wooyun UI

![alt tag](https://raw.githubusercontent.com/CaledoniaProject/wooyun_offline_ui/master/contrib/screen2.jpg)


## 安装

复制 src 到 web 目录，e.g

```
cp -R src ~/web/wooyun_offline
```

安装 php-mongo 扩展

mongo 默认为 `127.0.0.1:27017`，如有不同，修改 `src/config.php` 即可

```
<?php
	$config = array(
		'mongodb' => 'mongodb://127.0.0.1:27017'
	);
?>
```

## 导入数据

导入到 `wooyun` 数据库，`bugs` 表即可

由于政策风险，我不能提供数据，可以用网上的14GB虚拟机导出

然后使用 `src/scripts/minify.pl` 精简 mongo 数据库

## 已知问题

1. 缺少 `images/blood.png`
2. 漏洞库不全，可惜没用高权限账号爬


