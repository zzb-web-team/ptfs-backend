<?php
namespace app\cloud\controller;
use think\facade\Cache;
use think\Validate;

class Urlmgmt2 extends Common
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
            "buser_id" => $data['id']."",
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
            'domain_id'  => 'require',
            'domain'  => 'require',
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
        $param = [
            'buser_id' => $data['id'],
            'domain_id' => $data['domain_id'],
            'domain' => $data['domain']
        ];
        return self::loadCloudData("url_mgmt/modify_domain", $param);
    }

    public function query_domain_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
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
        $param = [
            'buser_id' => $data['id'],
            'domain' => isset($data['domain']) ? $data['domain'] : "",
            'state' => isset($data['state']) ? $data['state'] : "",
            'start_time' => isset($data['start_time']) ? $data['start_time'] : 0,
            'end_time' => isset($data['end_time']) ? $data['end_time'] : 0,
            'page' => isset($data['page']) ? $data['page'] : 0,
            'order' => isset($data['order']) ? $data['order'] : 0
        ];
        return self::loadCloudData("url_mgmt/query_domain",$param);
    }

    public function query_url_for_rest()
    {
        $data = input('post.');
        $validation = new Validate([
            'id' => 'require',
            'token' => 'require',
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
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "buser_id" => $data['id'],
            "url_name" => isset($data['url_name']) ? $data['url_name'] : "",
            "start_time" => isset($data['start_time']) ? $data['start_time'] : "",
            "end_time" => isset($data['end_time']) ? $data['end_time'] : "",
            "state" => isset($data['state']) ? $data['state'] : "",
            "order" => isset($data['order']) ? $data['order'] : 0,
        );
        return self::loadCloudData("url_mgmt/query_url", $param);
    }

}
