<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;

class Ipfs extends Common
{

    public function ipfs_dataflow_query_conditions()
    {
        $param = array(
            "token" => 's',
        );
        $rs = self::testApiData("ipfs_node_ip_data/ipfs_dataflow_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ip_usage_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'ipfs_ip'  => 'require',
            'usage'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "ipfs_ip"  => $data['ipfs_ip'],
            "usage"  => $data['usage'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ip_usage_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_node_region_dist()
    {
        $param = array(
            "token" => 's',
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ipfs_node_region_dist", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_dataflow_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfsId'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfsId"  => $data['ipfsId'],
            "region"  => $data['region'],
            "city"  => $data['city'],
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ipfs_dataflow_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_dataflow_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfsId'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfsId"  => $data['ipfsId'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ipfs_dataflow_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_dataflow_avg_usage_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfsId'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfsId"  => $data['ipfsId'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "time_unit"  => $data['time_unit'],
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ipfs_dataflow_avg_usage_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_dataflow_avg_usage_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfsId'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'time_unit'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfsId"  => $data['ipfsId'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "time_unit"  => $data['time_unit'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_data/query_ipfs_dataflow_avg_usage_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }


    public function query_ip_store_usage_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'content_id'  => 'require',
            'usage'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "content_id"  => $data['content_id'],
            "usage"  => $data['usage'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_store/query_ip_store_usage_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

     public function query_ip_store_details_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "time_unit"  => $data['time_unit'],
        );
        $rs = self::testApiData("ipfs_node_ip_store/query_ip_store_details_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ip_store_details_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_store/query_ip_store_details_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

     public function query_ip_store_avg_usage_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "time_unit"  => $data['time_unit'],
        );
        $rs = self::testApiData("ipfs_node_ip_store/query_ip_store_avg_usage_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ip_store_avg_usage_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'ipfs_id'  => 'require',
            'region'  => 'require',
            'city'  => 'require',
            'pageNo'  => 'require',
            'pageSize'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "ipfs_id"  => $data['ipfs_id'],
            "region"  => $data['region'],
            "city"  => $data['city'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("ipfs_node_ip_store/query_ip_store_avg_usage_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_total_dataflow()
    {
        $data = input('post.');
        $validation = new Validate([
            'chanId'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "chanId" => $data['chanId'],
        );
        $rs = self::testApiData("channel_details/query_total_dataflow", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function ipfs_region_summary()
    {
        $data = input('post.');
        $validation = new Validate([
            'region'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "region" => $data['region'],
        );
        $rs = self::testApiData("ipfs_node_monit/ipfs_region_summary", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function ipfs_basic_info()
    {
        $data = input('post.');
        $validation = new Validate([
            'ipfsId'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "ipfsId" => $data['ipfsId'],
        );
        $rs = self::testApiData("ipfs_node_monit/ipfs_basic_info", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_ipfs_version_download()
    {
        $param = array(
            "page"      => 0,
            "page_size" => 10,
            "tb_name"   => 'tb_action_log',
            "col_name"  => "*",
            "where"     => '',
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] !=0) {
            return json($return_data);
        }
        if (!isset($return_data['result']['cols'][0])) {
            return json($return_data);
        }
        $data = [
            'version' => $return_data['result']['cols'][0]['action'],
            'url' => $return_data['result']['cols'][0]['description'],
            'md5' => $return_data['result']['cols'][0]['username'],
        ];
        return json(['status' => 0, 'data' => $data]);

    }

}