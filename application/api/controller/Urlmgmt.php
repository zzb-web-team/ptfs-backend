<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class Urlmgmt extends Common
{
	 public function get_token_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'secret'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        // $whitelist = config("xyjtoken.list");
        // if (!isset($whitelist[$data['id']])) {
        //     return json(['status' => -901, 'err_code' => -901,  'msg' => 'id not found']);
        // }
        // if ($data['secret'] != $whitelist[$data['id']]) {
        //     return json(['status' => -902, 'err_code' => -902,  'msg' => 'secret is error']);
        // }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'cloud_user',
            "col_name"  => "*",
            "where"     => "id='".$data['id']."'",
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
            return json(['status' => -901, 'err_code' => -901, 'msg' => 'id not found']);
        }
        if ($return_data['result']['cols'][0]['secret'] != $data['secret']) {
            return json(['status' => -902, 'err_code' => -902,  'msg' => 'secret is error']); 
        }
        $token = md5(time());
        Cache::store('redis')->set('url:token:'. $data['id'], $token, 60*60*24);
        return json(['status' => 0, 'err_code' => 0,  'msg' => 'success', 'data' => $token]);
    }

    public function add_url_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if (!Cache::store('redis')->has('url:token:'. $data['id'])) {
            return json(['status' => -902, 'err_code' => -902,  'msg' => 'token已过期']);
        }
        if (Cache::store('redis')->get('url:token:'. $data['id'])!= $data['token']) {
            return json(['status' => -903, 'err_code' => -903,  'msg' => 'token不正确']);
        }
        if ($data['data_count']>10) {
            return json(['status' => -904, 'err_code' => -904,  'msg' => 'size too large']);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadCloudData("url_mgmt/add_url", $param);
    }

    public function config_url_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if (!Cache::store('redis')->has('url:token:'. $data['id'])) {
            return json(['status' => -902, 'err_code' => -902,  'msg' => 'token已过期']);
        }
        if (Cache::store('redis')->get('url:token:'. $data['id'])!= $data['token']) {
            return json(['status' => -903, 'err_code' => -903,  'msg' => 'token不正确']);
        }
        if ($data['data_count']>10) {
            return json(['status' => -904, 'err_code' => -904,  'msg' => 'size too large']);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadCloudData("url_mgmt/config_url", $param);
    }

    public function add_domain_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if (!Cache::store('redis')->has('url:token:'. $data['id'])) {
            return json(['status' => -902, 'err_code' => -902,  'msg' => 'token已过期']);
        }
        if (Cache::store('redis')->get('url:token:'. $data['id'])!= $data['token']) {
            return json(['status' => -903, 'err_code' => -903,  'msg' => 'token不正确']);
        }
        if ($data['data_count']>10) {
            return json(['status' => -904, 'err_code' => -904,  'msg' => 'size too large']);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadCloudData("url_mgmt/add_domain", $param);
    }

    public function modify_domain_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if (!Cache::store('redis')->has('url:token:'. $data['id'])) {
            return json(['status' => -902, 'err_code' => -902,  'msg' => 'token已过期']);
        }
        if (Cache::store('redis')->get('url:token:'. $data['id'])!= $data['token']) {
            return json(['status' => -903, 'err_code' => -903,  'msg' => 'token不正确']);
        }
        if ($data['data_count']>10) {
            return json(['status' => -904, 'err_code' => -904,  'msg' => 'size too large']);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadCloudData("url_mgmt/modify_domain", $param);
    }

}
