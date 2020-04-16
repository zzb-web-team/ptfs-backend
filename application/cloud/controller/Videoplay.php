<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;

class Videoplay extends Common
{

    public function query_accelerate_log()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileUrl'  => 'require',
            'userIp'  => 'require',
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
            "fileUrl"  => $data['fileUrl'],
            "userIp"  => $data['userIp'],
            "requestFlag" => isset($data['requestFlag']) ? $data['requestFlag'] : 2,
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("videoplay_accelerate/query_accelerate_log", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_videoplay_log()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileId'  => 'require',
            'fileName'  => 'require',
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
            "fileId"  => $data['fileId'],
            "fileName"  => $data['fileName'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("videoplay_accelerate/query_videoplay_log", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function app_usage_region_dist()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
        );
        $rs = self::testApiData("grapefruit_analyse/app_usage_region_dist", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function app_version_dist_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
        );
        $rs = self::testApiData("grapefruit_analyse/app_version_dist_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function app_version_dist_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
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
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("grapefruit_analyse/app_version_dist_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function device_online_curve()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'flag'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "flag"  => $data['flag'],
        );
        $rs = self::testApiData("grapefruit_analyse/device_online_curve", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function device_online_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'flag'  => 'require',
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
            "flag"  => $data['flag'],
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("grapefruit_analyse/device_online_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function app_version_online_dist()
    {
        $data = input('post.');
        $param = array(
            "ts" => time(),
        );
        $rs = self::testApiData("grapefruit_analyse/app_version_online_dist", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function query_logfile_table()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId' => 'require',
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
            "pageNo"  => $data['pageNo'],
            "pageSize"  => $data['pageSize'],
        );
        $rs = self::testApiData("logfile_download/query_logfile_table", $param);
        if (!$rs) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'IPFS服务错误']);
        }
        return $rs;
    }

    public function download_logfile()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'fileName'  => 'require',
            'chanId' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "fileName"  => $data['fileName'],
            "chanId"  => $data['chanId'],
        );
        return json(['status' => 0, 'err_code' => 0,  'msg' => 'http://39.100.131.247:8090/logfile_download/download_logfile?'.http_build_query($param)]);
    }

    public function export_videoaccel_file()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileUrl'  => 'require',
            'userIp'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileUrl"  => $data['fileUrl'],
            "userIp"  => $data['userIp'],
        );
        return json(['status' => 0, 'err_code' => 0,  'msg' => 'http://39.100.131.247:8090/videoplay_accelerate/export_videoaccel_file?'.http_build_query($param)]);
    }

    public function export_videoplay_file()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_ts'  => 'require',
            'end_ts'  => 'require',
            'chanId'  => 'require',
            'fileId'  => 'require',
            'fileName'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts" => $data['start_ts'],
            "end_ts"  => $data['end_ts'],
            "chanId"  => $data['chanId'],
            "fileId"  => $data['fileId'],
            "fileName"  => $data['fileName'],
        );
        return json(['status' => 0, 'err_code' => 0,  'msg' => 'http://39.100.131.247:8090/videoplay_accelerate/export_videoplay_file?'.http_build_query($param)]);
    }

}
