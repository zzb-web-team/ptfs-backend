<?php
namespace app\cloud\controller;
use think\Controller;

class Common extends Controller
{
	public function initialize()
    {
        parent::initialize();
    }

    public static function loadApiData($method, $data)
    {
    	//初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, config("ipfs.apiurl"));
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data['method'] = $method;
        $post_data['data'] = $data;
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
        //执行命令
        $response = curl_exec($curl);
        //显示获得的数据
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        } else {
            $body = $response;
        }
        //关闭URL请求
        curl_close($curl);

        writelog(config("ipfs.apiurl"), $method, $data, $body);
        return $body;
    }

    public static function testApiData($method, $data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        //curl_setopt($curl, CURLOPT_URL, "http://10.0.0.140:8090/".$method);
        curl_setopt($curl, CURLOPT_URL, config("ipfs.apiurl4").$method);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data)))
        );

        //执行命令
        $response = curl_exec($curl);
        //显示获得的数据
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        } else {
            $body = $response;
        }
        //关闭URL请求
        curl_close($curl);
        writelog("zhangwenyuan--", $method, $data, $body);
        return $body;
    }

    public static function testApiDataForm($method, $data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        //curl_setopt($curl, CURLOPT_URL, "http://10.0.0.140:8090/".$method);
        curl_setopt($curl, CURLOPT_URL, config("ipfs.apiurl4").$method);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($data));
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            //'Content-Length: ' . strlen(json_encode($data))
            )
        );
        //执行命令
        $response = curl_exec($curl);
        //显示获得的数据
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        } else {
            $body = $response;
        }
        //关闭URL请求
        curl_close($curl);
        writelog("zhangwenyuan--", $method, $data, $body);
        return $body;
    }


    public static function formatByte($number, $unit = 'GB')
    {
        if ($unit == 'KB') {
            return round($number/1024, 3)."KB";
        }
        if ($unit == 'MB') {
            return round($number/1024/1024, 3)."MB";
        }
        if ($unit == 'GB') {
            return round($number/1024/1024/1024, 3)."GB";
        }
        return round($number/1024/1024/1024/1024, 3)."TB";
    }

    public static function actionLog($action, $description, $beforevalue, $aftervalue, $status, $utype, $uid, $user)
    {
        $param = array(
            "tb_name"   => 'cloud_action_log',
            "insert"    => [
                [
                    $action."", $description."", $beforevalue."", $aftervalue."", intval($status), $utype."", intval($uid), $user.""
                ]
            ]
        );
        return self::loadApiData("store/insert_table", $param);
        
    }

}
