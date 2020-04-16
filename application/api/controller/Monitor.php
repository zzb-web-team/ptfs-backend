<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class Monitor extends Common
{

    //获取微服务信息
    public function getserver()
    {
        $param = array(
            'ts'  => time(),
        );
        $return_data = self::loadApiData("monitor/get_server", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //获取当前进程状态信息
    public function getcurprocessinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'server_id'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'server_id'  => $data['server_id'],
        );
        $return_data = self::loadApiData("monitor/get_cur_process_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //获取当前服务器状态信息
    public function getcurmachineinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'server_id'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'server_id'  => $data['server_id'],
        );
        $return_data = self::loadApiData("monitor/get_cur_machine_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //获取当前进程状态信息
    public function getallprocessinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'server_id'  => 'require',
            'day' => 'require',
            'type_of_data' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'server_id'  => $data['server_id'],
            'day'  => $data['day'],
            'type_of_data'  => $data['type_of_data'],
        );
        $return_data = self::loadApiData("monitor/get_all_process_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //获取当前进程状态信息
    public function getallmachineinfo()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'server_id'  => 'require',
            'day' => 'require',
            'type_of_data' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'server_id'  => $data['server_id'],
            'day'  => $data['day'],
            'type_of_data'  => $data['type_of_data'],
        );
        $return_data = self::loadApiData("monitor/get_all_machine_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function getmachine()
    {
        $param = array(
            'ts'  => time(),
        );
        return self::loadApiData("monitor/get_machine", $param);
    }



    //获取当前服务器状态信息
    public function getcurmachineinfo2()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'machine_id'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'machine_id'  => $data['machine_id'],
        );
        return self::loadApiData("monitor/get_cur_machine_info2", $param);
    }

    //获取当前进程状态信息
    public function getallmachineinfo2()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'machine_id'  => 'require',
            'day' => 'require',
            'type_of_data' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'machine_id'  => $data['machine_id'],
            'day'  => $data['day'],
            'type_of_data'  => $data['type_of_data'],
            'page' => isset($data['page']) ? $data['page'] : 0,
        );
        $return_data = self::loadApiData("monitor/get_all_machine_info2", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

}
