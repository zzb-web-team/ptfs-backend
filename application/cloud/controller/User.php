<?php
namespace app\cloud\controller;
use think\Validate;
use think\facade\Cache;

class User extends Common
{
    private function sendcode($phone, $code) 
    {
        if ($phone == '13111111111')
            return false;
        //发送短信
        Cache::store('redis')->set('phone_verify:'.$phone, $code, 5 * 60);

        return true;
    } 

    public function getcode()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'phone'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $code = 123456;
        if ($this->sendcode($data['phone'], $code)){
            return json(['status' => 0, 'err_code' => 0,  'msg' => 'success']);
        }
        return json(['status' => -900, 'err_code' => -900,  'msg' => '短信平台错误']);
    }

    private function checkcode($phone, $code)
    {
        if (!Cache::store('redis')->has('phone_verify:'.$phone)) {
            return 0;
        }
        $truecode = Cache::store('redis')->get('phone_verify:'.$phone);
        if ($truecode != $code) {
            return -1;
        }
        return 1;
    }

    public function register()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'username'  =>  'require',
            'password'  =>  'require',
            'password2'  =>  'require',
            'phone' =>  'require',
            'code' =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['password'] != $data['password2']) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '两次密码不一致']);
        }
        $check = $this->checkcode($data['phone'], $data['code']);
        if ($check == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码未发送']);
        }
        if ($check == -1) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码不正确']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "username='".$data['username']."' or phone='".$data['phone']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该用户名或手机号已存在']);
        }
        $insert = array();

        $insert[] = [
            $data['username'],
            md5($data['password']),
            '',
            $data['phone'],
            '',
            0,
            md5($this->randStr(6))
        ];
        if (!$insert) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'cloud_user',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "phone='".$data['phone']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            //登录成功
            $user = $return_data['result']['cols'][0];
            $token = md5(time());
            Cache::store('redis')->set('cloud_user_token:'. $user['id'], $token);
            unset($user['password']);
            $param = array(
                "userid" => $user['id']."",
            );
            self::loadApiData("url_mgmt/add_user", $param);
            return json(['status' => 0, 'err_code' => 0, 'msg' => $user, 'token' => $token]);
        }
        return json($return_data);
    }

    private function randStr( $length = 6 ) { 
        // 密码字符集，可任意添加你需要的字符 
        $chars = 'abcdefghijkmnpqrstuvwxyABCDEFGHJKLMNPQRTUVWXY346789'; 
        $password = ''; 
        for ($i = 0; $i < $length; $i++) { 
            $password .= $chars[mt_rand(0, strlen($chars) - 1)]; 
        } 
        return $password; 
    } 

    public function login()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'username'  =>  'require',
            'password'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "username='".$data['username']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            if (isset($return_data['result']['cols'][0])) {
                $user = $return_data['result']['cols'][0];
                if (md5($data['password']) != $user['password']) {
                    return json(['status' => -900, 'err_code' => -900, 'msg' => '密码错误']);
                }
                if ($user['status'] == 1) {
                    return json(['status' => -900, 'err_code' => -900, 'msg' => '用户已被禁用']);
                }
                $token = md5(time());
                Cache::store('redis')->set('cloud_user_token:'. $user['id'], $token);
                unset($user['password']);
                return json(['status' => 0, 'err_code' => 0, 'msg' => $user, 'token' => $token]);
            }
            return json(['status' => -900, 'err_code' => -900, 'msg' => '找不到该用户']);
        }
        return json($return_data);
        
    }

    public function loginbyphone()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'phone'  =>  'require',
            'code'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $check = $this->checkcode($data['phone'], $data['code']);
        if ($check == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码未发送']);
        }
        if ($check == -1) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码不正确']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "phone='".$data['phone']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            //登录成功
            $user = $return_data['result']['cols'][0];
            $token = md5(time());
            Cache::store('redis')->set('cloud_user_token:'. $user['id'], $token);
            unset($user['password']);
            return json(['status' => 0, 'err_code' => 0, 'msg' => $user, 'token' => $token]);
        }
        return json(['status' => -900, 'err_code' => -900, 'msg' => '请先注册']);
        $insert = array();

        $insert[] = [
            '手机用户'.$data['phone'],
            '',
            '',
            $data['phone'],
            '',
            0,
            md5($this->randStr(6))
        ];
        if (!$insert) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'cloud_user',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "phone='".$data['phone']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            //登录成功
            $user = $return_data['result']['cols'][0];
            $token = md5(time());
            Cache::store('redis')->set('cloud_user_token:'. $user['id'], $token);
            unset($user['password']);
            return json(['status' => 0, 'err_code' => 0, 'msg' => $user, 'token' => $token]);
        }
        return json($return_data);
    }

    public function forgetpassword()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'type'  =>  'require',
            'user'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['type'] == 'phone') {
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"   => 'cloud_user',
                "col_name"  => "*",
                "where"     => "phone='".$data['user']."'",
                "order"     => 'id desc',
            );
            $return_data = self::loadApiData("store/find_table", $param);
            if (!$return_data) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
            }

            $return_data = json_decode($return_data, true);
            if ($return_data['status'] != 0) {
                return json($return_data);
            }
            if (!isset($return_data['result']['cols'][0])) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '不存在该用户']);
            }
            $code = 123456;
            if ($this->sendcode($data['user'], $code)){
                return json(['status' => 0, 'err_code' => 0,  'msg' => 'success']);
            }
            return json(['status' => -900, 'err_code' => -900,  'msg' => '短信平台错误']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "email='".$data['user']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (!isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '不存在该用户']);
        }
        $code = 123456;
        if ($this->sendemail($data['user'], $code)){
            return json(['status' => 0, 'err_code' => 0,  'msg' => 'success']);
        }
        return json(['status' => -900, 'err_code' => -900,  'msg' => '邮件服务器错误']);
        
    }

    public function resetpassword()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'type'  =>  'require',
            'user'  =>  'require',
            'password'  =>  'require',
            'password2'  =>  'require',
            'code' =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['password'] != $data['password2']) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '两次密码不一致']);
        }
        if ($data['type'] == 'phone'){
            $check = $this->checkcode($data['user'], $data['code']);
        } else {
            $check = $this->checkemail($data['user'], $data['code']);
        }
        if ($check == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码未发送']);
        }
        if ($check == -1) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码不正确']);
        }
        $update = [
            "password"
        ];
        $insert = [
            md5($data['password'])
        ];
        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => $data['type']." = '".$data['user']."'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function getuser()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "id = ".intval($data['id']),
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (!isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该用户不存在']);
        }
        return json(['status' => 0, 'msg' => '查询成功', 'data' => $return_data['result']['cols'][0]]);

    }

    public function userlist()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $where = "1";
        $where.= $data['search']=="" ? "" : " and (username = '".$data['search']."' or phone = '".$data['search']."' or email = '".$data['search']."' or id = ".intval($data['search']).")";
        $where.= $data['status']===null ? "" : " and status = ".$data['status'];
        if (isset($data['start_ts'])) {
            $where.= $data['start_ts']=="" ? "" : " and time_create >= ".$data['start_ts'];
        }
        if (isset($data['end_ts'])) {
            $where.= $data['end_ts']=="" ? "" : " and time_create <= ".$data['end_ts'];
        }

        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => $order,
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $datalist = $return_data['result']['cols'];
        $chanid = [];
        for ($i=0;$i<count($datalist);$i++) {
            $chanid[] =  $datalist[$i]['id']."";
        }
        $param = array(
            "chanId" => $chanid,
        );
        $rs = self::testApiData("channel_details/query_total_dataflow", $param);
        if (!$rs) {
            return json(['status' => -5, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        $rs = json_decode($rs, true);


        if ($rs['status'] != 0) {
            return json($rs);
        }
        for($i = 0; $i<count($rs['data']['tableList']);$i++) {
            for ($j=0;$j<count($datalist);$j++) {
                if($datalist[$j]['id'] == $rs['data']['tableList'][$i]['chanId']) {
                    $datalist[$j]['dataFlow'] = $rs['data']['tableList'][$i]['dataFlow'];
                }
            }
        }
        $return_data['result']['cols'] = $datalist;
        return json($return_data);

    }

    public function denyuser()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'status' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $update = [
            "status"
        ];
        $insert = [
            $data['status']
        ];
        
        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id in (".implode(",", $data['id']).")",
        );
        return self::loadApiData("store/update_table", $param);
    }

    private function sendemail($email, $code) 
    {
        //发送短信
        Cache::store('redis')->set('email_verify:'.$email, $code, 5 * 60);

        return true;
    } 

    public function getemail()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'email'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $code = 123456;
        if ($this->sendemail($data['email'], $code)){
            return json(['status' => 0, 'err_code' => 0,  'msg' => 'success']);
        }
        return json(['status' => -900, 'err_code' => -900,  'msg' => '邮件服务器错误']);
    }

    private function checkemail($email, $code)
    {
        if (!Cache::store('redis')->has('email_verify:'.$email)) {
            return 0;
        }
        $truecode = Cache::store('redis')->get('email_verify:'.$email);
        if ($truecode != $code) {
            return -1;
        }
        return 1;
    }

    public function editemail()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'email' => 'require',
            'code' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $check = $this->checkemail($data['email'], $data['code']);
        if ($check == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码未发送']);
        }
        if ($check == -1) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码不正确']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "email='".$data['email']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该邮箱已存在']);
        }

        $update = [
            "email"
        ];
        $insert = [
            $data['email']
        ];

        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id='".$data['id']."'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function editphone()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'phone' => 'require',
            'code' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $check = $this->checkcode($data['phone'], $data['code']);
        if ($check == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码未发送']);
        }
        if ($check == -1) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '验证码不正确']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "phone='".$data['phone']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该手机号已存在']);
        }

        $update = [
            "phone"
        ];
        $insert = [
            $data['phone']
        ];

        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id='".$data['id']."'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function editusername()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'username' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "username='".$data['username']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该用户名已存在']);
        }

        $update = [
            "username"
        ];
        $insert = [
            $data['username']
        ];

        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id='".$data['id']."'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function resecret()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "id='".$data['id']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }

        $secret = md5($this->randStr(6));
        $update = [
            "secret"
        ];
        $insert = [
            $secret
        ];

        $param = array(
            "tb_name"   => 'cloud_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id='".$data['id']."'",
        );
        $return_data = self::loadApiData("store/update_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        return json(['status' => 0, 'err_code' => 0, 'msg' => 'success', 'data' => $secret]);
    }

    public function checktoken()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'token' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if (!Cache::store('redis')->has('cloud_user_token:'. $data['id'])){
            return json(['status' => -900, 'err_code' => -900, 'msg' =>'token不存在']);
        }
        if (Cache::store('redis')->get('cloud_user_token:'. $data['id'])!=$data['token']) {
            return json(['status' => -900, 'err_code' => -900, 'msg' =>'该用户已在其他地方登陆']);
        }
        return json(['status' => 0, 'err_code' => 0, 'msg' =>'ok']);

    }

    public function checkuser()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "id='".$data['id']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => 1, 'err_code' => 0, 'msg' => '该渠道ID存在']);
        }
        return json(['status' => 0, 'err_code' => 0, 'msg' => '该渠道ID不存在']);
    }

    public function test()
    {
        if(isset($_COOKIE['user'])){
            self::actionLog("冻结账号", "被冻结用户ID：1", $_COOKIE['user']);
        };exit;
        //return self::actionLog("冻结账号","被冻结用户ID：1","test");
    }

}
