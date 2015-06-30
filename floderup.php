<?php

require_once './demo/tutorial.php';
require_once './sdk.class.php';

$oss_sdk_service = new ALIOSS();

//设置是否打开curl调试模式
$oss_sdk_service->set_debug_mode(FALSE);

//var_dump($oss_sdk_service);
$bucket = 'qtyd';
$host = "D:\\xampp\\htdocs\\www.zjqttz.comv1\\www\\";
$floder = array("D:\\xampp\\htdocs\\www.zjqttz.comv1\\www\\upload",
"D:\\xampp\\htdocs\\www.zjqttz.comv1\\www\\plupload",
	);
//遍历目录

set_time_limit(0);

// define('M_PATH',$floder);  //设置监控的目录，当前目录为'.'，上一级目录为'..'，也可以设置绝对路径，后面不要加斜杠
define('M_LOG','./m.log');  //设置存储log的路径，可以放置在任意位置
define('M_FILE','./f.log');  //待上传的文件列表

$filesarr = array(); //待更新文件
if(file_exists(M_FILE)){
	_log('m file is exists.');

	$filesarr = unserialize(file_get_contents(M_FILE));
	foreach ($filesarr as $key=>$file) {
		_log('m file :'.$file);
//上传动作：
		$object = str_replace($host, "", $file);
		$file_path = $file;
		
		$response = $oss_sdk_service->upload_file_by_file($bucket,$object,$file_path);
		_format($response);

		unset($filesarr[$key]);
		file_put_contents(M_FILE,serialize($filesarr));
	}

	@unlink(M_FILE); 
	exit;
}


$file_list = array();

foreach ($floder as $f) {
	record_md5($f);
}

if(file_exists(M_LOG)){
        $log = unserialize(file_get_contents(M_LOG));
}else{
        $log = array();
}

file_put_contents(M_LOG,serialize($file_list));
if(count($file_list) > 0 ){
        foreach($file_list as $file => $md5){
                if(!isset($log[$file])){
                        _log('add:'.$file);
                        $filesarr[] = $file;
                }else{
                        if($log[$file] != $md5){
                                //print '修改：'.$file."\r\n";
								_log('modify:'.$file);
                        		$filesarr[] = $file;
                                unset($log[$file]);
                        }else{
                                unset($log[$file]);
                        }
                }
        }
}
if(count($log)>0){
        foreach($log as $file => $md5){
        // print "删除：".$file."\r\n";
			_log('delete:'.$file);
        }
}

if(count($filesarr)>0){
	file_put_contents(M_FILE,serialize($filesarr));
}

?>