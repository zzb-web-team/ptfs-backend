<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function writelog($url, $method, $data, $body, $type = "POST"){
    $path = "log/".date("Ymd");
    if (!is_dir($path)){  
        mkdir($path,0777,true); 
    }
	$file = fopen($path."/".date("H").".txt","a+"); //次方法会自动生成文件test,txt,a表示追加写入，
    fwrite($file, "-----".date("H:i:s")."-----".$type."----".$url."\n");
    fwrite($file, "---------------------------\n");
    fwrite($file, $method."\n");
    fwrite($file, "---------------------------\n");
    fwrite($file, json_encode($data)."\n");
    fwrite($file, "---------------------------\n");
    fwrite($file, $body."\n");
    fwrite($file, "---------------------------\n\n");
    fclose($file);
}