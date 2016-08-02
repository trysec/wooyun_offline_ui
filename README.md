## 说明

花了10分钟重做了一套乌云离线Web UI，重做总共有两个目的，

1. 完全没必要使用网上流行的14GB的虚拟机，mongo 数据导出后，40k漏洞压缩完也只有 100MB 而已（解压导入本地 mongo 后 3.5 GB)
2. 上述作者没有提取 关注数、是否为有奖励或者漏洞预警的漏洞 等几个关键字段

![alt tag](https://raw.githubusercontent.com/CaledoniaProject/wooyun_offline_ui/master/contrib/screen.jpg)

## 数据

由于政策风险，我不能提供数据，可以用网上的14GB虚拟机导出，并执行 `src/scripts/transform.php` 添加缺失的字段

## 安装

复制 src 到 web 目录，e.g

```
cp -R src ~/web/wooyun_offline
```

在根目录下安装前端组件，

```
bower install bootstrap angularjs jquery angular-paging angularLocalStorage angular-cookies
```

安装 php-mongo 扩展

mongo 默认为 `127.0.0.1:27017`，如有不同，修改 `src/api.php` 里的这一句，

```
$conn = new MongoClient('mongodb://127.0.0.1:27017');
```

## 已知问题

缺少 `images/blood.png`


