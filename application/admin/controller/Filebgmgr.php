<?php
namespace app\admin\controller;
use think\Validate;

class Filebgmgr extends Common
{

    //查询设备地域分布 
    public function city_heat()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("file_bgmgr/city_heat", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //查询当天top10拉取次数排行榜 
    public function file_dl_range()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("file_bgmgr/file_dl_range", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }
    
    //查询当天top10拉取流量的设备排行榜 
    public function time_heat()
    {
        $param = array(
            "token" => 'sss'
        );
        $return_data = self::loadApiData("file_bgmgr/time_heat", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //根据条件，查询当前设备统计明细 
    public function file_outline()
    {
        $data = input('post.');
        $param = array(
            "file_name"  => empty($data['file_name']) ? "" : $data['file_name'],
            "owner"  => empty($data['owner']) ? "" : $data['owner'],
            "page"  => empty($data['page']) ? 0 : $data['page'],
        );
        $return_data = self::loadApiData("file_bgmgr/file_outline", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    //根据条件，查询当前设备统计明细 
    public function file_store()
    {
        $data = input('post.');
        $param = array(
            "name"  => empty($data['name']) ? "" : $data['name'],
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "page"  => empty($data['page']) ? 0 : $data['page'],
            "type"  => empty($data['type']) ? "" : $data['type'],
            "start"  => empty($data['start']) ? "" : $data['start'],
            "end"  => empty($data['end']) ? "" : $data['end'],
        );
        $return_data = self::loadApiData("file_bgmgr/file_store", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function file_dlinfo()
    {
        $data = input('post.');
        $param = array(
            "name"  => empty($data['name']) ? "" : $data['name'],
            "dev_sn"  => empty($data['dev_sn']) ? "" : $data['dev_sn'],
            "page"  => empty($data['page']) ? 0 : $data['page'],
            "appid"  => empty($data['appid']) ? "" : $data['appid'],
             "area"  => empty($data['area']) ? "" : $data['area'],
            "start"  => empty($data['start']) ? "" : $data['start'],
            "end"  => empty($data['end']) ? "" : $data['end'],
        );
        $return_data = self::loadApiData("file_bgmgr/file_dlinfo", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }


}
