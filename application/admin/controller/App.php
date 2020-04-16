<?php
namespace app\admin\controller;
use think\facade\Cache;
use think\Validate;
use think\Request;

class App extends Common
{

    //
    public function app_add()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_name'  => 'require',
             'app_info'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_name"   => $data['app_name'],
            "app_info"   => $data['app_info'],
        );
        $return_data = self::loadApiData("app/app_add", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function add_version()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_id'  => 'require',
             'app_version'  => 'require',
             'app_download_url' => 'require',
             'ver_info' => 'require',
             'pkg_name' => 'require',
             'pkg_size' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_id"   => $data['app_id'],
            "app_version"   => $data['app_version'],
            "app_download_url"   => $data['app_download_url'],
            "ver_info"   => $data['ver_info'],
            "pkg_name"   => $data['pkg_name'],
            "pkg_size"   => $data['pkg_size'],
        );
        $return_data = self::loadApiData("app/ver_add", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function get_app_count()
    {
        $param = array(
            "time"   => time(),
        );
        $return_data = self::loadApiData("app/get_app_count", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function get_ver_count()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_id'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_id"   => $data['app_id'],
        );
        $return_data = self::loadApiData("app/get_ver_count", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function get_app()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'page'  => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' =>-900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page"   => $data['page'],
        );
        $return_data = self::loadApiData("app/get_app", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }
     
    public function get_ver()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_id' => 'require',
             'page'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_id" => $data['app_id'],
            "page"   => $data['page'],
        );
        $return_data = self::loadApiData("app/get_ver", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function add_app()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'sn' => 'require',
             'app_id' => 'require',
             'app_version'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "sn" => $data['sn'],
            "app_id" => $data['app_id'],
            "app_version"   => $data['app_version'],
        );
        $return_data = self::loadApiData("app/bind_app", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function app_update()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_id' => 'require',
             'vol_name' => 'require',
             'vol_value'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_id" => $data['app_id'],
            "vol_name" => $data['vol_name'],
            "vol_value"   => $data['vol_value'],
        );
        $return_data = self::loadApiData("app/app_update", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function ver_update()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_id' => 'require',
             'app_version' => 'require',
             'vol_name' => 'require',
             'vol_value'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "app_id" => $data['app_id'],
            "app_version" => $data['app_version'],
            "vol_name" => $data['vol_name'],
            "vol_value"   => $data['vol_value'],
        );
        $return_data = self::loadApiData("app/ver_update", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function refreshapp()
    {
        $param = array(
            "time"   => time(),
        );
        $return_data = self::loadApiData("app/get_app_count", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0 ) {
            return json_encode($return_data);
        }
        $count = isset($return_data['data']['count']) ? $return_data['data']['count'] : 0;
        $totalpage = ceil($count/10);
        $applist = array();
        for ($i = 0; $i < $totalpage; $i ++) {
            $param = array(
                "page"   => $i,
            );
            $return_data = self::loadApiData("app/get_app", $param);
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] != 0 ) {
                return json_encode($return_data);
            }
            if(isset($return_data['data'])) {
                for ($j = 0; $j < count($return_data['data']); $j ++) {
                    $applist[$return_data['data'][$j]['app_id']] = $return_data['data'][$j];
                    $this->refreshver($return_data['data'][$j]['app_id']);
                }   
            }
        }
        Cache::store('redis')->set("all_app_list", json_encode($applist));
        $data['status'] = 0;
        $data['data'] = $applist;
        return json_encode($data);
    }

    public function getallapp()
    {
        if (Cache::store('redis')->has("all_app_list")) {
            $list =  Cache::store('redis')->get("all_app_list");
            $data['status'] = 0;
            $data['data'] = json_decode($list, true);
            return json_encode($data);
        }
        return $this->refreshapp();
    }

    public function refreshver($id)
    {
        $param = array(
            "app_id"   => $id,
        );
        $return_data = self::loadApiData("app/get_ver_count", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0 ) {
            return json_encode($return_data);
        }
        $count = isset($return_data['data']['count']) ? $return_data['data']['count'] : 0;
        $totalpage = ceil($count/10);
        $verlist = array();
        for ($i = 0; $i < $totalpage; $i ++) {
            $param = array(
                "page"   => intval($i),
                "app_id"   => $id,
            );
            $return_data = self::loadApiData("app/get_ver", $param);
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] != 0 ) {
                return json_encode($return_data);
            }
            if(isset($return_data['data'])) {
                for ($j = 0; $j < count($return_data['data']); $j ++) {
                    //$verlist[]['version'] = implode(".", $return_data['data'][$j]['app_version']);
                    $verlist[]['version'] = $return_data['data'][$j]['app_version'];
                }   
            }
        }
        Cache::store('redis')->set("all_ver_list_".$id, json_encode($verlist));
        $data['status'] = 0;
        $data['data'] = $verlist;
        return json($data);
    }

    public function getallver($id)
    {
        if (Cache::store('redis')->has("all_ver_list_".$id)) {
            $list =  Cache::store('redis')->get("all_ver_list_".$id);
        } else {
            $list = $this->refreshver($id);
        }
        return json_decode($list, true);
    }


    //列表
    public function applist()
    {
        $applist = $this->getallapp();
        $applist = json_decode($applist, true);
        if ($applist['status'] !=0) {
            return json($applist);
        }
        $list = $applist['data'];
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $param = array(
            "page" => $page,
            "tb_name"  => 'app_version',
            "col_name" => ["app_id","app_version2","app_download_url","ver_info","pkg_name","pkg_size","time_create"],
            "where" => '',
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status']!=0) {
            return json($return_data);
        }
        for($i = 0; $i < count($return_data['result']['cols']); $i ++) {
            $return_data['result']['cols'][$i]['app_name'] = $list[$return_data['result']['cols'][$i]['app_id']]['app_name'];
            $return_data['result']['cols'][$i]['app_version'] = $return_data['result']['cols'][$i]['app_version2'];
        }
        return json($return_data);
        
    }

    public function saveapp()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'app_name' => 'require',
             'app_version' => 'require',
             'app_info' => 'require',
             'app_download_url' => 'require',
             'size' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        
        $param = array(
            "app_name"   => $data['app_name'],
            "app_info"   => $data['app_info'],
        );
        $return_data = self::loadApiData("app/app_add", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $app_id = $return_data['data'][0]['app_id'];
            $this->refreshapp();
        } else if ($return_data['status'] == -7) {
            $app_id = $return_data['data']['app_id'];
        } else {
            return json($return_data);
        }
        // $app_version = explode(".", $data['app_version']);
        // foreach ($app_version as $key => $value) {
        //     $app_version[$key] = intval($value);
        // }
        // if (count($app_version)<4) {
        //     $app_version[] = 0;
        // }
        $param = array(
            "app_id" => $app_id,
            "app_version" => $data["app_version"],
            "app_download_url" => $data["app_download_url"],
            "ver_info"   => $data['app_info'],
            "pkg_name"   => $data['app_name'],
            "pkg_size"   => $data['size'],
        );
        $return_data = self::loadApiData("app/ver_add", $param);
        $return_data = json_decode($return_data, true);
        $this->refreshver($app_id);
        if ($return_data['status'] !=0) {
            return json($return_data);
        }
        return json($return_data);
        
    }

    public function devicelist()
    {
        $data = input('post.');
        $page = isset($data['page']) ? intval($data['page']) : 0;
        $param = array(
            "page" => $page,
            "user_id"  => empty($data['user_id']) ? "" : $data['user_id'],
            "type"  => empty($data['type']) ? "" : intval($data['type']),
            "status"  => $data['status'] === "" ? "" : intval($data['status']),
            "sn_array"  => $data['sn_range'],
        );
        $return_data = self::loadApiData("app/find_app_all", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
        
    }

    public function devicectrl()
    {
        $data = input('post.');
        $param = array(
            "user_id"  => empty($data['user_id']) ? "" : $data['user_id'],
            "type"  => empty($data['type']) ? "" : intval($data['type']),
            "status"  => $data['status'] === "" ? "" : intval($data['status']),
            "sn_array"  => $data['sn_range'],
            "app_id" => $data['app_id'],
            "app_version" => $data['app_version'],
            "action_id" => $data['action_id'],
        );
        $return_data = self::loadApiData("app/ctrl_all", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
        
    }

    public function applistfortree()
    {
        $applist = $this->getallapp();
        $applist = json_decode($applist, true);
        if ($applist['status'] != 0) {
            return json($applist);
        }
        $list = array();
        if ($applist['data']) {
            foreach ($applist['data'] as $key => $value) {
                $list[] = [
                    "id" => $value['app_id'],
                    "app_name" => $value['app_name']
                ];
            }
        }
        $data['status'] = 0;
        $data['data'] = $list;
        return json($data);
    }

    public function verlistfortree()
    {
        $data = input('post.');
        $validation = new Validate([
             'id' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $id = $data['id'];
        if (Cache::store('redis')->has("all_ver_list_".$id)) {
            $list =  Cache::store('redis')->get("all_ver_list_".$id);
            $data['status'] = 0;
            $data['data'] = json_decode($list, true);
            return json($data);
        }
        return $this->refreshver($id);
    }

    public function getappbydev()
    {
        $data = input('post.');
        $validation = new Validate([
             'sn' => 'require',
             'page' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "sn" => $data['sn'],
            "page" => $data['page'],
        );
        $return_data = self::loadApiData("app/get_app_from_dev", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function getmactype()
    {
        $param = array(
            "current_ts" => time(),
        );
        $return_data = self::loadApiData("app/get_mac_type", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function otaversion()
    {
        $param = array(
            "page"      => 0,
            "tb_name"   => 'tb_action_log',
            "col_name"  => ["description"],
            "where"     => "action='手机App更新'",
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status']!=0) {
            return json($return_data);
        }
        if (count($return_data['result']['cols'])>0) {
            $return_data['result'] = $return_data['result']['cols'][0]['description'];
        } else {
            $return_data['result'] = '';
        }
        return json($return_data);
    }

    public function getappstatistics()
    {
        $param = array(
            "current_ts" => time(),
        );
        $return_data = self::loadApiData("app/get_app_statistics", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

}
