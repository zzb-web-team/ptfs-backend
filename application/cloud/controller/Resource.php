<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;

class Resource extends Common
{

    public function accelerate_flow_query_conditions()
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
        $rs = self::testApiData("resource_manage/accelerate_flow_query_conditions", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function accelerate_flow()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "time_unit"  => $data['time_unit'],
        );
        $rs = self::testApiData("resource_manage/accelerate_flow", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function accelerate_flow_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'pageNo' => 'require',
            'pageSize' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
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
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "time_unit"  => $data['time_unit'],
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
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "time_unit"  => $data['time_unit'],
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
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'isp'  => 'require',
            'top'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "isp"  => $data['isp'],
            "top"  => $data['top'],
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
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'top'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "top"  => $data['top'],
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
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
            'time_unit'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "time_unit"  => $data['time_unit'],
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
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileName'  => 'require',
            'region'  => 'require',
            'isp'  => 'require',
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
            "chanId"  => $data['chanId'],
            "fileName"  => $data['fileName'],
            "region"  => $data['region'],
            "isp"  => $data['isp'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("videoplay_statistic/query_playdata_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }
   

}
