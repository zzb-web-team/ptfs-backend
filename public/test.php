<?php
ini_set("display_errors", 1);
include('./Apk.php');
$appObj  = new Apkparser(); 
$targetFile = "dts100030.apk";//apk所在的路径地址
$res   = $appObj->open($targetFile);
echo $appObj->getAppName()."<br>";     // 应用名称
echo $appObj->getPackage()."<br>";    // 应用包名
echo $appObj->getVersionName()."<br>";  // 版本名称
echo $appObj->getVersionCode()."<br>";  // 版本代码
?>
