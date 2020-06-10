<?php
namespace app\cloud\controller;
use think\Validate;
use think\facade\Cache;

class Terminal extends Common
{
    private function randStr( $length = 6 ) { 
        // 密码字符集，可任意添加你需要的字符 
        $chars = 'abcdefghijkmnpqrstuvwxyABCDEFGHJKLMNPQRTUVWXY346789'; 
        $password = ''; 
        for ($i = 0; $i < $length; $i++) { 
            $password .= $chars[mt_rand(0, strlen($chars) - 1)]; 
        } 
        return $password; 
    }

    public function addterminal()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'chanid' => 'require',
            'name'  =>  'require',
            'type'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        //if ($data['type'] == 'android/ios') {
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"   => 'cloud_terminal',
                "col_name"  => "*",
                "where"     => "name='".$data['name']."' and chanid=".intval($data['chanid']),
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
                return json(['status' => -900, 'err_code' => 0, 'msg' => '您已经添加过该终端了']);
            }
            $token = md5($this->randStr(6));
            $insert = array();

            $insert[] = [
                $data['chanid'],
                $data['name'],
                $data['type'],
                $token
            ];
            // $insert[] = [
            //     $data['chanid'],
            //     $data['name'],
            //     'android',
            //     md5($this->randStr(6))
            // ];
            if (!$insert) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '缺少参数']);
            }
            $param = array(
                "tb_name"   => 'cloud_terminal',
                "insert"    => $insert
            );
            $return_data = self::loadApiData("store/insert_table", $param);
            if (!$return_data) {
                return json(['status' => -900, 'msg' => '服务器可能开小差去了']);
            }
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] != 0) {
                return json($return_data);
            }

            //添加成功 上报
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"   => 'cloud_terminal',
                "col_name"  => "*",
                "where"     => "name='".$data['name']."' and chanid=".intval($data['chanid']),
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
                return json(['status' => -900, 'err_code' => 0, 'msg' => '终端ID未找到']);
            }

            $param = array(
                "id" => $return_data['result']['cols'][0]['id']."",
                "chanid"   => $data['chanid']."",
                "token"    => $token.""
            );
            self::loadCloudData("url_mgmt/add_terminal", $param);
            return json($return_data);
        //}
        // $param = array(
        //     "page" => 0,
        //     "page_size" => 10,
        //     "tb_name"   => 'cloud_terminal',
        //     "col_name"  => "*",
        //     "where"     => "name='".$data['name']."' and type='".$data['type']."' and chanid=".intval($data['chanid']),
        //     "order"     => 'id desc',
        // );
        // $return_data = self::loadApiData("store/find_table", $param);
        // if (!$return_data) {
        //     return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        // }
        // $return_data = json_decode($return_data, true);
        // if ($return_data['status'] != 0) {
        //     return json($return_data);
        // }
        // if (isset($return_data['result']['cols'][0])) {
        //     return json(['status' => -900, 'err_code' => 0, 'msg' => '您已经添加过该终端了']);
        // }
        // $insert = array();

        // $insert[] = [
        //     $data['chanid'],
        //     $data['name'],
        //     $data['type'],
        //     md5($this->randStr(6))
        // ];
        // if (!$insert) {
        //     return json(['status' => -900, 'err_code' => -900, 'msg' => '缺少参数']);
        // }
        // $param = array(
        //     "tb_name"   => 'cloud_terminal',
        //     "insert"    => $insert
        // );
        // return self::loadApiData("store/insert_table", $param);  
    }

    public function getterminal()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'chanid' =>  'require',
            'page'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['chanid'] == 0) {
            $where = "";
        } else {
            $where = "chanid = ".intval($data['chanid']);
        }
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => isset($data['pagesize']) ?  intval($data['pagesize']) : 10,
            "tb_name"   => 'cloud_terminal',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => 'id desc,name asc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function deleteterminal()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'chanid'  =>  'require',
            'token'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"   => 'cloud_terminal',
            "where"     => "id=".intval($data['id']),
        );
        $return_data = self::loadApiData("store/delete_record", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $param = array(
            "chanid" => $data['chanid']."",
            "token" => $data['token']."",
        );
        return self::loadCloudData("url_mgmt/del_terminal", $param);
    }

    public function editterminal()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'id'  =>  'require',
            'name' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $update = [
            "name"
        ];
        $insert = [
            $data['name']
        ];

        $param = array(
            "tb_name"   => 'cloud_terminal',
            "update"    => $update,
            "col_value" => $insert,
            "where" => "id=".intval($data['id']),
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function actionlogList()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page'  =>  'require',
            'utype' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $where = "utype = '".$data['utype']."'";
        if (isset($data['search'])) {
            $where.= $data['search']=="" ? "" : " and username = '".$data['search']."'";
        }
        if (isset($data['action'])){
            $where.= $data['action']=="" ? "" : " and action = '".$data['action']."'";
        }
        if (isset($data['start_ts'])){
            $where.= $data['start_ts']=="" ? "" : " and time_create >= ".$data['start_ts'];
        }
        if (isset($data['end_ts'])){
            $where.= $data['end_ts']=="" ? "" : " and time_create <= ".$data['end_ts'];
        }
        if (isset($data['status'])){
            $where.= $data['status']===null ? "" : " and status=".$data['status'];
        }
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_action_log',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => 'id desc',
        );
        $rs = self::loadApiData("store/find_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'IPFS服务错误']);
        }
        return $rs;

    }

    public function setactionlog()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'action'  =>  'require',
            'description'  =>  'require',
            'beforevalue'  =>  'require',
            'aftervalue'  =>  'require',
            'status' =>  'require',
            'utype' =>  'require',
            'id' => 'require',
            'name' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        return self::actionLog($data['action'], $data['description'], $data['beforevalue'], $data['aftervalue'], $data['status'], $data['utype'], $data['id'], $data['name']);
    }

}
