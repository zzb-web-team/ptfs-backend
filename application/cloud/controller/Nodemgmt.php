<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;

class Nodemgmt extends Common
{

    public function node_distribute()
    {
        $data = input('post.');
        $validation = new Validate([
            'province'  => 'require',
            'page'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "province" => $data['province'],
            "page"  => $data['page'],
        );
        return self::loadApiData("node_mgmt/node_distribute", $param);
    }

    public function query_node()
    {
        $data = input('post.');
       // $validation = new Validate([
         //   'page'  => 'require',
       // ]);
        //验证表单
       // if(!$validation->check($data)){
       //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
       // }
       // $param = array(
        //    "page" => isset($data['page']) ? $data['page'] : 0,
        //    "nodeId" => isset($data['nodeId']) ? $data['nodeId'] : "",
        //    "ip" => isset($data['ip']) ? $data['ip'] : "",
        //    "state" => isset($data['state']) ? $data['state'] : "",
        //    "province" => isset($data['province']) ? $data['province'] : "",
        //    "city" => isset($data['city']) ? $data['city'] : "",
        //);
        return self::loadApiData("node_mgmt/query_node", $data);
    }

    public function query_resource()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/query_resource", $data);
    }

    public function query_rootnode()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/query_rootnode", $data);
    }

    public function resource_refresh()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/resource_refresh", $data);
    }

    public function query_relaysrv()
    {
        $data = [
            'timestamp' => time()
        ];

        return self::loadApiData("node_mgmt/query_relaysrv", $data);
    }

     public function refresh_state()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/refresh_state", $data);
    }

     public function uploadpfts()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/uploadpfts", $data);
    }

     public function query_nodefilter()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/query_nodefilter", $data);
    }

     public function filter_node()
    {
        $data = input('post.');

        return self::loadApiData("node_mgmt/filter_node", $data);
    }

}
