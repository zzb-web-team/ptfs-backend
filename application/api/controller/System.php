<?php
namespace app\api\controller;

use think\facade\Cache;
use think\Validate;

class System extends Common
{
   
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
        if (Cache::store('redis')->has('ipfs_alldepartment')) {
            $result1 = Cache::store('redis')->get('ipfs_alldepartment');
        } else {
            $result1 = parent::cachedb('ipfs_department',"*","ipfs_alldepartment");
        }
        $result1 = json_decode($result1, true);
        $pidarr = [];
        foreach ($result1 as $k => $v) {
            $pidarr[] = $v['pid'];
        }
        $pidarr = array_unique($pidarr, SORT_REGULAR);
        $pid = implode(",", $pidarr);
        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_department',
            "col_name" => "*",
            "where" => "(pid != 0) or (id not in (" . $pid . "))",
            "order" => $order,
        );
        $result = self::loadApiData("store/find_table", $param);
        $result = json_decode($result, true);
        if (Cache::store('redis')->has('ipfs_alluser')) {
            $result2 = Cache::store('redis')->get('ipfs_alluser');
        } else {
            $result2 = parent::cachedb('ipfs_user',"*","ipfs_alluser");
        }
        $result2 = json_decode($result2,true);
        $newarr = [];
        foreach ($result1 as $v) {
            if ($v['pid'] == 0) {
                $newarr[$v['id']] = $v['name'];
            }
        }
        foreach ($result['result']['cols'] as $k => $v) {
            $v['user'] = [];
            if ($v['pid'] != 0) {
                $v['parent'] = $newarr[$v['pid']];
            } else {
                $v['parent'] = $v['name'];
                $v['name'] = '-';
            }
            foreach($result2 as $u){
                if($v['id'] == $u['department_id']){
                    $v['user'][] = ['id' => $u['id'],'name' => $u['username']]; 
                }
            }
            $result['result']['cols'][$k] = $v;
        }
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result['result']['tree'] = parent::getTree($result1,0);
        return json($result);
    }

    public function get_alldepartment()
    {
        $where = '1';
        $param = array(
            "page" => 0,
            "page_size" => 200,
            "tb_name" => 'ipfs_department',
            "col_name" => "*",
            "where" => $where,
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器开小差去了']);
        }
        $result = Cache::store('redis')->set('ipfs_alldepartment', $result);

    }

    public function get_topdepartment()
    {
        $param = array(
            "page" => 0,
            "page_size" => 100,
            "tb_name" => 'ipfs_department',
            "col_name" => "*",
            "where" => "pid = 0",
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $result;
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
        parent::cachedb('ipfs_department',"*","ipfs_alldepartment");
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
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
        parent::cachedb('ipfs_department',"*","ipfs_alldepartment");
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
            'ids' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if (Cache::store('redis')->has('ipfs_alluser')) {
            $return_data = Cache::store('redis')->get('ipfs_alluser');
        } else {
            $return_data = parent::cachedb('ipfssystem_user', "*", 'ipfs_alluser');
        }
        $return_data = json_decode($return_data,true);
        foreach ($data['ids'] as $k => $v) {
            $departmentid = array_column($return_data,'department_id');
            if(array_search($v['id'],$departmentid)){
                return json(['status' => 1,'msg' => '所选部门下存在用户,禁止删除!']);
            }
            $param = array(
                "tb_name" => 'ipfs_department',
                "where" => "id='" . $v['id'] . "'",
            );
            $result = self::loadApiData("store/delete_record", $param);
        }
        if (!$result) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        parent::cachedb('ipfs_department',"*","ipfs_alldepartment");
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
        parent::cachedb('ipfs_position',"*","ipfs_allposition");
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
        parent::cachedb('ipfs_position',"*","ipfs_allposition");
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
        if (Cache::store('redis')->has('ipfs_alluser')) {
            $return_data = Cache::store('redis')->get('ipfs_alluser');
        } else {
            $return_data = parent::cachedb('ipfssystem_user', "*", 'ipfs_alluser');
        }
        $return_data = json_decode($return_data,true);
        foreach ($data['ids'] as $k => $v) {
            $positionid = array_column($return_data,'position_id');
            if(array_search($v['id'],$positionid)){
                return json(['status' => 1,'msg' => '所选职位下存在用户,禁止删除!']);
            }
            $param = [
                "tb_name" => 'ipfs_position',
                "where" => "id=" . $v['id'],
            ];
            $result = self::loadApiData("store/delete_record", $param);
            if (!$result) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
            }
            $result = json_decode($result, true);
            if ($result['status'] != 0) {
                return json($result);
            }
        }
        parent::cachedb('ipfs_position',"*","ipfs_allposition");
        return json($result);
    }

    //根据用户信息返回权限菜单信息
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
        if (Cache::store('redis')->has('ipfs_allmenu')) {
            $return_data2 = Cache::store('redis')->get('ipfs_allmenu');
        } else {
            $return_data2 = parent::cachedb('ipfs_menu', "*", 'ipfs_allmenu');
        }
        $return_data2 = json_decode($return_data2, true);
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_role',
            "col_name" => "*",
            "where" => 'id = ' . $data['roleid'],
            "order" => 'id desc',
        );
        $result = self::loadApiData("store/find_table", $param);
        $result = json_decode($result,true);
        if($result['result']['cols'][0]['type'] == 0){
            foreach(array_reverse($return_data2) as $k=>$v){
                if ($v['read_status'] == 1) {
                    $v['roleR'] = 1;
                }

                if ($v['update_status'] == 1) {
                    $v['roleU'] = 1;
                }

                if ($v['delete_status'] == 1) {
                    $v['roleD'] = 1;
                }

                if ($v['insert_status'] == 1) {
                    $v['roleC'] = 1;
                }

                if ($v['import_status'] == 1) {
                    $v['roleI'] = 1;
                }

                if ($v['export_status'] == 1) {
                    $v['roleE'] = 1;
                }
                $return_data2[$k] = $v;
            }
            return json(['status' => 0,'data' => parent::getTree($return_data2,0)]);
        }
        if (Cache::store('redis')->has('ipfs_allroleinfo')) {
            $return_data = Cache::store('redis')->get('ipfs_allroleinfo');
        } else {
            $return_data = parent::cachedb('ipfs_roleinfo', "*", 'ipfs_allroleinfo');
        }
        $return_data = json_decode($return_data, true);
        $roleinfolist = [];
        foreach($return_data as $k => $v){
            if($v['roleid'] == $data['roleid']){
                $roleinfolist[] = $v;
            }
        }
        
        $newarr = [];
        foreach ($roleinfolist as $k => $v) {
            $newarr = $this->getparent($v['menuid'], $return_data2);
        }
        $newarr = array_unique($newarr, SORT_REGULAR);
        foreach ($newarr as $k => $v) {
            unset($v['read_status'],$v['update_status'],$v['delete_status'],$v['insert_status'],$v['import_status'],$v['export_status'],$v['time_create'],$v['time_update']);
            foreach ($roleinfolist as $m => $n) {
                if ($n['menuid'] == $v['id']) {
                    if ($n['roleC'] == 1) {
                        $v['roleC'] = $n['roleC'];
                    }

                    if ($n['roleD'] == 1) {
                        $v['roleD'] = $n['roleD'];
                    }

                    if ($n['roleR'] == 1) {
                        $v['roleR'] = $n['roleR'];
                    }

                    if ($n['roleU'] == 1) {
                        $v['roleU'] = $n['roleU'];
                    }

                    if ($n['roleI'] == 1) {
                        $v['roleI'] = $n['roleI'];
                    }

                    if ($n['roleE'] == 1) {
                        $v['roleE'] = $n['roleE'];
                    }

                } 
            }
            $newarr[$k] = $v;
        }
        return json(['status' => 0, 'data' => parent::getTree($newarr, 0)]);
    }

    //根据权限分组名称搜索权限分组/权限分组列表
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
        $where = "1";
        $where .= $data['search'] == "" ? "" : " and name LIKE '%" . $data['search'] . "%'";
        $param = [
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name" => 'ipfs_role',
            "col_name" => "*",
            "where" => $where,
            "order" => 'id desc',
        ];
        $return_data = self::loadApiData("store/find_table", $param);
        $result = json_decode($return_data, true);
        if (!$result || $result['status'] != 0) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        if (Cache::store('redis')->has('ipfs_alluser')) {
            $return_data2 = Cache::store('redis')->get('ipfs_alluser');
        } else {
            $return_data2 = parent::cachedb('ipfssystem_user', "*", 'ipfs_alluser');
        }
        $result2 = json_decode($return_data2, true);
        if (isset($data['name'])) {
            $where .= " and name='" . $data['name'] . "'";
        }
        if (Cache::store('redis')->has('ipfs_allroleinfo')) {
            $return_data3 = Cache::store('redis')->get('ipfs_allroleinfo');
        } else {
            $return_data3 = parent::cachedb('ipfs_roleinfo', "*", 'ipfs_allroleinfo');
        }
        $result3 = json_decode($return_data3, true);
        if (Cache::store('redis')->has('ipfs_allmenu')) {
            $return_data4 = Cache::store('redis')->get('ipfs_allmenu');
        } else {
            $return_data4 = parent::cachedb('ipfs_menu',"*", 'ipfs_allmenu');
        }
        $result4 = json_decode($return_data4, true);
        foreach ($result4 as $k => $m) {
            $result4[$k]['label'] = $m['name'];
            unset($result4[$k]['name']);
        }
        $menulist = parent::getTree($result4, 0);
        foreach ($result['result']['cols'] as $k => $v) {
            $v['user'] = [];
            $v['roleinfo'] = [];
            foreach ($result2 as $n) {
                if ($n['role_id'] == $v['id']) {
                    $v['user'][] = ["id" => $n['id'], "label" => $n['username']];
                }
            }
            foreach ($result3 as $r) {
                if ($r['roleid'] == $v['id']) {
                    $v['roleinfo'][] = $r;
                }
            }
            $result['result']['cols'][$k] = $v;
        }
        $result['menulist'] = $menulist;
        return json($result);
    }

    public function add_role()
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
        if (isset($data['userid'])) {
            $param1 = array(
                "page" => isset($data['page']) ? intval($data['page']) : 0,
                "page_size" => 10,
                "tb_name" => 'ipfs_role',
                "col_name" => "*",
                "where" => "name = '" . $data['name'] . "'",
                "order" => 'id desc',
            );
            $return_data1 = self::loadApiData("store/find_table", $param1);
            $result1 = json_decode($return_data1, true);
            $update = [
                "role_id",
            ];
            $insert = [
                $result1['result']['cols'][0]['id'],
            ];
            $param2 = array(
                "tb_name" => 'ipfssystem_user',
                "update" => $update,
                "col_value" => $insert,
                "where" => "id in (" . $data['userid'] . ")",
            );
            $result2 = self::loadApiData("store/update_table", $param2);
            parent::cachedb('ipfssystem_user',"*","ipfs_alluser");
            return $result2;
        }
        return json($result);
    }

    public function userlist_addrole()
    {
        if (Cache::store('redis')->has('ipfs_alldepartment')) {
            $return_data = Cache::store('redis')->get('ipfs_alldepartment');
        } else {
            $return_data = parent::cachedb('ipfs_department', "*", 'ipfs_alldepartment');
        }
        $result = json_decode($return_data, true);
        if (Cache::store('redis')->has('ipfs_alluser')) {
            $user = Cache::store('redis')->get('ipfs_alluser');
        } else {
            $user = parent::cachedb('ipfssystem_user', "*", 'ipfs_alluser');
        }
        $user = json_decode($user, true);
        $newarr = [];
        foreach ($result as  $v) {
            foreach ($user as $m => $n) {
                if ($n['department_id'] == $v['id'] && $n['role_id'] == 0) {
                    $newarr[] = $v;
                    $newarr = $this->getparent($v['id'],$result);
                }
            }
        }
        $newarr = array_unique($newarr,SORT_REGULAR);
        foreach($newarr as $k=>$v){
            foreach($user as $n){
                if ($n['department_id'] == $v['id'] && $n['role_id'] == 0){
                    $v['user'][] = ["id" => $n["id"], "label" => $n['username']];
                }
            }
            $newarr[$k] = $v;
        }
        if (!$user) {
            return json(['status' => -900, 'msg' => 'ipfs服务出错']);
        }
        return json(['status' => 0, 'data' => parent::getTree($newarr, 0)]);
    }

    public function getuserdepartment()
    {
        $data = input('post.');
        $validation = new Validate([
            'userid' => 'require',
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 20,
            "tb_name" => 'ipfssystem_user',
            "col_name" => "*",
            "where" => "id in (" . $data['userid'] . ")",
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $result = json_decode($return_data, true);
        $where = '1';
        if (Cache::store('redis')->has('ipfs_alldepartment')) {
            $return_data1 = Cache::store('redis')->get('ipfs_alldepartment');
        } else {
            $return_data1 = parent::cachedb('ipfs_department', "*", 'ipfs_alldepartment');
        }
        $result1 = json_decode($return_data1, true);
        $arr = [];
        foreach ($result['result']['cols'] as $k => $v) {
            foreach ($result1 as $m => $n) {
                if ($n['id'] == $v['department_id']) {
                    $v['departmentid'] = $n['id'];
                    $v['pdepartmentid'] = $n['pid'];
                }
            }
            $arr[] = [
                'userid' => $v['id'],
                'departmentid' => $v['departmentid'],
                'pdepartmentid' => $v['pdepartmentid'],
            ];
        }
        return json(['status' => 0, 'data' => $arr]);
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
            'name' => 'require'
        ]);
        //验证表单
        if (!$validation->check($data)) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $userid = isset($data['userid']) ? $data['userid'] : "";
        $data['description'] = isset($data['description']) ? $data['description'] : "";
        $update = ['name', 'description'];
        $insert = [$data['name'], $data['description']];
        $param = array(
            "tb_name" => 'ipfs_role',
            "update" => $update,
            "col_value" => $insert,
            "where" => "id=" . $data['roleid'],
        );
        $result = self::loadApiData("store/update_table", $param);
        $update1 = ['role_id'];
        $insert1 = [$data['roleid']];
        $param1 = [
            "tb_name" => 'ipfssystem_user',
            "update" => $update1,
            "col_value" => $insert1,
            "where" => "id in (" . $userid . ")",
        ];
        $result1 = self::loadApiData("store/update_table", $param1);
        parent::cachedb('ipfssystem_user',"*","ipfs_alluser");
        if (!$result || !$result1) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $result;
    }

    public function update_roleinfo()
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
        $list = $data['data'];
        $temp = [];
        for ($i = 0; $i < count($list); $i++) {
            $tp = $list[$i];
            unset($tp['menuid']);
            if (isset($temp[$list[$i]['menuid']])) {
                $temp[$list[$i]['menuid']] = array_merge($tp, $temp[$list[$i]['menuid']]);
            } else {
                $temp[$list[$i]['menuid']] = $tp;
            }
        }
        $param = [
            "tb_name" => 'ipfs_roleinfo',
            "where" => "roleid=" . $data['roleid'],
        ];
        $result = self::loadApiData("store/delete_record", $param);
        foreach ($temp as $k => $v) {
            $insert[] = [
                $data['roleid'],
                $k,
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
        parent::cachedb('ipfs_roleinfo',"*","ipfs_allroleinfo");
        if (!$return_data) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        return $return_data;
    }

    public function del_role()
    {
        $data = input('post.');
        $validation = new Validate([
            'roleid' => 'require'
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
        parent::cachedb('ipfs_roleinfo',"*","ipfs_allroleinfo");
        $param = [
            "tb_name" => 'ipfs_role',
            "where" => "id=" . $data['roleid'],
        ];
        $result = self::loadApiData("store/delete_record", $param);
        if(isset($data['userid'])){
            $update = ['role_id'];
            $insert = [0];
            $param1 = array(
                "tb_name" => 'ipfssystem_user',
                "update" => $update,
                "col_value" => $insert,
                "where" => "id in (" . $data['userid'] . ")",
            );
            self::loadApiData("store/update_table", $param1);
        }
        parent::cachedb('ipfssystem_user',"*","ipfs_alluser");
        if (!$result) {
            return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $result = json_decode($result, true);
        if ($result['status'] != 0) {
            return json(['status' => 1, 'msg' => '删除失败']);
        }
        return json($result);
    }

    public function getparent($id, $data)
    {
        static $parent = [];
        foreach ($data as $k => $v) {
            if ($v["id"] == $id) {
                $parent[] = $v;
                $this->getparent($v["pid"], $data);
            }
        }
        return $parent;
    }

    public function rechange($data)
    {
        $temp = [];

        foreach ($data as $k => $v) {
            if (count($v['parent']) > 0) {
                $temp = $this->rechange($v['parent']);
                unset($v['parent']);
                $temp['child'][] = $v;
            } else {
                unset($v['parent']);
                $temp = $v;
            }
        }

        return $temp;
    }

    public function flushdepartment(){
        $result = Cache::store('redis')->rm('ipfs_alldepartment');
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => $result]); 
    }

    public function flushuser(){
            $result = Cache::store('redis')->rm('ipfs_alluser');
            return json(['status' => 0, 'err_code' => 0,  'err_msg' => $result]); 
    }

    public function getservercache(){
        $result = Cache::store('redis')->get('ipfs_alluser');
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => json_decode($result, true)]);
    }
}