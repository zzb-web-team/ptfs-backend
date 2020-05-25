<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class Minerearn extends Common
{

    //查询单个ptfs存储】动态信息
    public function querynodeinfolist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'start_time'  =>  'require',
             'end_time' =>  'require',
             'dev_sn'  =>  'require',
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
            "login_token" => $data['login_token'],
            "start_time"  => $data['start_time'],
            "end_time" => $data['end_time'],
            "dev_sn" => $data['dev_sn'],
            "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_node_info_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    //查询指定【ptfs存储】收益信息
    public function querynodeprofitlist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'start_time'  =>  'require',
             'end_time' =>  'require',
             'dev_sn'  =>  'require',
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
            "login_token" => $data['login_token'],
            "start_time"  => $data['start_time'],
            "end_time" => $data['end_time'],
            "dev_sn" => $data['dev_sn'],
            "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_node_profit_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    //全网用户收益信息排名
    public function queryprofitrank()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'query_type'  =>  'require',
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
            "login_token" => $data['login_token'],
            "query_type" => $data['query_type'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_profit_rank", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    //查询用户绑定 的【ptfs存 储】动态信息 (每天一条最 终数据)
    public function queryusernodeinfolist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'start_time'  =>  'require',
             'end_time' =>  'require',
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
            "login_token" => $data['login_token'],
            "start_time"  => $data['start_time'],
            "end_time" => $data['end_time'],
            "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_user_node_info_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    //查询用户绑定 的【ptfs存 储】动态信息 (每天一条最 终数据)
    public function queryusernodeprofitlist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'start_time'  =>  'require',
             'end_time' =>  'require',
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
            "login_token" => $data['login_token'],
            "start_time"  => $data['start_time'],
            "end_time" => $data['end_time'],
            "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_user_node_profit_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    //php查询用户兑换记录
    public function queryusernodeexchangelist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'start_time'  =>  'require',
             'end_time' =>  'require',
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
            "login_token" => $data['login_token'],
            "start_time"  => $data['start_time'],
            "end_time" => $data['end_time'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_user_node_exchange_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    public function queryuserprofitlist()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'query_type'  =>  'require',
             'start_time'  =>  'require',
             'end_time'  =>  'require',
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
            "login_token" => $data['login_token'],
            "query_type" => $data['query_type'],
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("miner_earn/query_user_profit_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['data']['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['data']['token_info']['token']);
        }
        return json($return_data);
    }

    public function savequestion()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'login_token'  =>  'require',
             'user_name'  =>  'require',
             'user_tel_num'  =>  'require',
             'quest_cotent'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        
        $param = array(
            "tb_name"   => 'ipfs_user_question',
            "insert"    => [
                [
                    $data['login_token'], $data['user_name'], $data['user_tel_num'], $data['quest_cotent']
                ]
            ]
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function ptfs_query_list_user_store_list()
    {
        $data = input('post.');
        //表单验证规则
        // $validation = new Validate([
        //     'cur_page'=>  'require',
        //     ]
        // );
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'msg' => $validation->getError()]);
        // }
        
        // $param = array(
        //     "user_tel" => $data['user_tel'],
        //     "user_name" => $data['user_name'],
        //     "user_id" => $data['user_id'],
        //     "order" => $data['order'],
        //     "user_status" => $data['user_status'],
        //     "user_sex" => $data['user_sex'],
        //     "reg_start_time" => $data['reg_start_time'],
        //     "reg_end_time" => $data['reg_end_time'],
        //     "bind_start_time" => $data['bind_start_time'],
        //     "bind_end_time" => $data['bind_end_time'],
        //     "cur_page" => isset($data['cur_page']) ? intval($data['cur_page']) : 0,
        // );
        return self::loadApiData("miner_earn/ptfs_query_list_user_store_list", $data);
    }


    public function turnon()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'version'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $phone = isset($data['phone']) ? $data['phone'] : "";
        $param = array(
            "phonenum" => $phone."", 
            "userip" => request()->ip(), 
            "appversion" => $data['version']
        );
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, "http://39.100.131.247:3469/api/ptfs");
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data['method'] = 'ipfs_data_center/app_turnon_log';
        $post_data['data'] = $param;
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
        //执行命令
        $response = curl_exec($curl);
        //显示获得的数据
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        } else {
            $body = $response;
        }
        //关闭URL请求
        curl_close($curl);
        return $body;
    }

    public function ptfs_query_node_grade()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_query_node_grade", $data);
    }

    public function ptfs_query_con_value_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_query_con_value_list", $data);
    }

    public function ptfs_query_cp_value_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_query_cp_value_list", $data);
    }

    public function ptfs_set_con_param_add()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_con_param_add", $data);
    }

    public function ptfs_set_con_param_dec()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_con_param_dec", $data);
    }

    public function ptfs_get_con_param_add()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_get_con_param_add", $data);
    }

    public function ptfs_get_con_param_dec()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_get_con_param_dec", $data);
    }

    public function ptfs_query_node_info_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_query_node_info_list", $data);
    }

     public function ptfs_set_com_power_scale()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_com_power_scale", $data);
    }

     public function ptfs_set_com_power_add()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_com_power_add", $data);
    }

     public function ptfs_set_com_power_dec()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_com_power_dec", $data);
    }

     public function ptfs_get_com_power_scale()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_get_com_power_scale", $data);
    }

     public function ptfs_get_com_power_add()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_get_com_power_add", $data);
    }

     public function ptfs_get_com_power_dec()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_get_com_power_dec", $data);
    }

     public function get_user_average_cp()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_user_average_cp", $data);
    }

     public function get_dev_cap_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_dev_cap_list", $data);
    }

     public function get_dev_bandwidth_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_dev_bandwidth_list", $data);
    }

     public function ptfs_query_user_profit_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_query_user_profit_list", $data);
    }

     public function query_node_total_profit_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/query_node_total_profit_info", $data);
    }

     public function sign()
    {
        $data = input('post.');
        return self::loadApiData("account/sign", $data);
    }

     public function get_app_dev_con_val()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_app_dev_con_val", $data);
    }

    public function get_app_dev_con_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_app_dev_con_list", $data);
    }

    public function get_app_dev_cp_val()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_app_dev_cp_val", $data);
    }

    public function get_app_dev_cp_list()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/get_app_dev_cp_list", $data);
    }

    public function query_node_dynamic_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/query_node_dynamic_info", $data);
    }

    public function create_help_cat_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/create_help_cat_info", $data);
    }

    public function create_help_item_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/create_help_item_info", $data);
    }

    public function modify_help_cat_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/modify_help_cat_info", $data);
    }

    public function modify_help_item_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/modify_help_item_info", $data);
    }

    public function delete_help_cat_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/delete_help_cat_info", $data);
    }

    public function delete_help_item_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/delete_help_item_info", $data);
    }

    public function query_help_cat_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/query_help_cat_info", $data);
    }

    public function query_help_item_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/query_help_item_info", $data);
    }

    public function app_query_help_cat_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/app_query_help_cat_info", $data);
    }

    public function app_query_help_item_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/app_query_help_item_info", $data);
    }

    public function query_node_address_info()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/query_node_address_info", $data);
    }

    public function web_change_device_state()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/web_change_device_state", $data);
    }

    public function move_help_item()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/move_help_item", $data);
    }

    public function batch_import_devices()
    {
        $data = input('post.');
        return self::loadApiData("miner_ctrl/batch_import_devices", $data);
    }

    public function get_sign_value(){
        $data = input('post.');
        return self::loadApiData("miner_earn/get_sign_value",$data);
    }

}
