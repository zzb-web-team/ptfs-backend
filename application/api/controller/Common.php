<?php
namespace app\api\controller;
use think\App;
use think\Controller;
use think\facade\Cache;

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

    public static function loadCloudData($method, $data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, config("ipfs.cloudurl"));
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

        writelog(config("ipfs.cloudurl"), $method, $data, $body);
        return $body;
    }

    public function check_token($token) {
        $token = str_replace(" ", "+", $token);
        if (Cache::store('redis')->has('token:'. $token)) {
            return true;
        }
        return false;
    }

    public function refresh_token($old_token, $new_token){
        $this->unset_token($old_token);
        $this->set_token($new_token);
    }

    public function set_token($token, $expire = 24 * 60 * 60){
        $token = str_replace(" ", "+", $token);
        Cache::store('redis')->set('token:'. $token, time(), $expire);
    }

    public function unset_token($token){
        $token = str_replace(" ", "+", $token);
        Cache::store('redis')->rm('token:'. $token);
    }

    public static function curl_post($url, $data, $header=[])
    {
        //初始化
        $header[] = 'Content-Type: application/json';
        $header[] = 'Content-Length: ' . strlen($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        return $result;
    }

    public function checkOauth($tel) {
        if (Cache::store('redis')->has('access_token:'. $tel)) {
            return true;
        }
        return false;
    }

    public function setAccessToken($tel, $token){
        $token = json_decode($token, true);
        if (isset($token['access_token'])) {
            $access_token = $token['access_token'];
            $expires_in = $token['expires_in'];
            $refresh_token = $token['refresh_token'];
            $refresh_expires_in = $token['refresh_expires_in'];
            $openid = $token['openid'];
            Cache::store('redis')->set('openid:'. $tel, $openid);
            Cache::store('redis')->set('access_token:'. $tel, $access_token, $expires_in);
            Cache::store('redis')->set('refresh_token:'. $tel, $refresh_token, $refresh_expires_in);
            return true;
        }
        return false;  
    }

    public function getOpenid($tel) {
        return Cache::store('redis')->get('openid:'. $tel);
    }

    public function getAccessToken($tel){
        $access_token = Cache::store('redis')->get('access_token:'. $tel);
        if (!$access_token) {
             return $this->refreshToken($tel);
        }
        return $access_token; 
    }

    public function refreshToken($tel){
        $refresh_token = Cache::store('redis')->get('refresh_token:'. $tel);
        if (!$refresh_token) {
            return false;
        }
        $param = array(
            "refresh_token" => $refresh_token,
        );
        $data = self::curl_post("http://api.whlive.top/api/oauth/gettokenbyrefresh", json_encode($param));
        return $this->setAccessToken($tel, $data);
    }


}
