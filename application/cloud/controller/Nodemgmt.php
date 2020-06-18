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
        return self::loadCloudData("node_mgmt/node_distribute", $param);
    }

    public function query_node()
    {
        $data = input('post.');
       // $validation = new Validate([
       //     'page'  => 'require',
       // ]);
       //  //验证表单
       // if(!$validation->check($data)){
       //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
       // }
       // $param = array(
       //     "page" => isset($data['page']) ? $data['page'] : 0,
       //     "nodeId" => isset($data['nodeId']) ? $data['nodeId'] : "",
       //     "ip" => isset($data['ip']) ? $data['ip'] : "",
       //     "state" => isset($data['state']) ? $data['state'] : "",
       //     "province" => isset($data['province']) ? $data['province'] : "",
       //     "city" => isset($data['city']) ? $data['city'] : "",
       //  );
        return self::loadCloudData("node_mgmt/query_node", $data);
    }

    public function query_resource()
    {
        $data = input('post.');
        // $validation = new Validate([
        //     'type' => 'require',
        //     'url_label' => 'require',
        //     'user_id' => 'require',
        //     'token' => 'require',
        //     'p2pID' => 'require',
        //     'localIP' => 'require',
        //     'localPort' => 'require',
        //     'natIP' => 'require',
        //     'natPort' => 'require'
        // ]);
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = [
        //     'type' => $data['type'],
        //     'url_label' => $data['url_label'],
        //     'user_id' => $data['user_id'],
        //     'token' => $data['token'],
        //     'p2pID' => $data['p2pID'],
        //     'localIP' => $data['localIP'],
        //     'localPort' => $data['localPort'],
        //     'natIP' => $data['natIP'],
        //     'natPort' => $data['natPort']
        // ];
        return self::loadCloudData("node_mgmt/query_resource", $data);
    }

    public function query_rootnode()
    {
        $data = input('post.');
        $validation = new Validate([
            'nodeId' => 'require',
            'ip' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'nodeId' => $data['nodeId'],
            'ip' => $data['ip']
        ];
        return self::loadCloudData("node_mgmt/query_rootnode", $param);
    }

    public function resource_refresh()
    {
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'type' => 'require',
            'area' => 'require',
            'url_name' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => $data['buser_id'],
            'type' => $data['type'],
            'area' => $data['area'],
            'url_name' => $data['url_name'],
        ];
        return self::loadCloudData("node_mgmt/resource_refresh", $param);
    }

    public function query_relaysrv()
    {
        $data = [
            'timestamp' => time()
        ];

        return self::loadCloudData("node_mgmt/query_relaysrv", $data);
    }

     public function refresh_state()
    {
        $data = input('post.');
        // $validation = new Validate([
        //     'url' => 'require',
        //     'buser_id' => 'require',
        //     'refresh_type' => 'require',
        //     'state' => 'require',
        //     'start_time' => 'require',
        //     'end_time' => 'require'
        // ]);
        //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = [
        //     'url_name' => $data['url_name'],
        //     'buser_id' => $data['buser_id'],
        //     'refresh_type' => $data['refresh_type'],
        //     'state' => $data['state'],
        //     'start_time' => $data['start_time'],
        //     'end_time' => $data['end_time'],
        // ];
        return self::loadCloudData("node_mgmt/refresh_state", $data);
    }

    public function refresh_state_admin(){
        $data = input('post.');
        return self::loadCloudData("node_mgmt/refresh_state_admin",$data);
    }

     public function uploadpfts()
    {
        $data = input('post.');
        $validation = new Validate([
            'url' => 'require',
            'type' => 'require',
            'uuid' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'url' => $data['url'],
            'type' => $data['type'],
            'uuid' => $data['uuid'],
        ];
        return self::loadCloudData("node_mgmt/uploadpfts", $param);
    }

     public function query_nodefilter()
    {
        $data = input('post.');
        $validation = new Validate([
            'type' => 'require',
            'page' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'type' => $data['type'],
            'page' => $data['page'],
        ];
        return self::loadCloudData("node_mgmt/query_nodefilter", $param);
    }

     public function filter_node()
    {
        $data = input('post.');
        $validation = new Validate([
            'opt_flag' => 'require',
            'nodes' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'opt_flag' => $data['opt_flag'],
            'nodes' => $data['nodes'],
        ];
        return self::loadCloudData("node_mgmt/filter_node", $param);
    }

    public function query_for_ssh(){
        $data = input('post.');
        $param = [
            'nodeId' => isset($data['nodeId']) ? $data['nodeId'] : '',
            'p2pID' => isset($data['p2pID']) ? $data['p2pID']."" : '',
            'localIP' => isset($data['localIP']) ? $data['localIP'] : '',
            'localPort' => isset($data['localPort']) ? $data['localPort'] : 0,
            "natIP" => isset($data['natIP']) ? $data['natIP'] : '',
            "natPort" => isset($data['natPort']) ? $data['natPort'] : 0
        ];
        return self::loadCloudData("node_mgmt/query_for_ssh",$param);
    }

    public function node_ctrl(){
        $data = input('post.');
        return self::loadCloudData("node_mgmt/node_ctrl",$data);
    }

    public function get_nodetype_enum(){
        $data = input('post.');
        return self::loadCloudData("node_mgmt/get_nodetype_enum",$data);
    }

}
