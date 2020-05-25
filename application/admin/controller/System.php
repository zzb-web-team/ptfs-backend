<?php
namespace app\admin\controller;

use PHPGangsta_GoogleAuthenticator;
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
            'username' => 'require',
            'password' => 'require',
        ]);
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => "username='" . $data['username'] . "'",
            "order" => 'id desc',
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
                    return json(['status' => -900, 'msg' => '密码错误']);
                }
                if ($user['status'] == 1) {
                    return json(['status' => -900, 'msg' => '用户已被禁用']);
                }
                unset($user['password']);
                $list = Cache::store('redis')->get('ipfs:ip:list', json_encode([]));
                $list = json_decode($list, true);
                $result = self::judge_password($data['password']);
                if (!empty($list)) {
                    $column = array_column($list, 'ip');
                    $ip = self::getIp();
                    $res = array_search($ip, $column);
                    // if (!$res) {
                    //     return json(['status' => -900, 'msg' => '非法ip']);
                    // }
                } else if (Cache::store('redis')->get('ipfs:admin_status:' . $user['id'])) {
                    $token = md5(time().'_jkhgasdgjkdsa');
                    Cache::store('redis')->set('ipfs:admin_token:' . $token, $user['id'], 60);
                    return json(['status' => 1, 'token' => $token, 'msg' => '请输入code','passlv' => $result['lv'], 'lvmsg' => $result['lvmsg']]);
                } else {
                    return json(['status' => 0, 'msg' => $user, 'passlv' => $result['lv'], 'lvmsg' => $result['lvmsg']]);
                }
            }
            return json(['status' => -900, 'msg' => '找不到该用户']);
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
        $id = Cache::store('redis')->get('ipfs:admin_token:'.$data['token']);
        if(empty($id)){
            return json(['status' => -900, 'msg'=> 'token无效']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
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

        $secret = Cache::store('redis')->get('ipfs:admin_login:' . $data['id']);
        $google_auth = new \PHPGangsta_GoogleAuthenticator();
        $code = $google_auth->getCode($secret);
        $check_result = $google_auth->verifyCode($secret, $data['code'], 2);
        if ($check_result) {
            return json(['status' => 0,'usermsg' => $user, 'msg' => '登陆成功']);
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
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => "id=" . $data['id'],
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $user = $return_data['result']['cols'][0];
        $google_auth = new \PHPGangsta_GoogleAuthenticator();
        if (!isset($data['code'])) {
            //获取二维码
            if (Cache::store('redis')->has('ipfs:admin_login:' . $data['id'])) {
                //已开通
                $secret = Cache::store('redis')->get('ipfs:admin_login:' . $data['id']);
            } else {
                //未开通二次验证
                $secret = $google_auth->createSecret();
                Cache::store('redis')->set('ipfs:admin_login:' . $data['id'], $secret);
            }
            writelog("id:" . $user['id'] . "username:" . $user['username'], 'admin/system/bind_login', $secret, $type = "POST"); //详细信息日志
            $qrcode_url = $google_auth->getQRCodeGoogleUrl($data['id'], $secret);
            return json([
                'satus' => 0,
                'msg' => [
                    'url' => $qrcode_url,
                    'secret' => $secret,
                ]]);
        } else {
            $secret = Cache::store('redis')->get('ipfs:admin_login:' . $data['id']);
            $check_result = $google_auth->verifyCode($secret, $data['code'], 2);
            if ($check_result) {
                Cache::store('redis')->set('ipfs:admin_status:' . $data['id'], 1);
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
            'page' => 'require',
        ]);
        $where = "1";
        $where .= $data['search'] == "" ? "" : " and (username = '" . $data['search'] . "' or nickname = '" . $data['search'] . "')";
        $where .= $data['status'] === null ? "" : " and status = " . $data['status'];
        if (isset($data['start_ts'])) {
            $where .= $data['start_ts'] == "" ? "" : " and time_create >= " . $data['start_ts'];
        }
        if (isset($data['end_ts'])) {
            $where .= $data['end_ts'] == "" ? "" : " and time_create <= " . $data['end_ts'];
        }

        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => $where,
            "order" => $order,
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function userctrl()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'ids' => 'require',
            'type' => 'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if (in_array("1", explode(",", $data['ids']))) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '无法对系统超级管理员admin进行操作']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => "id='" . $data['uid'] . "'",
            "order" => 'id desc',
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
        if ($data['type'] == 1 || $data['type'] == 0) {
            $uid = $data['uid'];
            $uname = $data['uname'];
            $update = [
                "status", "uid_update", "user_update",
            ];
            $insert = [
                $data['type'], intval($uid), $uname,
            ];

            $param = array(
                "tb_name" => 'system_user',
                "update" => $update,
                "col_value" => $insert,
                "where" => "id in (" . $data['ids'] . ")",
            );
            $result = self::loadApiData("store/update_table", $param);
        } else {
            $param = array(
                "tb_name" => 'system_user',
                "where" => "id in (" . $data['ids'] . ")",
            );
            $result = self::loadApiData("store/delete_record", $param);
        }
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        // $uid = isset($_COOKIE['id']) ? $_COOKIE['id'] : 0;
        // $uname = isset($_COOKIE['user']) ? $_COOKIE['user'] : "本地测试用户";
        // if ($data['type'] == 1 ) {
        //     self::actionLog("修改", "禁用系统用户", $data['ids'], "admin", $uid, $uname);
        // } else if ($data['type'] == 0) {
        //     self::actionLog("修改", "启用系统用户", $data['ids'], "admin", $uid, $uname);
        // } else {
        //     self::actionLog("删除", "删除系统用户", $data['ids'], "admin", $uid, $uname);
        // }

        return json($result);

    }

    public function userdelete()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许删除超级管理员']);
        }
        $param = array(
            "tb_name" => 'system_user',
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/delete_record", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        // $uid = isset($_COOKIE['id']) ? $_COOKIE['id'] : 0;
        // $uname = isset($_COOKIE['user']) ? $_COOKIE['user'] : "本地测试用户";
        // self::actionLog("删除", "删除系统用户", $data['id'], "admin", $uid, $uname);
        return json($result);
    }

    public function userinsert()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'username' => 'require',
            'nickname' => 'require',
            'password' => 'require',
            'password2' => 'require',
            'role_id' => 'require',
            'name' => 'require',
            'phone' => 'require',
            'status' => 'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['password'] != $data['password2']) {
            return json(['status' => -900, 'msg' => '两次密码不一致']);
        }
        $insert = array();
        //$uid = isset($_COOKIE['id']) ? $_COOKIE['id'] : 0;
        //$uname = isset($_COOKIE['user']) ? $_COOKIE['user'] : "本地测试用户";
        $uid = $data['uid'];
        $uname = $data['uname'];
        $insert[] = [
            $data['username'],
            $data['nickname'],
            md5($data['password']),
            $data['role_id'],
            $data['name'],
            $data['phone'],
            $data['status'],
            intval($uid),
            $uname,
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name" => 'system_user',
            "insert" => $insert,
        );
        $result = self::loadApiData("store/insert_table", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }

        //self::actionLog("新增", "新增用户", "-", "admin", $uid, $uname);
        return json($result);
    }

    public function userupdate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
            'username' => 'require',
            'role_id' => 'require',
            'name' => 'require',
            'phone' => 'require',
            'status' => 'require',
            'uid' => 'require',
            'uname' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许操作超级管理员']);
        }
        //$uid = isset($_COOKIE['ipfs_id']) ? $_COOKIE['ipfs_id'] : (isset($_COOKIE['adminid']) ? $_COOKIE['adminid'] : 0);
        //$uname = isset($_COOKIE['ipfs_user']) ? $_COOKIE['ipfs_user'] : (isset($_COOKIE['adminuser']) ? $_COOKIE['adminuser'] : "-");
        $uid = $data['uid'];
        $uname = $data['uname'];
        if ($data['password']) {
            if ($data['password'] != $data['password2']) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '两次密码不一致']);
            }
            $update = [
                "username", "nickname", "password", "role_id", "name", "phone", "status", "uid_update", "user_update",
            ];
            $insert = [
                $data['username'],
                $data['nickname'],
                md5($data['password']),
                $data['role_id'],
                $data['name'],
                $data['phone'],
                $data['status'],
                intval($uid),
                $uname,
            ];

        } else {
            $update = [
                "username", "nickname", "role_id", "name", "phone", "status", "uid_update", "user_update",
            ];
            $insert = [
                $data['username'],
                $data['nickname'],
                $data['role_id'],
                $data['name'],
                $data['phone'],
                $data['status'],
                intval($uid),
                $uname,
            ];
        }

        $param = array(
            "tb_name" => 'system_user',
            "update" => $update,
            "col_value" => $insert,
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/update_table", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }

        //self::actionLog("修改", "修改用户", $data['id'], "admin", $uid, $uname);
        return json($result);
    }

    public function menulist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page' => 'require',
        ]);

        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'system_menu',
            "col_name" => "*",
            "where" => "",
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    private function getTree($data, $pId)
    {
        $tree = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {
                $v['children'] = $this->getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    public function menulistfortree()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => ["role_id"],
            "where" => "id=" . intval($data['id']),
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        if (!isset($result['result']['cols'][0])) {
            return json(['status' => -900, 'msg' => '未找到该用户']);
        }
        $role_id = $result['result']['cols'][0]['role_id'];
        if (!$role_id) {
            $data = array();
            $loop = true;
            $page = 0;
            while ($loop) {
                $param = array(
                    "page" => $page,
                    "page_size" => 10,
                    "tb_name" => 'system_menu',
                    "col_name" => "*",
                    "where" => "",
                    "order" => 'id asc',
                );
                $result = self::loadApiData("store/find_table", $param);
                $result = json_decode($result, true);

                if ($result['status'] != 0) {
                    return json($result);
                }
                if (isset($result['result']['cols'])) {
                    $list = $result['result']['cols'];
                    for ($i = 0; $i < count($list); $i++) {
                        $data[] = $list[$i];
                    }
                }
                if ($result['result']['les_count']) {
                    $page++;
                } else {
                    $loop = false;
                }

            }
            $menu = $this->getTree($data, 0);
            return json(['status' => 0, 'msg' => $menu]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_role',
            "col_name" => ["role"],
            "where" => "id=" . $role_id,
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        if (!isset($result['result']['cols'][0])) {
            return json(['status' => -900, 'msg' => '未找到该用户']);
        }
        $role = $result['result']['cols'][0]['role'];
        if (!$role) {
            return json(['status' => 0, 'msg' => []]);
        }
        $data = array();
        $loop = true;
        $page = 0;
        while ($loop) {
            $param = array(
                "page" => $page,
                "page_size" => 10,
                "tb_name" => 'system_menu',
                "col_name" => "*",
                "where" => "id in (" . $role . ")",
                "order" => 'id asc',
            );
            $result = self::loadApiData("store/find_table", $param);
            $result = json_decode($result, true);

            if ($result['status'] != 0) {
                return json($result);
            }
            if (isset($result['result']['cols'])) {
                $list = $result['result']['cols'];
                for ($i = 0; $i < count($list); $i++) {
                    $data[] = $list[$i];
                }
            }
            if ($result['result']['les_count']) {
                $page++;
            } else {
                $loop = false;
            }

        }
        $menu = $this->getTree($data, 0);
        return json(['status' => 0, 'msg' => $menu]);

    }

    public function menulistfortop()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'pid' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $pid = intval($data['pid']);
        $data = array();
        $loop = true;
        $page = 0;
        while ($loop) {
            $param = array(
                "page" => $page,
                "page_size" => 10,
                "tb_name" => 'system_menu',
                "col_name" => "*",
                "where" => "pid=" . $pid,
                "order" => 'id desc',
            );
            $result = self::loadApiData("store/find_table", $param);
            $result = json_decode($result, true);

            if ($result['status'] != 0) {
                return json($result);
            }
            if (isset($result['result']['cols'])) {
                $list = $result['result']['cols'];
                for ($i = 0; $i < count($list); $i++) {
                    $data[] = $list[$i];
                }
            }
            if ($result['result']['les_count']) {
                $page++;
            } else {
                $loop = false;
            }

        }
        return json(['status' => 0, 'msg' => $data]);

    }

    public function menudelete()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许删除超级管理员']);
        }
        $param = array(
            "tb_name" => 'system_menu',
            "where" => "id='" . $data['id'] . "'",
        );
        return self::loadApiData("store/delete_record", $param);
    }

    public function menuinsert()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'pid' => 'require',
            'path' => 'require',
            'component' => 'require',
            'name' => 'require',
            'icon' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = array();

        $insert[] = [
            $data['pid'],
            $data['path'],
            $data['component'],
            $data['name'],
            $data['icon'],
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name" => 'system_menu',
            "insert" => $insert,
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function menuupdate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
            'pid' => 'require',
            'path' => 'require',
            'component' => 'require',
            'name' => 'require',
            'icon' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = array();

        $insert = [
            $data['pid'],
            $data['path'],
            $data['component'],
            $data['name'],
            $data['icon'],
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }

        $param = array(
            "tb_name" => 'system_menu',
            "update" => ["pid", "path", "component", "name", "icon"],
            "col_value" => $insert,
            "where" => "id='" . intval($data['id']) . "'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function rolelist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page' => 'require',
        ]);

        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'system_role',
            "col_name" => "*",
            "where" => "",
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function rolelistfortop()
    {
        $data = array();
        $loop = true;
        $page = 0;
        while ($loop) {
            $param = array(
                "page" => $page,
                "page_size" => 10,
                "tb_name" => 'system_role',
                "col_name" => "*",
                "where" => "",
                "order" => 'id desc',
            );
            $result = self::loadApiData("store/find_table", $param);
            $result = json_decode($result, true);

            if ($result['status'] != 0) {
                return json($result);
            }
            if (isset($result['result']['cols'])) {
                $list = $result['result']['cols'];
                for ($i = 0; $i < count($list); $i++) {
                    $data[] = $list[$i];
                }
            }
            if ($result['result']['les_count']) {
                $page++;
            } else {
                $loop = false;
            }

        }
        return json(['status' => 0, 'msg' => $data]);

    }

    public function getrolebyid()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'system_role',
            "col_name" => "*",
            "where" => "id=" . intval($data['id']),
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        if (!isset($result['result']['cols'][0])) {
            return json(['status' => -900, 'msg' => '未找到该用户']);
        }
        return json(['status' => 0, 'msg' => $result['result']['cols'][0]]);
    }

    public function roledelete()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['id'] == 1) {
            return json(['status' => -900, 'msg' => '不允许删除超级管理员']);
        }
        $param = array(
            "tb_name" => 'system_role',
            "where" => "id='" . $data['id'] . "'",
        );
        return self::loadApiData("store/delete_record", $param);
    }

    public function roleinsert()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'name' => 'require',
            'type' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = array();

        $insert[] = [
            $data['name'],
            $data['type'],
            $data['role'],
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name" => 'system_role',
            "insert" => $insert,
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function roleedit()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
            'name' => 'require',
            'type' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = array();

        $insert = [
            $data['name'],
            $data['type'],
            $data['role'],
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }

        $param = array(
            "tb_name" => 'system_role',
            "update" => ["name", "type", "role"],
            "col_value" => $insert,
            "where" => "id='" . intval($data['id']) . "'",
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function iplist()
    {
        $data = input('post.');
        //表单验证规则
        $page = isset($data['page']) ? $data['page'] : 1;
        $size = isset($data['size']) ? $data['size'] : 10;
        $list = Cache::store('redis')->get('ipfs:ip:list', json_encode([]));
        $list = json_decode($list, true);
        $return_data = [];
        $rowindex = ($page - 1) * $size;
        for ($i = $rowindex; $i < $size; $i++) {
            if (!isset($list[$i])) {
                continue;
            }
            $return_data[] = $list[$i];
        }
        return json(['status' => 0, 'err_code' => 0, 'err_msg' => 'success', 'page' => $page, 'size' => $size, 'data' => $return_data, 'total' => count($list)]);
    }

    public function addip()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'ip' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $list = Cache::store('redis')->get('ipfs:ip:list', json_encode([]));
        $list = json_decode($list, true);
        $column = array_column($list, 'ip');
        $res = array_search($data['ip'], $column);
        if ($res === false) {
            $id = Cache::store('redis')->get('ipfs:ip:id', 0) + 1;
            Cache::store('redis')->set('ipfs:ip:id', $id);
            $list[] = [
                "id" => $id,
                "ip" => $data['ip'],
            ];
            Cache::store('redis')->set('ipfs:ip:list', json_encode($list));
            return json(['status' => 0, 'err_code' => 0, 'err_msg' => 'success']);
        }
        return json(['status' => -900, 'err_code' => -900, 'msg' => '该ip已经添加过了']);

    }

    public function editip()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
            'ip' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $list = Cache::store('redis')->get('ipfs:ip:list', json_encode([]));
        $list = json_decode($list, true);
        $column = array_column($list, 'ip');
        $res = array_search($data['ip'], $column);
        if ($res === false) {
            $column = array_column($list, 'id');
            $res = array_search($data['id'], $column);
            if ($res === false) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '该id不存在']);
            }
            $info = [
                "id" => $data['id'],
                "ip" => $data['ip'],
            ];
            $list[$res] = $info;
            Cache::store('redis')->set('ipfs:ip:list', json_encode($list));
            return json(['status' => 0, 'err_code' => 0, 'err_msg' => 'success']);
        }
        if ($list[$res]['id'] != $data['id']) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该ip已存在']);
        }
        $column = array_column($list, 'id');
        $res = array_search($data['id'], $column);
        if ($res === false) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该id不存在']);
        }
        $info = [
            "id" => $data['id'],
            "ip" => $data['ip'],
        ];
        $list[$res] = $info;
        Cache::store('redis')->set('ipfs:ip:list', json_encode($list));
        return json(['status' => 0, 'err_code' => 0, 'err_msg' => 'success']);

    }

    public function deleteip()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $list = Cache::store('redis')->get('ipfs:ip:list', json_encode([]));
        $list = json_decode($list, true);
        $column = array_column($list, 'id');
        $res = array_search($data['id'], $column);
        if ($res === false) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该id不存在']);
        }
        unset($list[$res]);
        Cache::store('redis')->set('ipfs:ip:list', json_encode($list));
        return json(['status' => 0, 'err_code' => 0, 'err_msg' => 'success']);

    }

    public function qrcode(){
        $auth = new \PHPGangsta_GoogleAuthenticator();
        $secret = $auth->createSecret();
        $qrcode = $auth->getQRCodeGoogleUrl('ipfs',$secret);
        $qrcode = urldecode($qrcode);
        return json(['secret'=>$secret,'qrcode'=>$qrcode]);
    }

}
