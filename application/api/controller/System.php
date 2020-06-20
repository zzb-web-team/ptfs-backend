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
            return json(['status' => -900, 'msg' => '服务器炸了']);
        }

        return $return_data;
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
        if ($result) {
            return json(['status' => 0, 'msg' => "操作成功"]);
        } else {
            return json(['status' => 1, 'msg' => "操作失败"]);
        }
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
            return json(['status' => -900, 'msg' => '服务器炸了']);
        }
        $result = json_decode($result, true);
        $department = $result['result']['cols'];
        return json(parent::getTree($department, 0));
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
            $data['name']
        ];
        $param = array(
            "tb_name" => 'ipfs_department',
            "insert" => $insert,
        );
        return self::loadApiData("store/insert_table", $param);

    }

    //部门修改
    public function update_department(){
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
        $update = ['pid','name'];
        $insert = [$data['pid'],$data['name']];
        $param = array(
            "tb_name" => 'ipfs_department',
            "update" => $update,
            "col_value" => $insert,
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/update_table", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器炸了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    public function del_department(){
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
            "order" => 'id desc'
        );
        $result = self::loadApiData("store/find_table", $param);
        $result = json_decode($result,true);
        if($result['result']['cols']){
            return json(['status'=>1,'msg'=>'该部门下有二级部门,禁止删除']);
        }
        $param = array(
            "tb_name" => 'ipfs_department',
            "where" => "id='" . $data['id'] . "'",
        );
        $result = self::loadApiData("store/delete_record", $param);
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器炸了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json($result);
        }
        return json($result);
    }

    public function position_list(){
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
        if(!$result){
            return json(['status'=>-900,'msg'=>'服务器炸了']);
        }
        return $result;
    }

    public function add_position(){
        $data = input('post.');
        $validation = new Validate([
            'name' => 'require'
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $insert = [];
        $insert[] = ['name'=>$data['name']];
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

   public function update_position(){
    $data = input('post.');
    $validation = new Validate([
        'id' => 'require',
        'name' => 'require'
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
        return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器炸了']);
    }
    $result = json_decode($result, true);
    if ($result['status'] != 0) {
        return json($result);
    }
    return json($result);
   }

   public function del_position(){
    $data = input('post.');
    $validation = new Validate([
        'id' => 'require',
    ]);
    //验证表单
    if (!$validation->check($data)) {
        return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
    }
    $param = [
        "tb_name" => 'ipfs_position',
        "where" => "id=" . $data['id']
    ];
    $result = self::loadApiData("store/delete_record", $param);
    if (!$result) {
        return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器炸了']);
    }
    $result = json_decode($result, true);
    if ($result['status'] != 0) {
        return json($result);
    }
    return json($result);
   }

   //返回所有菜单信息
   public function menu_list(){
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
        return json(['status' => -900, 'msg' => '服务器炸了']);
    }
    $result = json_decode($return_data,true);
    $menu = $result['result']['cols'];
    $result = parent::getTree($menu,0);
    return json($result);
   }

   //根据用户信息返回菜单信息
   public function menu_list_user(){
       $data = input('post.');
       $validation = new Validate([
        'userid' => 'require',
        'roleid' => 'require'
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
        "order" => 'id desc'
    ];
    $return_data = self::loadApiData("store/find_table", $param);
    $return_data = json_decode($return_data,true);
    return json($return_data);

   }

   public function test(){
   }
}
