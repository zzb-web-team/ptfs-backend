<?php
namespace app\admin\controller;
use think\Validate;

class Minerearn extends Common
{

    //查询设备地域分布 
    public function ptfs_total_profit_info()
    {
        $data = input('post.');
        $param = array(
            'token' => 'sss',
        );
        $return_data = self::loadApiData("miner_earn/ptfs_total_profit_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询设备地域分布 
    public function ptfs_query_user_store_list()
    {
        $data = input('post.');
        $param = array(
            "cur_page"  => empty($data['cur_page']) ? 0 : $data['cur_page'],
            "query_type"  => empty($data['query_type']) ? 0 : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? 0 : $data['user_id'],
            "user_name"  => empty($data['user_name']) ? "" : $data['user_name'],
            "tel_num"  => empty($data['tel_num']) ? "" : $data['tel_num'],
        );
        return $return_data = self::loadApiData("miner_earn/ptfs_query_user_store_list", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询设备地域分布 
    public function ptfs_query_user_profit_info()
    {
        $data = input('post.');
        $param = array(
            "query_type"  => empty($data['query_type']) ? 0 : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? 0 : $data['user_id'],
            "user_name"  => empty($data['user_name']) ? "" : $data['user_name'],
            "tel_num"  => empty($data['tel_num']) ? "" : $data['tel_num'],
            "cur_page"  => empty($data['cur_page']) ? 0 : $data['cur_page'],
            "start_time"  => empty($data['start_time']) ? "" : $data['start_time'],
            "end_time"  => empty($data['end_time']) ? "" : $data['end_time'],
        );
        $return_data = self::loadApiData("miner_earn/ptfs_query_user_profit_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询设备地域分布 
    public function ptfs_query_node_info_list()
    {
        $data = input('post.');
        $param = array(
            "query_type"  => empty($data['query_type']) ? 0 : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? 0 : $data['user_id'],
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "cur_page"  => empty($data['cur_page']) ? 0 : $data['cur_page'],
            "nick_name" => empty($data['nick_name']) ? "" : $data['nick_name'],
            "start_time"  => empty($data['start_time']) ? "" : $data['start_time'],
            "end_time"  => empty($data['end_time']) ? "" : $data['end_time'],
            "order"  => empty($data['order']) ? 0 : $data['order'],
        );
        $return_data = self::loadApiData("miner_earn/ptfs_query_node_info_list", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询设备地域分布 
    public function ptfs_set_earn_param()
    {
        $data = input('post.');
        return self::loadApiData("miner_earn/ptfs_set_earn_param", $data);
    }

    //查询设备地域分布 
    public function ptfs_forbiden_devices()
    {
        $data = input('post.');
        $param = array(
            "device_list"  => empty($data['device_list']) ? "" : $data['device_list'],
            "forbid_status"  => empty($data['forbid_status']) ? 0 : $data['forbid_status'],
        );
        $return_data = self::loadApiData("miner_earn/ptfs_forbiden_devices", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询设备地域分布 
    public function ptfs_query_user_total_profit_everyday()
    {
        $data = input('post.');
        $param = array(
            "query_type"  => empty($data['query_type']) ? 0 : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? 0 : $data['user_id'],
            "nick_name"  => empty($data['nick_name']) ? "" : $data['nick_name'],
            "profit_type"  => empty($data['profit_type']) ? 0 : $data['profit_type'],
            "cur_page"  => empty($data['cur_page']) ? 0 : $data['cur_page'],
            "start_time"  => empty($data['start_time']) ? 0 : $data['start_time'],
            "end_time"  => empty($data['end_time']) ? 0 : $data['end_time'],
            "order"  => empty($data['order']) ? 0 : $data['order'],
        );
        $return_data = self::loadApiData("miner_earn/ptfs_query_user_total_profit_everyday", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }
}
