<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class Oauth extends Common
{
	public function check()
	{
		$data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $token = $data['login_token'];
        $login_type = isset($data['login_type']) ? intval($data['login_type']) : 1;
        //检测
        $param = array(
            "login_type" => $login_type,
            "login_token" => $token,
        );
        $return_data = self::loadApiData("account/query_bind_user_info", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        if ($return_data['status'] == 0) {
            $status = $return_data['data']['bind_status'];
            if ($status != 1) {
                $state = urlencode($token.'|'.$login_type);
                $return_data['url'] = request()->domain().'/oauth/index?state='.$state;
            }     
        }
        return json($return_data);
        
	}

    public function cachetoken()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'login_token' => 'require',
            'login_type'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['login_type'] == 1 || $data['login_type'] == 2) {
            //有token 缓存1小时
            Cache::store('redis')->set('cachetoken:'. $data['login_token'], $data['login_token'], 3600);
        } else {
            Cache::store('redis')->set('cachetoken:'. $data['login_token'], $data['login_token'], 3600);
        }
        return json(['status' => 0, 'msg' => Cache::store('redis')->get('cachetoken:'. $data['login_token'])]);
    }

    public function gettoken()
    {
        return json(['status' => 0, 'msg' => Cache::store('redis')->get('cachetoken:'. $data['login_token'])]);
    }

    public function order()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'amount'  =>  'require',
             'wallet_type'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $amount = $data['amount'];
        $wallet_type = $data['wallet_type'];
        //检测
        $param = array(
            "login_type" => isset($data['login_type']) ? intval($data['login_type']) : 1,
            "login_token" => $data['login_token'],
            "deal_type" => 1,
            "amount" => $amount*1000000,
            "wallet_type" => $wallet_type,
        );
        $return_data = self::loadApiData("accountex/xb_product_order", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

	public function pay()
	{
		$data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'amount'  =>  'require',
             'wallet_type'  =>  'require',
             'open_id' => 'require',
             'order_code' => 'require',
             'charge_psd' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        
        //检测
        $param = array(
            "login_type" => isset($data['login_type']) ? intval($data['login_type']) : 1,
            "login_token" => $data['login_token'],
            "charge_psd" => $data['charge_psd'],
        );
        $return_data = self::loadApiData("account/authen_charge_psd", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            $return_data['info'] = '交易密码错误';
            return json($return_data);
        }

        $amount = $data['amount'];
        $wallet_type = $data['wallet_type'];
        $openid = $data['open_id'];
        $order_code = $data['order_code'];
        $param = array(
        	//"client_id" => "343751051310141440",
        	//"client_secret" => "1T5Rl6EwFHTfeZ7sI2SMKdbg2Q7IGoK0",
            "client_id" => "366312144214331392",
            "client_secret" => "WfoMjrqRcC30wMncmDHkyQmBSVHRvvL3",
            "open_id" => $openid,
            "amount" => $amount,
            "wallet_type" => $wallet_type,
            "transfer_type" => 1,
            "order_code" => $order_code,
            "time_stemp" => time()."",
        );
        ksort($param);
        $signature = $this->sign($param);
        $header[] = 'signature: '.$signature;
        //$param['signature'] = $signature;
        //$return_data = self::curl_post("http://api.whlive.top/api/oauthclient/merchanttransfer", json_encode($param), $header);
        $return_data = self::curl_post("https://api.jlpfcj.com/api/oauthclient/merchanttransfer", json_encode($param), $header);
        $return_data = json_decode($return_data, true);
        if ($return_data['code'] != 1) {
            //小白交易请求失败
            return json(['status' => -901, 'msg' => $return_data]);
        }

        // $param = array(
        //     "client_id" => "343751051310141440",
        //     "client_secret" => "1T5Rl6EwFHTfeZ7sI2SMKdbg2Q7IGoK0",
        //     "open_id" => $openid,
        //     "transfer_type" => 1,
        //     "order_code" => $order_code,
        // );
        // $return_data = self::curl_post("http://api.whlive.top/api/oauthclient/order/status", json_encode($param));
        // $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['order_code'])) {
            //确认订单
            $param = array(
                "login_type" => isset($data['login_type']) ? intval($data['login_type']) : 1,
                "login_token" => $data['login_token'],
                "xb_order_id" => $return_data['data']['order_code'],
            );
            $return_data = self::loadApiData("deal/authen_order", $param);
            $return_data = json_decode($return_data, true);
            if (isset($return_data['data']['login_token'])) {
                $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
            }
        }
        return json($return_data);


	}

    public function sign($param, $sign = null)
    {
        $str = 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAL2SJzwq6hF2YByPLiq8nUEgOu7CnxWDGiyY3hHuLhwxyfZLEThsfZTRA4FB5wPbq5hOkRjz0TIQFCjngm7Q7LWHCmFUiwq6KvcmI3vutIPXHVP29ZxrLR5stcTDDiWJH+fb6ym70zDBfY7y20C1UuCzhKUWm6YgvF7uzze3EatbAgMBAAECgYAubz9nEIf3MQzH0haX502Jp1BoLYn0JgHiTKuQrsvioht7VdXpAUIdkrkOD0t7+XHlw9Ds0MQ8RA38GwErDcf9Ho79x4UCJA6RlliQqiTgGyNJJQDP8PuRl4+6qB4hgqphPmaMQb81NDH4rm5q2YCEaypPoJLOgV9LU1lhPgEDcQJBAPRehsXjon0CTJUJTIRWxIp0KkLXNfo/SLTTAQOT4JcLj039oF5/eh2zJPAqFSa8K0BTnLPEIA0VwvKjsLZOdxkCQQDGl/I/IPZDbkPytmdQooHrJ0zxNouJm2024kx3LS/FYZyGVyDXkhVoTg9cP/ToRQ9+JsxIdVXyAWbCUYEMboiTAkEAvjeOZR+qbfCKOEDCxQjdeICwHNN0+tSj/c15rpU/b5PZ+vWADc7g+ZlnGWNIj5xNdocfJXd3E3hrBYCRn3c4kQJAJCUTmhnNuIghJdO9ChgJvVrxzhU3YFxBjHOzpx06TJpveqPw/ktASjOq6Adb88pd/3/gPm/crKdEpKlg63pSsQJBAIoKQjmwqFrzYqRbKbAii865xOoWX9OYr6MHNs9giSvrk38PxsccfVXNXQRJYSl0smXo+y2E3yqzgGrPKeMMCnE=';
        $str        = chunk_split($str, 64, "\n");
        $res = "-----BEGIN RSA PRIVATE KEY-----\n$str-----END RSA PRIVATE KEY-----\n";

        //拼接value
        $string = '';
        foreach ($param as $key => $value) {
            $string.=$value;
        }
        $d = openssl_sign($string, $sign, $res,'SHA256');
        return $mysign = bin2hex($sign);
    }

    public function orderlist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'login_token'  =>  'require',
            'start_time'  =>  'require',
            'end_time'  =>  'require',
            'cur_page' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_token" => $data['login_token'],
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "cur_page" => $data['cur_page'],
        );
        $return_data = self::loadApiData("deal/query_deal_info_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

    public function index()
    {
    	$state = $this->request->param('state', '');
    	if (!$state) {
    		return json(['status' => -900, 'msg' => '缺少参数state']);
    	}
        $param = array(
            //"client_id" => "343751051310141440",
            "client_id" => "366312144214331392",
        	"redirect_uri" => request()->domain()."/oauth/callback",
        	"response_type" => "code",
        	"scope" => "all",
        	"state" => $state,
        );
        //$url = "http://www.test.otcrmbt.com:10008/oauth?".http_build_query($param);
        $url = "https://wap.jlpfcj.com/oauth?".http_build_query($param);
        return redirect($url);
    }

    public function callback()
    {
    	$code = $this->request->param('code', '');
    	$state = $this->request->param('state', '');
    	if (!$code) {
    		return json(['status' => -900, 'msg' => '未找到返回code']);
    	}

    	if (!$state) {
    		return json(['status' => -900, 'msg' => '未找到返回state']);
    	}

        $param = array(
        	"grant_type" => "authorization_code",
        	"code" => $code,
            //"client_id" => "343751051310141440",
            //"client_secret" => "1T5Rl6EwFHTfeZ7sI2SMKdbg2Q7IGoK0",
            "client_id" => "366312144214331392",
            "client_secret" => "WfoMjrqRcC30wMncmDHkyQmBSVHRvvL3",
            "redirect_uri" => request()->domain()."/oauth/callback",
        );
        //$data = self::curl_post("http://api.whlive.top/api/oauth/accesstoken", json_encode($param));
        $data = self::curl_post("https://api.jlpfcj.com/api/oauth/accesstoken", json_encode($param));
        $data = json_decode($data, true);
        if (!isset($data['access_token'])) {
            return json(['status' => -900, 'msg' => '授权失败']);
        }
        //获取用户信息
        $param = array(
            "access_token" => $data['access_token'],
        );
        //$data2 = self::curl_post("http://api.whlive.top/api/oauthuser/getuserinfo", json_encode($param));
        $data2 = self::curl_post("https://api.jlpfcj.com/api/oauthuser/getuserinfo", json_encode($param));
        $data2 = json_decode($data2, true);

        if (!isset($data2['data']['userName'])) {
            return json(['status' => -900, 'msg' => '获取用户信息失败']);
        }
        $userName = $data2['data']['userName'];

        $state = explode("|", $state);
        $token = $state[0];
        $type = $state[1];

        //注册openid
        $param = array(
            "set_type" => 1,
            "login_token" => $token,
            "xb_access_token" => $data['access_token'],
            "xb_refresh_token" => $data['refresh_token'],
            "expires_in" => $data['expires_in'],
            "refresh_expires_in" => $data['refresh_expires_in'],
            "scope" => $data['scope'],
            "open_id" => $data['openid'],
            "xb_user_name" => $userName,
            "client_type" => 1,
            "is_web_login" => 0
        );
        $return_data = self::loadApiData("accountex/set_xb_user_info", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json(['status' => -900, 'msg' => '授权失败']);
        }
        // //绑定openid
        // $param = array(
        //     "open_id" => $data['openid'],
        //     "login_token" => $token,
        //     "login_type" => $type,
        // );
        // return $return_data = self::loadApiData("account/bind_user_info", $param);
        // $return_data = json_decode($return_data, true);
        // if ($return_data['status'] != 0) {
        //     return json(['status' => -900, 'msg' => '授权失败']);
        // }
       
        echo json_encode($return_data);
        //$this->setAccessToken($state, $data);
        // $data = '{"access_token":"1.3a9da00f04d98e48a7f65dcd9ba777e962d7e0b1.2592000.1560672388","refresh_token":"2.df5855675c532f78bfe41ca2def309e2531185f6.7776000.1565856388","refresh_expires_in":7776000,"openid":"MDA2Ng%3D%3Dke0p3MTAw","scope":"ALL","expires_in":2592000}';
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        //分别进行判断
        if(strpos($agent,'iphone') || strpos($agent,'ipad')){
           return '<script>window.webkit.messageHandlers.closeWebView.postMessage("goTomoney")</script>';
        }
        return '<script>window.parent.postMessage("Hello!","*")</script>';
    }

    public function test(){
        $string = '{"amount":"0.0001","client_id":"366312144214331392","client_secret":"WfoMjrqRcC30wMncmDHkyQmBSVHRvvL3","open_id":"MTI3MQ%3D%3De3uh8MTAw","order_code":"85e47Fk7","time_stemp":"1567560184","transfer_type":1,"wallet_type":"EUSD"}';
        return $this->sign($string);
    	exit;
    }

    public function delete(){
        $data = input("get.");
        $tel = isset($data['tel']) ? $data['tel'] : "";
        if (!$tel) {
            return json(['status' => -1, 'msg' => 'no tel']);
        }
        Cache::store('redis')->rm('openid:'. $tel);
        Cache::store('redis')->rm('access_token:'. $tel);
        Cache::store('redis')->rm('refresh_token:'. $tel);
        return json(['status' => 0, 'msg' => 'success']);
        exit;
    }

    



}
