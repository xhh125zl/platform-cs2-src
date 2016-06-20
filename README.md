# 前言
将apache的网站根目录设置为htdocs，并设置apache文件默认首页为index.html和index.php  

# 安装
1.先通过mysql客户端导入数据库db.sql文件  
2.修改数据库配置文件  
复制 /Framework/dbconfig.default.php 文件，改名为dbconfig.php文件，并填写正确数据库配置信息  

# 配置网站域名
修改 htdocs/index.php 文件中的 MAIN_SITE，如下所示  
define('MAIN_SITE', 'www.example.com');


# 升级注意事项
由于系统不变的改进变动，到于程序文件的修改可以通过Git来同步，而数据库则无法实现这一点。  
这里则需要对数据表的升级sql语句存放到upgrade目录里最新的日期文件夹里，当此目录内sql文件大于50个的时候，则再启用另一个新的日期目录。以此避免过多产生sql，造成维护成本过高。  

每个升级语句以表名为单位存放相应的升级语句。如新添加的一个用户表，则相应的sql文件名称为 tbl_newusers.sql，文件内容为相应的创建表语句。后期如果有对此表的修改，则语句调整为 tbl_newusers_upgrade_1.sql, 后面的数字依次累计。
如果对多个表进行了修改，则创建多个对应的sql升级文件。  


# 待续(未完)