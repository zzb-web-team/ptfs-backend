<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;

class Resource extends Common
{

    public function accelerate_flow_query_conditions()
    {
        $data = input('post.');
        // $validation = new Validate([
        //     'chanId'  => 'require',
        //     'hashidSet' => 'require'
        // ]);
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "chanId" => $data['chanId'],
        //     'hashidSet' => $data['hashidSet']
        // );
        $rs = self::testApiData("resource_manage/accelerate_flow_query_conditions", $data);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function accelerate_flow()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'timeUnit'  => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "timeUnit"  => $data['timeUnit'],
            "acce" => isset($data['acce']) ? $data['acce'] : ""
        );
        $rs = self::testApiData("resource_manage/accelerate_flow", $data);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function accelerate_flow_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'pageNo' => 'require',
            'pageSize' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
            "acce" => isset($data['acce']) ? $data['acce'] : ""
        );
        $rs = self::testApiData("resource_manage/accelerate_flow_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
        
    }

    public function backsource_flow_query_conditions()
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
        $rs = self::testApiData("resource_manage/backsource_flow_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function backsource_flow()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'timeUnit'  => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "timeUnit"  => $data['timeUnit'],
            "acce" => isset($data['acce']) ? $data['acce'] : ""
        );
        $rs = self::testApiData("resource_manage/backsource_flow", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function pv_uv_query_conditions()
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
        $rs = self::testApiData("videoaccess_statistic/pv_uv_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function pv_uv_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'timeUnit'  => 'require',
            'terminalName' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "timeUnit"  => $data['timeUnit'],
            "acce" => isset($data['acce']) ? $data['acce'] : "",
            "terminalName" => $data['terminalName']
        );
        $rs = self::testApiData("videoaccess_statistic/pv_uv_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function region_query_conditions()
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
        $rs = self::testApiData("videoaccess_statistic/region_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_topregion_accesscnt_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'isp'  => 'require',
            'top'  => 'require',
            'pageNo' => 'require',
            'pageSize' => 'require',
            'terminalName' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "isp"  => $data['isp'],
            "top"  => $data['top'],
            "acce" => isset($data['acce']) ? $data['acce'] : "",
            "pageNo" => $data['pageNo'],
            "pageSize" => $data['pageSize'],
            "terminalName" => $data['terminalName']
        );
        $rs = self::testApiData("videoaccess_statistic/query_topregion_accesscnt_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function isp_query_conditions()
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
        $rs = self::testApiData("videoaccess_statistic/isp_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_topisp_accesscnt_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'top'  => 'require',
            'pageNo' => 'require',
            'pageSize' => 'require',
            'terminalName' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "top"  => $data['top'],
            "pageNo" => $data['pageNo'],
            "pageSize" => $data['pageSize'],
            "acce" => isset($data['acce']) ? $data['acce'] : "",
            "terminalName" => $data['terminalName']
        );
        $rs = self::testApiData("videoaccess_statistic/query_topisp_accesscnt_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_playtimes_conditions()
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
        $rs = self::testApiData("videoplay_statistic/query_playtimes_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_playtimes_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'timeUnit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "timeUnit"  => $data['timeUnit'],
            "acce" => isset($data['acce']) ? $data['acce'] : ""
        );
        $rs = self::testApiData("videoplay_statistic/query_playtimes_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_playdata_table()
    {
        $data = input('post.');
        // $validation = new Validate([
        //     'startTs'  => 'require',
        //     'endTs'  => 'require',
        //     'chanId'  => 'require',
        //     'fileName'  => 'require',
        //     'region'  => 'require',
        //     'isp'  => 'require',
        //     'pageNo'  => 'require',
        //     'pageSize'  => 'require'
        // ]);
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "startTs" => $data['startTs'],
        //     "endTs"  => $data['endTs'],
        //     "chanId"  => $data['chanId'],
        //     "fileName"  => $data['fileName'],
        //     "region"  => $data['region'],
        //     "isp"  => $data['isp'],
        //     "pageNo"  => $data['pageNo'],
        //     "pageSize"  => $data['pageSize'],
        //     "acce" => isset($data['acce']) ? $data['acce'] : ""
        // );
        $rs = self::testApiData("videoplay_statistic/query_playdata_table", $data);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }
   
    public function export_pv_uv_curve_file(){
        $data = input('post.');
        $validation = new Validate([
            'startTs'  => 'require',
            'endTs'  => 'require',
            'timeUnit'  => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "startTs" => $data['startTs'],
            "endTs"  => $data['endTs'],
            "chanId"  => isset($data['chanId']) ? $data['chanId'] : "*",
            "fileName"  => isset($data['fileName']) ? $data['fileName'] : "*",
            "region"  => isset($data['region']) ? $data['region'] : "*",
            "isp"  => isset($data['isp']) ? $data['isp'] :"*",
            "timeUnit"  => $data['timeUnit'],
            "acce" => isset($data['acce']) ? $data['acce'] : ""
        );
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_pv_uv_curve_file?'.http_build_query($data)]);
        // return self::testApiData("file_download/export_pv_uv_curve_file",$data);
    }

    public function export_playdata_table_file(){
        $data = input('post.');
        // $validation = new Validate([
        //     'startTs'  => 'require',
        //     'endTs'  => 'require'
        // ]);
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        // }
        // $param = array(
        //     "startTs" => $data['startTs'],
        //     "endTs"  => $data['endTs'],
        //     "chanId"  => isset($data['chanId']) ? $data['chanId'] : "*",
        //     "fileName"  => isset($data['fileName']) ? $data['fileName'] : "*",
        //     "region"  => isset($data['region']) ? $data['region'] : "*",
        //     "isp"  => isset($data['isp']) ? $data['isp'] :"*",
        //     "timeUnit"  => $data['timeUnit'],
        //     "acce" => isset($data['acce']) ? $data['acce'] : ""
        // );
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_playdata_table_file?'.http_build_query($data)]);
    }

    public function export_playtimes_curve_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_playtimes_curve_file?'.http_build_query($data)]);
    }

    public function export_topregion_accesscnt_curve_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_topregion_accesscnt_curve_file?'.http_build_query($data)]);
    }

    public function export_accelerate_flow_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_accelerate_flow_file?'.http_build_query($data)]);
    }

    public function export_accelerate_flow_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_accelerate_flow_table_file?'.http_build_query($data)]);
    }

    public function export_backsource_flow_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_backsource_flow_file?'.http_build_query($data)]);
    }

    public function export_dataflow_curve_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_dataflow_curve_file?'.http_build_query($data)]);
    }

    public function export_topisp_accesscnt_curve_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_topisp_accesscnt_curve_file?'.http_build_query($data)]);
    }

    public function manage_dataflow_curve(){
        $data = input('post.');
        return self::testApiData("resource_manage/manage_dataflow_curve", $data);
    }

    public function manage_dataflow_table(){
        $data = input('post.');
        return self::testApiData("resource_manage/manage_dataflow_table", $data);
    }

    public function sdk_flow(){
        $data = input('post.');
        return self::testApiData("resource_manage/sdk_flow", $data);
    }

    public function sdk_flow_control(){
        $data = input('post.');
        return self::testApiData("resource_manage/sdk_flow_control",$data);
    }

    public function sdk_flow_table(){
        $data = input('post.');
        return self::testApiData("resource_manage/sdk_flow_table",$data);
    }

    public function ipfs_flow_curve(){
        $data = input('post.');
        return self::testApiData("resource_manage/ipfs_flow_curve",$data);
    }

    public function ipfs_flow_table(){
        $data = input('post.');
        return self::testApiData("resource_manage/ipfs_flow_table",$data);
    }

    public function query_accelcnt_ranking(){
        $data = input('post.');
        return self::testApiData("resource_manage/query_accelcnt_ranking",$data);
    }

    public function query_dataflow_ranking(){
        $data = input('post.');
        return self::testApiData("resource_manage/query_dataflow_ranking",$data);
    }

    public function video_info_statistics(){
        $data = input('post.');
        return self::testApiData("resource_manage/video_info_statistics",$data);
    }

    public function video_exception_statistics(){
        $data = input('post.');
        return self::testApiData("resource_manage/video_exception_statistics",$data);
    }

    public function export_sdk_flow_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_sdk_flow_table_file?'.http_build_query($data)]);
    }

    public function export_sdk_flow_table_user_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_sdk_flow_table_user_file?'.http_build_query($data)]);
    }

    public function export_sdk_flow_control_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_sdk_flow_control_file?'.http_build_query($data)]);
    }

    public function export_sdk_flow_control_user_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_sdk_flow_control_user_file?'.http_build_query($data)]);
    }

    public function export_manage_dataflow_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_manage_dataflow_table_file?'.http_build_query($data)]);
    }

    public function export_ipfs_flow_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_ipfs_flow_table_file?'.http_build_query($data)]);
    }

    public function export_accelcnt_ranking_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_accelcnt_ranking_table_file?'.http_build_query($data)]);
    }

    public function export_dataflow_ranking_table_file(){
        $data = input('post.');
        return json(['status' => 0, 'err_code' => 0,  'msg' => config("ipfs.apiurl4").'file_download/export_dataflow_ranking_table_file?'.http_build_query($data)]);
    }
    
}
