# 前言
将apache的网站根目录设置为htdocs，并设置apache文件默认首页为index.html和index.php  

# 安装
1.先通过mysql客户端导入数据库db.sql文件  
2.修改数据库配置文件  
复制 /Framework/dbconfig.default.php 文件，改名为dbconfig.php文件，并填写正确数据库配置信息  

# 配置网站域名
修改 htdocs/index.php 文件中的 MAIN_SITE，如下所示  
define('MAIN_SITE', 'www.example.com');

# 待续(未完)