# folder2oss

监控一批目录，上传更新的文件到OSS.

1.conf.inc.php
配置oss的key

2.floderup.php
配置
$bucket oss上的
$host 对应目录头，会去除
$floder 监控的目录

3.m.log
目录的监控信息

4.f.log
待上传文件的列表。监控到修改的内容会先存到这里，再次运行时上传到OSS。上传完成后会删除该文件。