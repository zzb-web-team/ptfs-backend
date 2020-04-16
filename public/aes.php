<?php
$Key = [0x04, 0xe3, 0x78, 0xec, 0x38, 0xe5, 0x07, 0x43, 0x85, 0x6f, 0x60, 0xed, 0x04, 0xcf, 0xc7, 0x38];
$privateKey = '';
for($i=0;$i<count($Key);$i++) {
	$privateKey.=chr($Key[$i]);
}
$string = base64_decode('dz1HIXFlS2ExMFFPJUYjWjHkmL4cRpM0vJ4ByLAQpZrlbVPPLCKUIxbJLJIYZ3LbVrAj6eaTj4CPKiNPmQWVMD91m+G1LZTzdd4qtHocP1xXD0zixlsnuL9bOeWbD+fzyFcYHxwxSjpLG+3ix+z20rzrSQqGqovuhpQrX0PfBX2wDlSbZG+TMrU+AK3mmWdIPuexhUwJ1LQCdjSrmMwtJ1ojzvdj24ppqapwnx33SBhlJTz4k3HiMK3CsCwRzvmcyGLhXKXyhJLEX/JHXwMEgnLAyr+OICPnM1hbfYhd0zQx+lvuVYJwji+8CWqyEqCRqXme8d7bb1DrBQuhwrv+6xu6wD8r/ym/nBTurtYxJm7KwSoMhKGAKCU+grfOfG/mrAQfQp4kcetFFZpW1UETSBW+cAYOlnQkbpDy7GzwqOU=');

$iv = substr($string,0,16);
$data = substr($string,16);

// //加密
// $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $data, MCRYPT_MODE_CBC, $iv);
// echo(base64_encode($encrypted));
// echo '<br/>';

//解密
$encryptedData = $data;
$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $privateKey, $encryptedData, MCRYPT_MODE_CBC, $iv);
echo($decrypted);
?>