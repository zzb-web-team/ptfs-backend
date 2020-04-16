<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class PtfsStorageUser extends Common
{
    //注册/登录
    public function test()
    {
        // $token = $this->request->param('token', '');
        // if (Cache::store('redis')->has('token:'.$token)) {
        //     return  Cache::store('redis')->get('token:'.$token);
        // }
        // return '没有token';
        //var_dump(Cache::store('redis'));exit;
        Cache::store('redis')->set('key1','123456789');
        echo Cache::store('redis')->get('key1');
        
        exit;
    }

    // private function check_token($token) {
    //     $token = str_replace(" ", "+", $token);
    //     if (Cache::store('redis')->has('token:'. $token)) {
    //         return true;
    //     }
    //     return false;
    // }

    // private function refresh_token($old_token, $new_token){
    //     $this->unset_token($old_token);
    //     $this->set_token($new_token);
    // }

    // private function set_token($token, $expire = 60 * 15){
    //     $token = str_replace(" ", "+", $token);
    //     Cache::store('redis')->set('token:'. $token, time(), $expire);
    // }

    // private function unset_token($token){
    //     $token = str_replace(" ", "+", $token);
    //     Cache::store('redis')->rm('token:'. $token);
    // }

    //注册/登录
    public function getcode()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'user_tel'  =>  'require|mobile',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $flag = isset($data['change_telnum_flag']) ? $data['change_telnum_flag'] : 0;
        if ($flag == 1) {
            if (!isset($data['login_token'])) {
                return json(['status' => -900, 'msg' => '未找到token']);
            }
            if (!$this->check_token($data['login_token'])) {
                return json(['status' => -999, 'msg' => 'token验证失效']);
            }
            $param = array(
                "user_tel" => $data['user_tel'],
                "change_telnum_flag" => isset($data['change_telnum_flag']) ? $data['change_telnum_flag'] : 0,
                "login_token"   => $data['login_token'],
            );
        } else {
            $param = array(
                "user_tel" => $data['user_tel'],
                "change_telnum_flag" => isset($data['change_telnum_flag']) ? $data['change_telnum_flag'] : 0,
            );
        }
        
        $return_data = self::loadApiData("account/get_code", $param);
        $return_data = json_decode($return_data, true);
        // if ($return_data['status'] == 0) {
        //     return json(['status' => 1, 'msg' => '发送成功']);
        // }
        return json($return_data);
    }

	//注册/登录
    public function login()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_type'  => 'require|integer',
             'user_name'   => 'require',
             'user_passwd' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_type"   => $data['login_type'],
            "user_name"    => $data['user_name'],
            "user_passwd"  => $data['user_passwd'],
        );
        $return_data = self::loadApiData("account/login", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->set_token($return_data['data']['login_token']);
        }
        return json($return_data);
    }

    //登出
    public function logout()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("account/logout", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->unset_token($data['login_token']);
        }
        return json($return_data);
    }

    //获取用户信息
    public function getuserinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if (!$this->check_token($data['login_token'])) {
            return json(['status' => -999, 'msg' => 'token验证失效']);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
            "col_name" =>   $data['col_name'], 
        );
        return $return_data = self::loadApiData("account/get_user_info", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

    //修改用户附属信息
    public function updateuserinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if (!$this->check_token($data['login_token'])) {
            return json(['status' => -999, 'msg' => 'token验证失效']);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
            "col_name"   => $data['col_name'],
            "col_value"   => $data['col_value'],
        );
        $return_data = self::loadApiData("account/update_user_info", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

    //修改用户附属信息
    public function changeusertelnum()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'tel_num' => 'require',
             'user_passwd' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if (!$this->check_token($data['login_token'])) {
            return json(['status' => -999, 'msg' => 'token验证失效']);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "tel_num"   => $data['tel_num'],
            "user_passwd"   => $data['user_passwd'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("account/change_user_telnum", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

    //交易密码设置(修改)
    public function setuserchargepsd()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'user_charge_psd' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if (!$this->check_token($data['login_token'])) {
            return json(['status' => -999, 'msg' => 'token验证失效']);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "user_charge_psd"   => $data['user_charge_psd'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("account/set_user_charge_psd", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }

    //查询交易密码设置状态
    public function getuserchargestatus()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if (!$this->check_token($data['login_token'])) {
            return json(['status' => -999, 'msg' => 'token验证失效']);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("account/get_user_charge_psd_status", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['login_token']);
        }
        return json($return_data);
    }


}
