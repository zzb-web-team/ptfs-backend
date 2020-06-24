<?php
namespace app\api\controller;

use think\Validate;

class System extends Common
{
    //用户列表
    public function user_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'page' => 'require',
        ]);
        $where = '1';
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => $where,
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $return_data;
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
        return json($result);
    }

    //新增用户
    public function add_user()
    {
        $data = input('post.');
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
        $insert = [];
        $insert[] = [
            $data['username'],
            $data['nickname'],
            md5($data['password']),
            intval($data['role_id']),
            $data['name'],
            $data['phone'],
            $data['status'],
            intval($data['uid']),
            $data['uname'],
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
        return json($result);
    }

    //删除用户
    public function del_user()
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
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    //修改用户
    public function update_user()
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
        $uid = $data['uid'];
        $uname = $data['uname'];
        if (isset($data['password'])) {
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

    public function search_user()
    {
        $data = input('post.');
        $validation = new Validate([
            'username' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'system_user',
            "col_name" => "*",
            "where" => "username = '" . $data['username'] . "'",
            "order" => $order, 
        );
        $result = self::loadApiData("store/find_table", $param);
        if(!$result){
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $resultarr = json_decode($result,true);
        if($resultarr['status']!=0){
            return json(['status' => 1,'msg' => '查询出错']);
        }
        return $result;
    }

    //部门列表
    public function department_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'page' => 'require',
        ]);
        $where = '1';
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_department',
            "col_name" => "*",
            "where" => $where,
            "order" => $order,
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        $department = $result['result']['cols'];
        return json(['status' => 0,'msg' => parent::getTree($department, 0)]);
    }

    //新增部门
    public function add_department()
    {
        $data = input('post.');
        $validation = new Validate([
            'pid' => 'require',
            'name' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert[] = [
            $data['pid'],
            $data['name'],
        ];
        $param = array(
            "tb_name" => 'ipfs_department',
            "insert" => $insert,
        );
        $result = self::loadApiData("store/insert_table", $param);
        if(!$result){
            return json(['status' => -900,'msg' => '服务器可能开小差去了']);
        }
        return $result;

    }

    //部门修改
    public function update_department()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'pid' => 'require',
            'name' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $update = ['pid', 'name'];
        $insert = [$data['pid'], $data['name']];
        $param = array(
            "tb_name" => 'ipfs_department',
            "update" => $update,
            "col_value" => $insert,
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/update_table", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    public function del_department()
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
            "tb_name" => 'ipfs_department',
            "col_name" => "*",
            "where" => "pid=" . $data['id'],
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        $result = json_decode($result, true);
        if ($result['result']['cols']) {
            return json(['status' => 1, 'msg' => '该部门下有二级部门,禁止删除']);
        }
        $param = array(
            "tb_name" => 'ipfs_department',
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/delete_record", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    public function position_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'page' => 'require',
        ]);
        $where = '1';
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_position',
            "col_name" => "*",
            "where" => $where,
            "order" => $order,
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $result;
    }

    public function add_position()
    {
        $data = input('post.');
        $validation = new Validate([
            'name' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = [];
        $insert[] = [$data['name']];
        $param = array(
            "tb_name" => 'ipfs_position',
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
        return json($result);
    }

    public function update_position()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'name' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $update = ['name'];
        $insert = [$data['name']];
        $param = array(
            "tb_name" => 'ipfs_position',
            "update" => $update,
            "col_value" => $insert,
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/update_table", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    public function del_position()
    {
        $data = input('post.');
        $validation = new Validate([
            'ids' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        foreach($data['ids'] as $k=>$v){
            $param = [
                "tb_name" => 'ipfs_position',
                "where" => "id=" . $v['id'],
            ];
            $result = self::loadApiData("store/delete_record", $param);
            echo $k;
            if (!$result) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
            }
            $result = json_decode($result, true);
            if ($result['status'] != 0) {
                return json($result);
            }
        }
        return json($result);
    }

    //返回所有菜单信息
    public function menu_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'page' => 'require',
        ]);
        $where = '1';
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_menu',
            "col_name" => "*",
            "where" => $where,
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($return_data, true);
        $menu = $result['result']['cols'];
        $result = parent::getTree($menu, 0);
        return json(['status' => 0, 'msg' => $result]);
    }

    //根据用户信息返回菜单信息
    public function menu_list_user()
    {
        $data = input('post.');
        $validation = new Validate([
            'roleid' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        // $param = array(
        //     "page" => isset($data['page']) ? intval($data['page']) : 0,
        //     "page_size" => 10,
        //     "tb_name" => 'system_user',
        //     "col_name" => "*",
        //     "where" => "id=" . $data['userid'],
        //     "order" => 'id desc',
        // );
        // $return_data = self::loadApiData("store/find_table", $param);
        // $return_data = json_decode($return_data,true);
        // $roleid = $return_data['result']['cols'][0]['role_id'];
        $param = [
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_roleinfo',
            "col_name" => "*",
            "where" => "roleid=" . $data['roleid'],
            "order" => 'id desc',
        ];
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $menuid = '(';
        foreach ($return_data['result']['cols'] as $k => $v) {
            // $menuid_arr[] = $v['menuid'];
            $menuid .= $v['menuid'] . ",";
        }
        $menuid = substr($menuid, 0, strlen($menuid) - 1);
        $menuid = $menuid . ")";
        $param = [
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_menu',
            "col_name" => "*",
            "where" => "id in " . $menuid,
            "order" => 'id desc',
        ];
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($return_data, true);
        $menu = $result['result']['cols'];
        $result = parent::getTree($menu, 0);
        return json(['status' => 0, 'msg' => $result]);
    }

    public function role_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'page' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $where = '1';
        $param = [
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_role',
            "col_name" => "*",
            "where" => $where,
            "order" => 'id desc',
        ];
        $return_data = self::loadApiData("store/find_table", $param);
        $result = json_decode($return_data,true);
        if(!$result || $result['status']!=0){
            return json(['status' => -900,'msg' => '服务器可能开小差去了']);
        }
        $arr = [];
        foreach($result['result']['cols'] as $k => $v){
            $param = [
                "page" => isset($data['page']) ? intval($data['page']) : 0,
                "page_size" => 10,
                "tb_name" => 'system_user',
                "col_name" => "*",
                "where" => 'role_id = ' . $v['id'],
                "order" => 'id desc',
            ];
            $return_data2 = self::loadApiData("store/find_table", $param);
            $result2 = json_decode($return_data2,true);
            $v['users'] = $result2['result']['cols'];
            $arr[] = $v;
        }
        return json(['status'=>0,'msg'=>$arr]);
    }

    public function add_role()
    {
        $data = input('post.');
        $validation = new Validate([
            'name' => 'require',
            'data' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = [];
        $insert[] = [
            $data['name'],
            1,
            $data['description'],
        ];
        $param = array(
            "tb_name" => 'ipfs_role',
            "insert" => $insert,
        );
        $result = self::loadApiData("store/insert_table", $param);
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_role',
            "col_name" => "*",
            "where" => "name = '" . $data['name'] . "'",
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $result = json_decode($return_data, true);
        $roleid = $result['result']['cols'][0]['id'];
        $insert = [];
        foreach ($data['data'] as $v) {
            $insert[] = [
                $roleid,
                $v['menuid'],
                isset($v['roleC']) ? $v['roleC'] : 0,
                isset($v['roleD']) ? $v['roleD'] : 0,
                isset($v['roleR']) ? $v['roleR'] : 0,
                isset($v['roleU']) ? $v['roleU'] : 0,
                isset($v['roleI']) ? $v['roleI'] : 0,
                isset($v['roleE']) ? $v['roleE'] : 0,
            ];
        }
        $param = array(
            "tb_name" => 'ipfs_roleinfo',
            "insert" => $insert,
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        $result = json_decode($return_data, true);
        if ($result['status'] != 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        return $return_data;
    }

    public function roleinfo()
    {
        $data = input('post.');
        $validation = new Validate([
            'roleid' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_roleinfo',
            "col_name" => "*",
            "where" => "roleid =" . $data['roleid'],
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $result = json_decode($return_data, true);
        if ($result['status'] != 0) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $infolist = $result['result']['cols'];
        return json(['status' => 0, 'msg' => $infolist]);
    }

    public function update_role()
    {
        $data = input('post.');
        $validation = new Validate([
            'roleid' => 'require',
            'data' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = [
            "tb_name" => 'ipfs_roleinfo',
            "where" => "roleid=" . $data['roleid'],
        ];
        $result = self::loadApiData("store/delete_record", $param);
        foreach ($data['data'] as $v) {
            $insert[] = [
                $data['roleid'],
                $v['menuid'],
                isset($v['roleC']) ? $v['roleC'] : 0,
                isset($v['roleD']) ? $v['roleD'] : 0,
                isset($v['roleR']) ? $v['roleR'] : 0,
                isset($v['roleU']) ? $v['roleU'] : 0,
                isset($v['roleI']) ? $v['roleI'] : 0,
                isset($v['roleE']) ? $v['roleE'] : 0,
            ];
        }
        $param = array(
            "tb_name" => 'ipfs_roleinfo',
            "insert" => $insert,
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $return_data;
    }

    public function del_role()
    {
        $data = input('post.');
        $validation = new Validate([
            'roleid' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = [
            "tb_name" => 'ipfs_roleinfo',
            "where" => "roleid=" . $data['roleid'],
        ];
        $result = self::loadApiData("store/delete_record", $param);
        $param = [
            "tb_name" => 'ipfs_role',
            "where" => "id=" . $data['roleid'],
        ];
        $result = self::loadApiData("store/delete_record", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $resultarr = json_decode($result, true);
        if ($resultarr['status'] != 0) {
            return json(['status' => 1, 'msg' => '删除失败']);
        }
        return json($resultarr);
    }

    public function search_role(){
        $data = input('post.');
        $validation = new Validate([
            'name' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_role',
            "col_name" => "*",
            "where" => "name = '" . $data['name'] . "'",
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if(!$return_data){
            return json(['status' => -900,'msg' => '服务器可能开小差去了']);
        }
        return $return_data;
    }

    public function test(){
        echo 123;
    }

}
