<?php
namespace app\admin\controller;
use think\Validate;

class Bgmanager extends Common
{

    //查询设备地域分布 
    public function query_device_region_distribution()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("bg_manager_tool/query_device_region_distribution", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询当天top10拉取次数排行榜 
    public function query_resource_export_rank()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("bg_manager_tool/query_resource_export_rank", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }
    
    //查询当天top10拉取流量的设备排行榜 
    public function query_stream_export_rank()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("bg_manager_tool/query_stream_export_rank", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //根据条件，查询当前设备统计明细 
    public function query_device_details()
    {
        $data = input('post.');
        $param = array(
            "token" => 'sss',
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "dev_city"  => empty($data['dev_city']) ? "" : $data['dev_city'],
            "export_stream_val1"  => empty($data['export_stream_val1']) ? 1 : $data['export_stream_val1'],
            "export_stream_val2"  => empty($data['export_stream_val2']) ? 10 : $data['export_stream_val2'],
            "page_no"  => $data['page_no'],
            "page_size"  => empty($data['page_size']) ? 10 : $data['page_size'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_device_details", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //渠道数据总览
    public function query_3rd_stream_overview()
    {
        $data = input('post.');
        $validation = new Validate([
            'app_id'  =>  'require',
            'time_span'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "token" => 'sss',
            "app_id"  => $data['app_id'],
            "time_span"  => $data['time_span'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_3rd_stream_overview", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //渠道容量流量统计
    public function query_3rd_capacity_stream_overview()
    {
        $data = input('post.');
        $validation = new Validate([
            'app_id'  =>  'require',
            'page_no'  =>  'require',
            'dev_sn'  =>  'require',
            'page_size'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "token" => 'sss',
            "app_id"  => $data['app_id'],
            "page_no"  => $data['page_no'],
            "dev_sn"  => $data['dev_sn'],
            "page_size"  => $data['page_size'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_3rd_capacity_stream_overview", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //渠道容量流量统计
    public function query_3rd_capacity_stream_details()
    {
        $data = input('post.');
        $validation = new Validate([
            'app_name'  =>  'require',
            'timestamp'  =>  'require',
            'page_no'  =>  'require',
            'page_size'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "token" => 'sss',
            "app_name"  => $data['app_name'],
            "timestamp"  => $data['timestamp'],
            "page_no"  => $data['page_no'],
            "page_size"  => $data['page_size'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_3rd_capacity_stream_details", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //渠道容量流量统计
    public function send_ptfs_cmd()
    {
        $data = input('post.');
        $validation = new Validate([
            'snode_hash'  =>  'require',
            'cmd_type'  =>  'require',
            'target_node'  =>  'require',
            'cmd_data'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "snode_hash" => $data['snode_hash'],
            "cmd_type"  => $data['cmd_type'],
            "target_node"  => $data['target_node'],
            "cmd_data"  => $data['cmd_data'],
            "cmd_desc"  => $data['cmd_desc'],
        );
        $return_data = self::loadApiData("bg_manager_tool/send_ptfs_cmd", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //渠道容量流量统计
    public function query_ptfs_cmd_result()
    {
        $data = input('post.');
        $validation = new Validate([
            'task_id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "task_id"  => $data['task_id'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_ptfs_cmd_result", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }


}
