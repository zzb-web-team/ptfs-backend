<?php
namespace app\cloud\controller;

use think\facade\Cache;
use think\Validate;

require '../extend/PHPGangsta/GoogleAuthenticator.php';

class System extends Common
{ 

    public function login()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'username'  =>  'require',
            'password'  =>  'require',
        ]);

        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_system_user',
            "col_name"  => "*",
            "where"     => "username='".$data['username']."'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            if (isset($return_data['result']['cols'][0])) {
                $user = $return_data['result']['cols'][0];
                if (md5($data['password']) != $user['password']) {
                    return json(['status' => -900, 'msg' => '账户名或密码错误']);
                }
                if ($user['status'] == 1) {
                    return json(['status' => -900, 'msg' => '用户已被禁用']);
                }
                unset($user['password']);
                $list = Cache::store('redis') -> get('ipfs:ip:list', json_encode([]));
                $list = json_decode($list, true);
                $result = self::judge_password($data['password']);
                if (!empty($list)) {
                    $column = array_column($list, 'ip');
                    $ip = self::getIp();
                    $res = array_search($ip, $column);
                    if (!$res) {
                        return json(['status' => -900, 'msg' => '非法ip']);
                    } else if (Cache::store('redis')->get('cloud:admin_status:' . $user['id'])) {
                        $token = md5(time() . '_jkhgasdgjkdsa');
                        Cache::store('redis')->set('cloud:admin_token:' . $token, $user['id'], 60);
                        return json(['status' => 1, 'token' => $token, 'msg' => '请输入code', 'passlv' => $result['lv'], 'lvmsg' => $result['lvmsg']]);
                    } else {
                        return json(['status' => 0, 'msg' => $user, 'passlv' => $result['lv'], 'lvmsg' => $result['lvmsg'], 'google' => 0]);
                    }
                } else if (Cache::store('redis')->get('cloud:admin_status:' . $user['id'])) {
                    $token = md5(time() . '_jkhgasdgjkdsa');
                    Cache::store('redis')->set('cloud:admin_token:' . $token, $user['id'], 60);
                    return json(['status' => 1, 'token' => $token, 'msg' => '请输入code', 'passlv' => $result['lv'], 'lvmsg' => $result['lvmsg']]);
                } else {
                    return json(['status' => 0, 'msg' => $user, 'passlv' => $result['lv'], 'lvmsg' => $result['lvmsg'], 'google' => 0]);
                }
            }
            return json(['status' => -900, 'msg' => '账户名或密码错误']);
        }
        return json($return_data);
    }

    //用户输入账号密码且校验通过后验证code
    public function check_login()
    {
        $data = input('post.');
        $validation = new Validate([
            'token' => 'require',
            'code' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $id = Cache::store('redis')->get('cloud:admin_token:' . $data['token']);
        if (empty($id)) {
            return json(['status' => -900, 'msg' => 'token无效']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'cloud_system_user',
            "col_name" => "*",
            "where" => "id=" . $id,
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        $user = $return_data['result']['cols'][0];

        $secret = Cache::store('redis')->get('cloud:admin_login:' . $id);
        $google_auth = new \PHPGangsta_GoogleAuthenticator();
        $code = $google_auth->getCode($secret);
        $check_result = $google_auth->verifyCode($secret, $data['code'], 2);
        if ($check_result) {
            return json(['status' => 0, 'usermsg' => $user, 'msg' => '登陆成功', 'google' => 1]);
        } else {
            return json(['status' => -900, 'msg' => 'code错误']);
        }
    }

     //用户第一次开通二次验证流程
    //status 0未开通 1已开通
    public function bind_login()
    {
        $data = input('post.');
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'cloud_system_user',
            "col_name" => "*",
            "where" => "id=" . $data['id'],
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if(!isset($return_data['result']['cols'][0])){
            return json(['status' => -900, 'msg' => '没有该用户']);
        }
        $user = $return_data['result']['cols'][0];
        $google_auth = new \PHPGangsta_GoogleAuthenticator();
        if (!isset($data['code'])) {
            //获取二维码
            if (Cache::store('redis')->has('cloud:admin_login:' . $data['id'])) {
                //已开通
                $secret = Cache::store('redis')->get('cloud:admin_login:' . $data['id']);
            } else {
                //未开通二次验证
                $secret = $google_auth->createSecret();
                Cache::store('redis')->set('cloud:admin_login:' . $data['id'], $secret);
            }
            writelog("id:" . $user['id'] . "username:" . $user['username'], 'admin/system/bind_login', $secret, $type = "POST"); //详细信息日志
            $qrcode_url = $google_auth->getQRCodeGoogleUrl($data['id'], $secret);
            return json([
                'status' => 0,
                'msg' => [
                    'url' => $qrcode_url,
                    'secret' => $secret,
                ]]);
        } else {
            $secret = Cache::store('redis')->get('cloud:admin_login:' . $data['id']);
            $check_result = $google_auth->verifyCode($secret, $data['code'], 2);
            if ($check_result) {
                Cache::store('redis')->set('cloud:admin_status:' . $data['id'], 1);
                return json(['status' => 0, 'msg' => '绑定成功']);
            } else {
                return json(['status' => -900, 'msg' => '绑定失败']);
            }
        }
    }

    public function userlist()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page'  =>  'require',
        ]);
        $where = "1";
        $where.= $data['search']=="" ? "" : " and username = '".$data['search']."'";
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
            "tb_name"   => 'cloud_system_user',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => $order,
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function userctrl()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'ids'  =>  'require',
            'type' => 'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if (in_array("1", explode(",", $data['ids']))) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => '无法对系统超级管理员admin进行操作']);
        }
        //if (isset($_COOKIE['adminid'])) {
            //查询用户当前状态
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"   => 'cloud_system_user',
                "col_name"  => "*",
                "where"     => "id='".$data['uid']."'",
                "order"     => 'id desc',
            );
            $return_data = self::loadApiData("store/find_table", $param);
            if (!$return_data) {
                return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
            }
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] != 0) {
                return json($return_data);
            }
            if (isset($return_data['result']['cols'][0])) {
                $user = $return_data['result']['cols'][0];
                if ($user['status'] == 1) {
                    return json(['status' => -900, 'msg' => '您已被禁用，无法操作']);
                }
            }
            
        //}
        if ($data['type'] == 1 || $data['type'] == 0) {
            //$uid = isset($_COOKIE['adminid']) ? $_COOKIE['adminid'] : 0;
            //$uname = isset($_COOKIE['adminuser']) ? $_COOKIE['adminuser'] : "本地测试用户";
            $uid = $data['uid'];
            $uname = $data['uname'];
            $update = [
                "status", "uid_update", "user_update"
            ];
            $insert = [
                $data['type'], intval($uid), $uname
            ];

            $param = array(
                "tb_name"   => 'cloud_system_user',
                "update"    => $update,
                "col_value" => $insert,
                "where" => "id in (".$data['ids'].") and id <> 1",
            );
            return self::loadApiData("store/update_table", $param);
        } else {
            $param = array(
                "tb_name"   => 'cloud_system_user',
                "where"     => "id in (".$data['ids'].") and id <> 1",
            );
            return self::loadApiData("store/delete_record", $param);
        }

    }

    public function userdelete()
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
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许删除超级管理员']);
        }
        $param = array(
            "tb_name"   => 'cloud_system_user',
            "where"     => "id='".$data['id']."' and role_id <> 1",
        );
        return self::loadApiData("store/delete_record", $param);
    }

    public function userinsert()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'username'  =>  'require',
            'password'  =>  'require',
            'password2'  =>  'require',
            'role_id' =>  'require',
            'name' =>  'require',
            'phone' =>  'require',
            'status' =>  'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['password'] != $data['password2']) {
            return json(['status' => -900, 'msg' => '两次密码不一致']);
        }
        $insert = array();
        //$uid = isset($_COOKIE['adminid']) ? $_COOKIE['adminid'] : 0;
        //$uname = isset($_COOKIE['adminuser']) ? $_COOKIE['adminuser'] : "本地测试用户";
        $uid = $data['uid'];
        $uname = $data['uname'];
        $insert[] = [
            $data['username'],
            md5($data['password']),
            $data['role_id'],
            $data['name'],
            $data['phone'],
            $data['status'],
            intval($uid),
            $uname
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'cloud_system_user',
            "insert"    => $insert
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function userupdate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'username' => 'require',
            'role_id' => 'require',
            'name' =>  'require',
            'phone' =>  'require',
            'status' =>  'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许操作超级管理员']);
        }
        //$uid = isset($_COOKIE['adminid']) ? $_COOKIE['adminid'] : 0;
        //$uname = isset($_COOKIE['adminuser']) ? $_COOKIE['adminuser'] : "本地测试用户";
        $uid = $data['uid'];
        $uname = $data['uname'];

        if($data['password']) {
            if ($data['password'] != $data['password2']) {
                 return json(['status' => -900, 'err_code' => -900,  'msg' => '两次密码不一致']);
            }
           
            $update = [
                "username",  "password", "role_id", "name" , "phone", "status", "uid_update", "user_update"
            ];

            $insert = [
                $data['username'],
                md5($data['password']),
                $data['role_id'],
                $data['name'],
                $data['phone'],
                $data['status'],
                intval($uid),
                $uname
            ];

        } else {
            $update = [
                "username",  "role_id", "name" , "phone", "status", "uid_update", "user_update"
            ];
            $insert = [
                $data['username'],
                $data['role_id'],
                $data['name'],
                $data['phone'],
                $data['status'],
                intval($uid),
                $uname
            ];
        }

        $param = array(
            "tb_name"   => 'cloud_system_user',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id='".$data['id']."'",
        );
        return self::loadApiData("store/update_table", $param);
    }

}
