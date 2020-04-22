<?php
namespace app\admin\controller;
use think\facade\Cache;
use think\Validate;
use think\Request;

class Appmarket extends Common
{

    //
    public function verify()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'dev_sn'  => 'require',
             'dev_mac'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "dev_sn"   => $data['dev_sn'],
            "dev_mac"   => $data['dev_mac'],
            "cpu_id"   => isset($data['cpu_id']) ? $data['cpu_id'] : '',
        );
        return self::loadApiData("device_manage/verify", $param);
    }

    public function add_app()
    {
        $data = input('post.');
        //表单验证规则
        // $validation = new Validate([
        //      'app_name'  => 'require',
        //      'app_icon' => 'require',
        //      'pkg_name' => 'require',
        //      'app_type' => 'require',
        //      'app_score' => 'require',
        //      'app_size' => 'require',
        //      'dl_count' => 'require',
        //      'app_version' => 'require',
        //      'app_brief' => 'require',
        //      'snapshot' => 'require',
        //      'developer' => 'require',
        //      //'app_pic' => 'require',
        //      'dl_url' => 'require',
        //     ]
        // );
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "token" => isset($data['token']) ? $data['token'] : '',
        //     "app_name"   => $data['app_name'],
        //     "app_icon"   => $data['app_icon'],
        //     "pkg_name"   => $data['pkg_name'],
        //     "app_type"   => $data['app_type'],
        //     "app_score"   => $data['app_score'],
        //     "app_size"   => $data['app_size'],
        //     "dl_count"   => $data['dl_count'],
        //     "app_version"   => $data['app_version'],
        //     "app_brief"   => $data['app_brief'],
        //     "snapshot"   => $data['snapshot'],
        //     "developer"   => $data['developer'],
        //     "app_pic"   => isset($data['app_pic']) ? $data['app_pic'] : ["http://xxx"],
        //     "dl_url"   => $data['dl_url'],
        // );
        return self::loadApiData("appmarket/add_app", $data);
    }

    public function update_app()
    {
        $data = input('post.');
        //表单验证规则
        // $validation = new Validate([
        //      'token'  => 'require',
        //      'app_id'  => 'require',
        //      'app_name'  => 'require',
        //      'app_icon' => 'require',
        //      'pkt_name' => 'require',
        //      'app_type' => 'require',
        //      'app_score' => 'require',
        //      'app_size' => 'require',
        //      'dl_count' => 'require',
        //      'app_version' => 'require',
        //      'app_brief' => 'require',
        //      'snapshot' => 'require',
        //      'developer' => 'require',
        //      'app_pic' => 'require',
        //      'dl_url' => 'require',
        //     ]
        // );
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "token"   => $data['token'],
        //     "app_id"   => $data['app_id'],
        //     "app_name"   => $data['app_name'],
        //     "app_icon"   => $data['app_icon'],
        //     "pkt_name"   => $data['pkt_name'],
        //     "app_type"   => $data['app_type'],
        //     "app_score"   => $data['app_score'],
        //     "app_size"   => $data['app_size'],
        //     "dl_count"   => $data['dl_count'],
        //     "app_version"   => $data['app_version'],
        //     "app_brief"   => $data['app_brief'],
        //     "snapshot"   => $data['snapshot'],
        //     "developer"   => $data['developer'],
        //     "app_pic"   => $data['app_pic'],
        //     "dl_url"   => $data['dl_url'],
        // );
        return self::loadApiData("appmarket/update_app", $data);
    }

    public function get_all_app()
    {
        $data = input('post.');
        //表单验证规则
        // $validation = new Validate([
        //      'token'  => 'require',
        //      'page' => 'require',
        //      'dev_sn'  => 'require',
        //      'dev_mac'  => 'require',
        //      'cpu_id' => 'require',
        //     ]
        // );
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' =>-900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "token"   => $data['token'],
        //     "page"   => $data['page'],
        //     "app_id"   => isset($data['app_id']) ? $data['app_id'] : "",
        //     "app_type"   => isset($data['app_type']) ? $data['app_type'] : "",
        //     "dev_sn"   => $data['dev_sn'],
        //     "dev_mac"   => $data['dev_mac'],
        //     "cpu_id"   => $data['cpu_id'],
        //     "pkg_name"   => isset($data['pkg_name']) ? $data['pkg_name'] : "",
        // );
        return self::loadApiData("appmarket/get_all_app", $data);
    }
     
    public function get_app()
    {
        $data = input('post.');
        //表单验证规则
        // $validation = new Validate([
        //      'token' => 'require',
        //      'app_name'  =>  'require',
        //      'dev_sn'  => 'require',
        //      'dev_mac'  => 'require',
        //      'cpu_id' => 'require',
        //     ]
        // );
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "token" => $data['token'],
        //     "app_name"   => $data['app_name'],
        //     "dev_sn"   => $data['dev_sn'],
        //     "dev_mac"   => $data['dev_mac'],
        //     "cpu_id"   => $data['cpu_id'],
        // );
        return self::loadApiData("appmarket/get_app", $data);
    }

    public function get_recommend()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/get_recommend", $data);
    }

    public function download()
    {
        $data = input('request.');
        //表单验证规则
        $validation = new Validate([
             'token' => 'require',
             'app_id' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "token" => $data['token'],
            "app_id" => $data['app_id'],
            "count"   => 1,
        );
        $return_data = self::loadApiData("appmarket/add_app_dlcount", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0 ) {
            return json($return_data);
        }
        $param = array(
            "token" => $data['token'],
            "app_id"   => $data['app_id'],
        );
        $return_data = self::loadApiData("appmarket/get_app", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0 ) {
            return json($return_data);
        }
        return redirect($return_data['data']['dl_url']);
    }

    public function app_on()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/app_on", $data);
    }

    public function app_off()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/app_off", $data);
    }

    public function add_app_dlcount()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/add_app_dlcount", $data);
    }

    public function add_group()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/add_group", $data);
    }

    public function update_group()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/update_group", $data);
    }

    public function del_group()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/del_group", $data);
    }

    public function query_all_group()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/query_all_group", $data);
    }

    public function query_group()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/query_group", $data);
    }

    public function get_app_by_appid()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/get_app_by_appid", $data);
    }

    public function del_app()
    {
        $data = input('post.');
        return self::loadApiData("appmarket/del_app", $data);
    }

}
