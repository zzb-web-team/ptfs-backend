<?php
namespace app\admin\controller;
use think\Validate;

class Account extends Common
{

    //查询设备地域分布 
    public function ptfs_forbid_users()
    {
        $data = input('post.');
        $param = array(
            "usr_id_list"  => empty($data['usr_id_list']) ? "" : $data['usr_id_list'],
            "forbid_status"  => empty($data['forbid_status']) ? 0 : $data['forbid_status'],
        );
        return self::loadApiData("account/ptfs_forbid_users", $param);
    }

    //查询设备地域分布 
    public function ptfs_query_total_users()
    {
        $data = input('post.');
        $param = array(
            "token"  => "sss",
        );
        return self::loadApiData("account/ptfs_query_total_users", $param);
    }

    //查询设备地域分布 
    public function ptfs_query_user_list()
    {
        $data = input('post.');
        $param = array(
            "page"  => empty($data['page']) ? 0 : $data['page'],
            "query_type"  => empty($data['query_type']) ? "" : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? "" : $data['user_id'],
            "user_name"  => empty($data['user_name']) ? "" : $data['user_name'],
            "tel_num"  => empty($data['tel_num']) ? "" : $data['tel_num'],
            "account_status"  => empty($data['account_status']) ? "" : $data['account_status'],
            "account_active"  => empty($data['account_active']) ? "" : $data['account_active'],
            "sex"  => empty($data['sex']) ? "" : $data['sex'],
            "reg_start_time"  => empty($data['reg_start_time']) ? "" : $data['reg_start_time'],
            "reg_end_time"  => empty($data['reg_end_time']) ? "" : $data['reg_end_time'],
            "bind_start_time"  => empty($data['bind_start_time']) ? "" : $data['bind_start_time'],
            "bind_end_time"  => empty($data['bind_end_time']) ? "" : $data['bind_end_time'],
            "order"  => empty($data['order']) ? 0 : $data['order'],
        );
        return self::loadApiData("account/ptfs_query_user_list", $param);
    }

    //用户趋势
    public function ptfs_query_user_trend_list()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'reg_start_time'  => 'require',
            'reg_end_time'  => 'require',
            'cur_page'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "reg_start_time"   => isset($data['reg_start_time']) ? $data['reg_start_time'] : 0,
            "reg_end_time" => isset($data['reg_end_time']) ? $data['reg_end_time'] : 1,
            "cur_page"   => isset($data['cur_page']) ? $data['cur_page'] : 0,
        );
        return self::loadApiData("account/ptfs_query_user_trend_list", $param);
    }


}
