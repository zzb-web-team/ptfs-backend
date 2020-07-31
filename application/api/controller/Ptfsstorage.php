<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class PtfsStorage extends Common
{
    public function test()
    {
        $token = $this->request->param('token', '');
        if (Cache::store('redis')->has('token:'.$token)) {
            return  Cache::store('redis')->get('token:'.$token);
        }
        return '没有token';
        //Cache::store('redis')->set('key1','123456789');
        ;
        
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

    //录入设备信息
    public function importnodebasicinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'dev_type' => 'require',
             'dev_sn' => 'require',
             'dev_mac' => 'require',
             'total_cap' => 'require',
             'rom_version' => 'require',
             'cpu_id' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        // if (!$this->check_token($data['login_token'])) {
        //     return json(['status' => -999, 'msg' => 'token验证失效']);
        // }
        $param = array(
            'login_token'  => $data['login_token'],
            'dev_type' => $data['dev_type'],
            'dev_sn' => $data['dev_sn'],
            'dev_mac' => $data['dev_mac'],
            'total_cap' => $data['total_cap'],
            'rom_version' => $data['rom_version'],
            'cpu_id' => $data['cpu_id'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/import_node_basicinfo", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //根据设备 id 查询设备信息
    public function querynodebasicinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_node_basicinfo", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //编辑设备信息
    public function editnodebasicinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
             'dev_name'   => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            'dev_name'   => $data['dev_name'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/edit_node_basicinfo", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //绑定/解绑设备（1 为绑定，0 为解绑）
    public function changedevicebindstate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
             'bind_type'   => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            'bind_type'   => $data['bind_type'],
            'bind_user_tel_num' => $data['bind_user_tel_num'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/change_device_bind_state", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    public function changedevicebindstate2()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
             'bind_type'   => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            'bind_type'   => $data['bind_type'],
            'bind_user_tel_num' => $data['bind_user_tel_num'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        return self::loadApiData("miner_ctrl/change_device_bind_state", $param);
    }

    //查询绑定状态
    public function querydevicebindstate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_device_bind_state", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //根据设备 sn 查询设备 id
    public function querydeviceidbysn()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'   => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'   => $data['dev_sn'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_device_id_by_sn", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }


    //根据用户ID查询已绑定的设备列表
    public function querybinddevinfolistbyuserid()
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
            'login_token'  => $data['login_token'],
            'page_num' => isset($data['page_num']) ? $data['page_num'] : 0,
            'page_cnt' => isset($data['page_cnt']) ? $data['page_cnt'] : 8,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_bind_devinfo_list_by_user_id", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //绑定设备，并更新设备名称 
    public function binddevupdatedevname()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'  => 'require',
             'dev_name'  => 'require',
             'bind_user_tel_num' => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'  => $data['dev_sn'],
            'bind_user_tel_num' => $data['bind_user_tel_num'],
            'dev_name'  => $data['dev_name'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/bind_dev_update_dev_name", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //获取用户的所有设备在线情况 
    public function querybinddevsonlinestate()
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
            'login_token'  => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_bind_devs_online_state", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    //控制设备状态（重启等）  
    public function ctrlnodestate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'  => 'require'
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
            'login_token'  => $data['login_token'],
            'dev_sn'  => $data['dev_sn'],
            'ctrl_type' => intval($data['extra_info']['ctrl_type']),
            'extra_info'  => $data['extra_info'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
            "pid" => isset($data['pid']) ? $data['pid'] : 0,
            "pname" => isset($data['pname']) ? $data['pname'] : ''
        );
        $return_data = self::loadApiData("miner_ctrl/ctrl_node_state", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    public function ctrlnodestate2()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'  => 'require',
             'extra_info'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'login_token'  => $data['login_token'],
            'dev_sn'  => $data['dev_sn'],
            'ctrl_type' => intval($data['extra_info']['ctrl_type']),
            'extra_info'  => $data['extra_info'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        return self::loadApiData("miner_ctrl/ctrl_node_state", $param);
    }

    public function queryonlinehistgraph()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'start_timestamp'  => 'require',
             'end_timestamp'  => 'require',
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
            'login_token'  => $data['login_token'],
            'start_timestamp'  => $data['start_timestamp'],
            'end_timestamp'  => $data['end_timestamp'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_online_histgraph", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['token_info']['login_token']);
        }
        return json($return_data);
    }

    public function querydevnamechglog()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'start_ts'  => 'require',
             'end_ts'  => 'require',
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
            'login_token'  => $data['login_token'],
            'start_ts'  => $data['start_ts'],
            'end_ts'  => $data['end_ts'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_dev_name_chg_log", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['login_token']);
        }
        return json($return_data);
    }

    public function querydevphycaphisgraph()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  => 'require',
             'dev_sn'  => 'require',
             'start_ts'  => 'require',
             'end_ts'  => 'require',
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
            'login_token'  => $data['login_token'],
            'dev_sn'  => $data['dev_sn'],
            'start_ts'  => $data['start_ts'],
            'end_ts'  => $data['end_ts'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_ctrl/query_dev_phy_cap_histgraph", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['login_token'])) {
           $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['login_token']);
        }
        return json($return_data);
    }

    public function query_devinfo_by_conditions()
    {
        $data = input('post.');
        // $param = array(
        //     "page_no"  => empty($data['page_no']) ? 0 : $data['page_no'],
        //     "page_size"  => empty($data['page_size']) ? 10 : $data['page_size'],
        //     "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
        //     "cpu_id"  => empty($data['cpu_id']) ? "" : $data['cpu_id'],
        //     "is_activated"  => empty($data['is_activated']) ? 0 : $data['is_activated'],
        //     "import_start_ts"  => empty($data['import_start_ts']) ? 0 : $data['import_start_ts'],
        //     "import_end_ts"  => empty($data['import_end_ts']) ? 0 : $data['import_end_ts'],
        //     "activate_start_ts"  => empty($data['activate_start_ts']) ? 0 : $data['activate_start_ts'],
        //     "activate_end_ts"  => empty($data['activate_end_ts']) ? 0 : $data['activate_end_ts'],
        // );
        return self::loadApiData("miner_ctrl/query_devinfo_by_conditions", $data);
    }

    public function query_miscell_devinfo()
    {
        $data = input('post.');
        $param = array(
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "query_code"  => empty($data['query_code']) ? 0 : $data['query_code'],
        );
        return self::loadApiData("miner_ctrl/query_miscell_devinfo", $param);
    }

    public function query_devinfo_by_conditions_grapefruit()
    {
        $data = input('post.');
        // $param = array(
        //     "page_no"  => empty($data['page_no']) ? 0 : $data['page_no'],
        //     "page_size"  => empty($data['page_size']) ? 10 : $data['page_size'],
        //     "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
        //     "ipfs_id"  => empty($data['ipfs_id']) ? "" : $data['ipfs_id'],
        //     "dev_name"  => empty($data['dev_name']) ? "" : $data['dev_name'],
        //     "dev_type"  => empty($data['dev_type']) ? "" : $data['dev_type'],
        //     "online_state"  => empty($data['online_state']) ? 0 : $data['online_state'],
        //     "dev_mac"  => empty($data['dev_mac']) ? "" : $data['dev_mac'],
        //     "dev_ip"  => empty($data['dev_ip']) ? "" : $data['dev_ip'],
        //     "rom_version"  => empty($data['rom_version']) ? "" : $data['rom_version'],
        //     "bind_flag"  => empty($data['bind_flag']) ? 0 : $data['bind_flag'],
        //     "bind_start_ts"  => empty($data['bind_start_ts']) ? 0 : $data['bind_start_ts'],
        //     "bind_end_ts"  => empty($data['bind_end_ts']) ? 0 : $data['bind_end_ts'],
        //     "bind_user_id" => empty($data['bind_user_id']) ? 0 : $data['bind_user_id'],
        // );
        return self::loadApiData("miner_ctrl/query_devinfo_by_conditions_grapefruit", $data);
    }

    public function edit_device_basicinfo()
    {
        $data = input('post.');
        $param = array(
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "info"  => empty($data['info']) ? "" : $data['info'],
        );
        return self::loadApiData("miner_ctrl/edit_device_basicinfo", $param);
    }

    public function device_cnt_overview()
    {
        $data = input('post.');
        $param = array(
            "token" => "sss",
        );

        return $return_data = self::loadApiData("miner_ctrl/device_cnt_overview", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function delete_device_basicinfo()
    {
        $data = input('post.');
        $param = array(
            "login_token"  => empty($data['login_token']) ? "" : $data['login_token'],
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
        );
        return self::loadApiData("miner_ctrl/delete_device_basicinfo", $param);
    }

    public function query_binded_user_cnt()
    {
        $data = input('post.');
        $param = array(
            "token" => "sss",
        );

        return self::loadApiData("miner_ctrl/query_binded_user_cnt", $param);
    }

    public function chg_device_state()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/chg_device_state", $data);
    }


    
}
