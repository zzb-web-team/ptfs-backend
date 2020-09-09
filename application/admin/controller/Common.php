<?php
namespace app\admin\controller;

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
        curl_setopt($curl, CURLOPT_URL, config("ipfs.xyjurl"));
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

         //writelog(config("ipfs.xyjurl"), $method, $data, $body);
        // if (!$body) {
        //     return json_encode(['status' => -900, 'msg' => '数据中心服务请求失败']);
        // }
        return $body;
    }

    public static function formatByte($number, $unit = 'GB')
    {
        if ($unit == 'KB') {
            return round($number / 1024, 3) . "KB";
        }
        if ($unit == 'MB') {
            return round($number / 1024 / 1024, 3) . "MB";
        }
        if ($unit == 'GB') {
            return round($number / 1024 / 1024 / 1024, 3) . "GB";
        }
        return round($number / 1024 / 1024 / 1024 / 1024, 3) . "TB";
    }

    public static function actionLog($action, $description, $beforevalue, $utype, $uid, $user)
    {
        $param = array(
            "tb_name" => 'tb_action_log',
            "insert" => [
                [
                    $action, $description, $beforevalue . "", $utype, intval($uid), $user . "",
                ],
            ],
        );
        return self::loadApiData("store/insert_table", $param);

    }

    public static function getIp()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    public static function judge_password($password){
        $score = 0;
        if(!empty($password)){ //接收的值
            $str = $password;
        } else{
            $str = '';
        }
        if(preg_match("/[0-9]+/",$str))
        {
            $score ++;
        }
        if(preg_match("/[0-9]{3,}/",$str))
        {
            $score ++;
        }
        if(preg_match("/[a-z]+/",$str))
        {
            $score ++;
        }
        if(preg_match("/[a-z]{3,}/",$str))
        {
            $score ++;
        }
        if(preg_match("/[A-Z]+/",$str))
        {
            $score ++;
        }
        if(preg_match("/[A-Z]{3,}/",$str))
        {
            $score ++;
        }
        if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$str))
        {
            $score += 2;
        }
        if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$str))
        {
            $score ++ ;
        }
        if(strlen($str) >= 10)
        {
            $score ++;
        }
        if($score>=1 && $score<=3){
            return ['lv' => 1,'lvmsg' => '弱'];
        }else if($score>=4 && $score<=6){
            return ['lv' => 2,'lvmsg' => '中'];
        }else if($score>=7){
            return ['lv' => 3,'lvmsg' => '强'];
        }

    }

    public function cachedb($tbname, $colname, $cachename,$where = '1')
    {
        $i = 0;
        $result = [];
        do {
            $param = [
                "page" => $i,
                "page_size" => 10,
                "tb_name" => $tbname,
                "col_name" => $colname,
                "where" => $where,
                "order" => 'id desc',
            ];
            $menu = self::loadApiData("store/find_table", $param);
            $return_data = json_decode($menu, true);
            $i++;
            if ($i > 0) {
                $result = array_merge($result, $return_data['result']['cols']);
            }
        } while ($return_data['result']['les_count'] != 0 && !empty($menu));
        $res = Cache::store('redis')->set($cachename, json_encode($result));
        if ($res) {
            return json_encode($result);
        }
    }

}
