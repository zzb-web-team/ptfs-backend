<?php
namespace app\admin\controller;
use think\Validate;

class Rom extends Common
{
    public function queryRom()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $where = "1";
        $where .= $data['rom_type'] == -1 ? "" : " and rom_type = " . $data['rom_type'];
        $where .= $data['time_start']===null ? "" : " and time_create BETWEEN " . $data['time_start'] . " and " .$data['time_end'];
        $param = array(
            "page"      => $page,
            "page_size" => 10,
            "tb_name"   => 'tb_rom_update_info',
            "col_name"  => ["id","equip_type","rom_version","version_name","rom_url","rom_size","md5","hashid","rom_desc","push_mod","rom_type","time_create","time_update","status","ptfs_file_upload_status"],
            "where"     => $where,
            "order"     => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0 ) {
            $data = array();
            for($i = 0; $i < count($return_data['result']['cols']); $i ++) {
                $version = $return_data['result']['cols'][$i]['rom_version'];
                if (!isset($data[$version]['rom_version'])) {
                    $data[$version]['rom_version']  = $version;
                }
                if (!isset($data[$version]['push_mod'])) {
                    $data[$version]['push_mod']     = $return_data['result']['cols'][$i]['push_mod'];
                }
                if (!isset($data[$version]['rom_desc'])) {
                    $data[$version]['rom_desc']     = $return_data['result']['cols'][$i]['rom_desc'];
                }
                if (!isset($data[$version]['status'])) {
                    $data[$version]['status']       = $return_data['result']['cols'][$i]['status'];
                }
                if (!isset($data[$version]['time_create'])) {
                    $data[$version]['time_create']  = $return_data['result']['cols'][$i]['time_create'];
                }
                if (!isset($data[$version]['time_update'])) {
                    $data[$version]['time_update']  = $return_data['result']['cols'][$i]['time_update'];
                }
                $data[$version]['equip_type'][]     = $return_data['result']['cols'][$i]['equip_type'];
                $data[$version]['md5'][]            = $return_data['result']['cols'][$i]['md5'];
                $data[$version]['hashid'][]         = $return_data['result']['cols'][$i]['hashid'];
                $data[$version]['rom_size'][]       = $return_data['result']['cols'][$i]['rom_size'];
                $data[$version]['rom_url'][]        = $return_data['result']['cols'][$i]['rom_url'];
                $data[$version]['version_name'][]   = $return_data['result']['cols'][$i]['version_name'];
                $data[$version]['ptfs_file_upload_status'][]   = $return_data['result']['cols'][$i]['ptfs_file_upload_status'];
                $data[$version]['rom_type'][] = $return_data['result']['cols'][$i]['rom_type'];
            }
            $list = array();
            if (count($data)>0) {
                foreach ($data as $key => $value) {
                   $list[] = $value;
                }
            } 
            $return_data['data'] = $list;  
        }
        return json($return_data);
    }

    public function findRomById()
    {
        $data = input('post.');
        $validation = new Validate([
            'rom_version'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page"      => 0,
            "page_size" => 10,
            "tb_name"   => 'tb_rom_update_info',
            "col_name"  => ["id","equip_type","rom_version","version_name","rom_url","rom_size","md5","hashid","rom_desc","push_mod","time_create","time_update"],
            "where"     => "rom_version='".$data['rom_version']."'",
            "order"     => '',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $return_data['result'] = $return_data['result']['cols'][0];
        return json($return_data);
    }

    public function updateRom()
    {
        $data = input('post.');
        $validation = new Validate([
            'rom_version'   => 'require',
            'rom_desc'      => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"  => 'tb_rom_update_info',
            "update" => ["rom_desc"],
            "col_value" => [
                $data["rom_desc"], 
            ],
            "where" => "rom_version='".$data['rom_version']."'",
        );
        $return_data = self::loadApiData("store/update_table", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function uploadMD5(){
        $data = input('post.');
        $validation = new Validate([
            'md5'   => 'require',
            'rom_version' => 'require',
            'rom_type' => 'require'
        ]);
         //验证表单
         if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $insert = []; 
        for($i=0;$i<count($data['md5']);$i++){
        $insert[] = [
            $data['rom_version'],$data['rom_type'],$data['md5'][$i]['path'],$data['md5'][$i]['md5']
        ];
        }
        $param = array(
            "tb_name"   => 'tb_rom_process_md5_info',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        if(!$return_data){
            return json(['status' => -900,'msg'=>'服务器错误']);
        }
        return $return_data;
    }

    public function updateMod()
    {
        $data = input('post.');
        $validation = new Validate([
            'rom_version'   => 'require',
            'push_mod'      => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"   => 'tb_rom_update_info',
            "update"    => [
                "push_mod"
            ],
            "col_value" => [
                $data["push_mod"]
            ],
            "where" => "rom_version='".$data['rom_version']."'",
        );
        $return_data = self::loadApiData("store/update_table", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function deleteRom()
    {
        $data = input('post.');
        $validation = new Validate([
             'rom_version'  =>  'require',
             'rom_type' => 'require'
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"   => 'tb_rom_update_info',
            "where"     => "rom_version='".$data['rom_version']."' and rom_type = " . $data['rom_type'],
        );
        $return_data = self::loadApiData("store/delete_record", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        //删除该版本下的灰度数据
        $param = array(
            "tb_name"   => 'tb_rom_publish_log',
            "where"     => "version_group='" . $data['rom_version'] . "' and push_type='灰度发布'",
        );
        return $return_data = self::loadApiData("store/delete_record", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status']!=0) {
            return json($return_data);
        }
        // return self::actionLog("删除ROM升级", $data['rom_version'], "临时用户".time());
        
    }


    public function saveRom()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'data'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $insert = array();

        for ($i=0;$i<count($data['data']);$i++) {
            $number = $this->versiontonumber($data['data'][$i]['rom_version']);
            // try {
            //     $ptfs = file_get_contents("http://127.0.0.1:7010/add/?path=/home/demon/public/download/".$data['data'][$i]['rom_version']."/". $data['data'][$i]['version_name']."&key=".$data['data'][$i]['md5']);
            //     $is_json = $this->is_json($ptfs);
            //     if ($is_json) {
            //         $ptfs = json_decode($ptfs, true);
            //         $hashid = $ptfs['hash'];
            //     } else {
            //         $hashid = '';
            //     }
            // } catch (\Exception $e) {
                $hashid = '';
            //}
            
            $insert[] = [
                $data['data'][$i]['equip_type'], $data['data'][$i]['rom_version'], $data['data'][$i]['version_name'], $data['data'][$i]['rom_url'], $data['data'][$i]['rom_size'], $data['data'][$i]['md5'], $hashid, $data['data'][$i]['rom_desc'], $data['data'][$i]['push_mod'], 0, $number, 0,$data['data'][$i]['dev_type']
            ];
        }
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'tb_rom_update_info',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function getpacketbyversion()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'version'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'tb_rom_update_info',
            "col_name"  => ["equip_type","rom_version","version_name","rom_url","hashid","rom_size","md5","rom_desc","push_mod"],
            "where"     => "rom_version='".$data['version']."'",
            "order"     => 'equip_type asc,version_name asc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $param = array(
            "page"      => 0,
            "page_size" => 10,
            "tb_name"   => 'tb_rom_update_info',
            "col_name"  => ["rom_version"],
            "where"     => 'status = 1',
            "order"     => 'number desc',
        );
        $return_data2 = self::loadApiData("store/find_table", $param);
        $return_data2 = json_decode($return_data2, true);
        if ($return_data2['status'] == 0 && count($return_data2['result']['cols'])>0){
            $high_version = $return_data2['result']['cols'][0]['rom_version'];
        } else {
            $high_version = "线上无版本发布";
        }
        $return_data['result']['high_version'] = $high_version;
        return json($return_data);
    }

    public function getversionbak()
    {
        $param = array( 
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["rom_version","number"],
            "where" => 'status = 1',
            "order" => 'number desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0 && count($return_data['result']['cols'])>0){
            $high_version = $return_data['result']['cols'][0]['rom_version'];
            $number = $return_data['result']['cols'][0]['number'];
        } else {
            $high_version = "0.0.0";
            $number = 0;
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["version", "rom_version", "ptfs_file_upload_status"],
            "col_alias" => ["distinct(rom_version) as version", "rom_version", "ptfs_file_upload_status"],
            "where" => "number>".$number,
            "group" => 'rom_version',
        );
        $return_data = self::loadApiData("store/find_table_ex", $param);
        $return_data = json_decode($return_data, true);
        $return_data['result']['high_version'] = $high_version;
        return json($return_data);
    }

    // public function rollback()
    // {
    //     $data = input('post.');
    //     $validation = new Validate([
    //         'rom_version'   => 'require',
    //     ]);
    //     //验证表单
    //     if(!$validation->check($data)){
    //         return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
    //     }
    //     $param = array(
    //         "tb_name"   => 'tb_rom_update_info',
    //         "update"    => [
    //             "status"
    //         ],
    //         "col_value" => [
    //             0
    //         ],
    //         "where" => "rom_version='".$data['rom_version']."'",
    //     );
    //     $return_data = self::loadApiData("store/update_table", $param);
    //     $return_data = json_decode($return_data, true);
    //     return json($return_data);
    // }

    public function rollback()
    {
        $data = input('post.');
        $validation = new Validate([
            'id'   => 'require',
            'rom_version'   => 'require',
            'atonce'   => 'require',
            'timing'   => 'require',
            'dev_type'   => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $number = $this->versiontonumber($data['rom_version']);

        if (!$data['push_key']) {
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"  => 'ipfs_xyj_rom_publish',
                "col_name" => "*",
                "where" => "status = 1 and act_type = 1 and dev_type = ".intval($data['dev_type'])." and push_type = '正式发布'",
                "order" => 'id desc',
            );
            $return_data =  self::loadApiData("store/find_table", $param);
            $return_data = json_decode($return_data,true);
            if(count($return_data['result']['cols']) == 1){
                return json(['status' => 1,'msg' => '当前已是最低版本,无法撤回']);
            }

            //全网撤回
            $param = array(
                "tb_name"   => 'ipfs_xyj_rom_publish',
                "update"    => [
                    "rom_version", "atonce", "timing", "act_type", "status", "number"
                ],
                "col_value" => [
                    $data['rom_version'], $data['atonce'], $data['timing'], 2, $data['atonce'], $number
                ],
                "where" => "dev_type=".intval($data['dev_type'])." and push_type = '正式发布' and number>=".$number ,
            );
            return self::loadApiData("store/update_table", $param);
        }

        $param = array(
            "tb_name"   => 'ipfs_xyj_rom_publish',
            "update"    => [
                "rom_version", "atonce", "timing", "act_type", "status", "number"
            ],
            "col_value" => [
                $data['rom_version'], $data['atonce'], $data['timing'], 2, $data['atonce'], $number
            ],
            "where" => "id=".intval($data['id']),
        );
        $return_data = self::loadApiData("store/update_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_rom_publish_user',
            "update"    => [
                "number"
            ],
            "col_value" => [
                $number
            ],
            "where" => "push_key='".$data['push_key']."'",
        );
        return self::loadApiData("store/update_table", $param);

    }

    public function getversion()
    {
        $data = input('post.');
        $validation = new Validate([
            'dev_type'  =>  'require',
            'rom_version' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $to_version = isset($data['to_version']) ? $data['to_version'] : "";
        $number = $this->versiontonumber($data['rom_version']);
        if (!$to_version) {
            $where = "number>".$number;
        } else {
            $number2 = $this->versiontonumber($to_version);
            $big = $number > $number2 ? $number : $number2;
            $small = $big == $number ? $number2 : $number;
            $where = "number >= ".$small. " and  number <".$big;
        }

        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 50,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["rom_version", "ptfs_file_upload_status","id"],
            "col_alias" => ["distinct(rom_version) as rom_version", "sum(ptfs_file_upload_status) as ptfs_file_upload_status", "count(id) as id"],
            "where" => $where . " and rom_type = " . $data['dev_type'],
            "group" => 'rom_version',
        );
        return self::loadApiData("store/find_table_ex", $param);

        // $return_data = json_decode($return_data, true);
        // if ($return_data['status']!=0) {
        //     return json($return_data);
        // }
        // $data = $return_data['result']['cols'];
        // $list = array();
        // for($i=0;$i<count($data);$i++) {
        //     $list[$i]['rom_version'] = $data[$i]['rom_version'];
        //     if ($data[$i]['ptfs_file_upload_status'] == $data[$i]['id']) {
        //         $list[$i]['ptfs_file_upload_status'] = 1;
        //     } else {
        //         $list[$i]['ptfs_file_upload_status'] = 0;
        //     }
        // }
        // $return_data['result']['cols'] = $list;
        // $return_data['result']['high_version'] = $high_version;
        // return json($return_data);
    }

    // public function publish()
    // {
    //     $data = input('post.');
    //     //表单验证规则
    //     $validation = new Validate([
    //          'version_group'  =>  'require',
    //          'push_type'  =>  'require',
    //          'push_mod'  =>  'require',
    //          // 'descript'  =>  'require',
    //         ]
    //     );
    //     //验证表单
    //     if(!$validation->check($data)){
    //         return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
    //     }
    //     $sn_list = trim($data['sn_list']);
    //     $sn_arr = explode("\n", $sn_list);
    //     $insert = array();
    //     $target = array();
    //     $push_key = time();
    //     $descript = isset($data['descript']) ? $data['descript'] : "";
    //     for ($i=0;$i<count($sn_arr);$i++) {
    //         $insert[] = [
    //             $data['version_group'], $data['push_type'], $data['push_mod'], $descript, $sn_arr[$i], $push_key
    //         ];
    //         $target[] = $sn_arr[$i];
    //     }
    //     $param = array(
    //         "tb_name"  => 'tb_rom_publish_log',
    //         "insert" => $insert
    //     );
    //     $return_data = self::loadApiData("store/insert_table", $param);
    //     $return_data = json_decode($return_data, true);
    //     if ($return_data['status'] == 0) {
    //         if ($data['push_type'] == '全网发布') {
    //            $param = array(
    //                 "tb_name"  => 'tb_rom_update_info',
    //                 "update" => ["status","push_mod"],
    //                 "col_value" => [
    //                     1, $data['push_mod']
    //                 ],
    //                 "where" => "rom_version='".$data['version_group']."'",
    //             );
    //             $return_data = self::loadApiData("store/update_table", $param);
    //             $return_data = json_decode($return_data, true); 
    //             $target = "*";
    //         } else {
    //             $target = implode(",", $target);
    //         }

    //         return $this->sendcmd($target, $data['version_group']);
    //     }
    //     return json($return_data);
    // }

    public function publish()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'rom_version'  =>  'require',
            'dev_type'  =>  'require',
            'push_type'  =>  'require',
            'push_mod'  =>  'require',
            'description'  =>  'require',
            'atonce'  =>  'require',
            'timing'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        
        $number = $this->versiontonumber($data['rom_version']);
        if ($data['push_type'] == '正式发布') {
            $insert = array();
            $insert[] = [
                1, 
                $data['rom_version'], 
                $data['dev_type'], 
                $data['push_type'], 
                $data['push_mod'], 
                $data['description'],
                $data['atonce'],
                $data['atonce'], 
                $data['timing'],
                $number, 
                ''
            ];
            if (!$insert) {
                return json(['status' => -900, 'msg' => '缺少参数']);
            }
            $param = array(
                "tb_name"   => 'ipfs_xyj_rom_publish',
                "insert"    => $insert
            );
            return self::loadApiData("store/insert_table", $param);
        }
        $push_key = md5(time());
        $sn_list = isset($data['sn_list']) ? $data['sn_list'] : [];
        if (!$sn_list) {
            return json(['status' => -900, 'msg' => '灰度发布必须指定至少1个设备']);
        }
        $insert = array();
        $insert[] = [
            1, 
            $data['rom_version'], 
            $data['dev_type'], 
            $data['push_type'], 
            $data['push_mod'], 
            $data['description'],
            $data['atonce'],
            $data['atonce'], 
            $data['timing'],
            $number, 
            $push_key
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_rom_publish',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }

        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $insert = array();
        for($i = 0; $i<count($sn_list); $i++) {
            $insert[] = [
                $push_key, 
                $sn_list[$i]."",
                $number
            ];
        }
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_rom_publish_user',
            "insert"    => $insert
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function autopush()
    {
        $param = array(
            "page" => 0,
            "page_size" => 100,
            "tb_name"  => 'ipfs_xyj_rom_publish',
            "col_name" => "*",
            "where" => "status = 0 and atonce = 0 and timing <".time(),
            "order" => 'id desc',
        );
        $return_data =  self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $data = $return_data['result']['cols'];
        for($i = 0;$i<count($data); $i++) {
            $number = $this->versiontonumber($data[$i]['rom_version']);
            $param = array(
                "tb_name"   => 'ipfs_xyj_rom_publish',
                "update"    => [
                    "status", "number"
                ],
                "col_value" => [
                    1, $number
                ],
                "where" => "id=".intval($data[$i]['id']),
            );
            $return_data = self::loadApiData("store/update_table", $param);
            if (!$return_data) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
            }
            if ($data[$i]['push_key']) {
                $return_data = json_decode($return_data, true);
                if ($return_data['status'] != 0) {
                    return json($return_data);
                }
                $param = array(
                    "tb_name"   => 'ipfs_xyj_rom_publish_user',
                    "update"    => [
                        "number"
                    ],
                    "col_value" => [
                        $number
                    ],
                    "where" => "push_key='".$data[$i]['push_key']."'",
                );
                $return_data = self::loadApiData("store/update_table", $param);
                if (!$return_data) {
                    return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
                }
                $return_data = json_decode($return_data, true);
                if ($return_data['status'] != 0) {
                    return json($return_data);
                }
            }
            
        }
        return json(['status' => 0, 'err_code' => 0, 'msg' => $i]);
    }

    public function publishuser()
    {
        $data = input('post.');
        $validation = new Validate([
            'push_key'   => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_rom_publish_user',
            "col_name" => ["snormac"],
            "where" => "push_key = '".$data['push_key']."'",
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function publishlist()
    {
        $data = input('post.');
        $validation = new Validate([
            'dev_type'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $page = isset($data['page']) ? $data['page'] : 0;
        $param = array(
            "page" => $page,
            "tb_name"  => 'ipfs_xyj_rom_publish',
            "col_name" => "*",
            "where" => "dev_type = ".intval($data['dev_type']),
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    // public function publishlist()
    // {
    //     $data = input('post.');
    //     $page = isset($data['page']) ? $data['page'] : 0;
    //     // $param = array(
    //     //     "page" => $page,
    //     //     "tb_name"  => 'tb_rom_publish_log',
    //     //     "col_name" => ["id","version_group","push_type","push_mod","descript","time_create"],
    //     //     "where" => '',
    //     //     "order" => 'id desc',
    //     // );
    //     // $return_data = self::loadApiData("store/find_table", $param);
    //     $param = array(
    //         "page" => $page,
    //         "page_size"  => '10',
    //         "sql_str" => 'select a.* from tb_rom_publish_log a,(select max(time_create) as TC,node_hash from  tb_rom_publish_log group by push_key) as b where a.time_create = b.TC and a.node_hash = b.node_hash order by id desc',
    //     );
    //     $return_data = self::loadApiData("store/query_table_by_sql_str", $param);
    //     $return_data = json_decode($return_data, true);
    //     return json($return_data);
    // }

    public function versionlist()
    {
        $data = input('post.');
        $validation = new Validate([
            'dev_type'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_rom_publish',
            "col_name" => "*",
            "where" => "dev_type=".intval($data['dev_type'])." and ((status = 1 and act_type = 1) or (status = 0 and act_type = 2)) and push_type = '正式发布'",
            "order" => 'number desc',
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
            $online_version = [];
            $high_version = "0.0.0";
            $high_version_info = "";
            $number = 0;
        } else {
            $online_version = $return_data['result']['cols'][0];
            $high_version = $return_data['result']['cols'][0]['rom_version'];
            $high_version_info = $return_data['result']['cols'][0]['description'];
            $number = $return_data['result']['cols'][0]['number'];
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_rom_publish',
            "col_name" => "*",
            "where" => "dev_type=".intval($data['dev_type'])." and ((status = 1 and act_type = 1) or (status = 0 and act_type = 2)) and push_type = '灰度发布' and number>".$number,
            "order" => 'number desc,id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $return_data['packet_version'] = $high_version;
        $return_data['online_version'] = $online_version;
        return json($return_data);
    }

    // public function versionlist()
    // {
    //     $data = input('post.');
    //     $page = isset($data['page']) ? $data['page'] : 0;
    //     $param = array(
    //         "page" => 0,
    //         "page_size" => 10,
    //         "tb_name"  => 'tb_rom_update_info',
    //         "col_name" => ["rom_version","push_mod","status"],
    //         "where" => 'status = 1',
    //         "order" => 'number desc',
    //     );
    //     $return_data = self::loadApiData("store/find_table", $param);
    //     $return_data = json_decode($return_data, true);
    //     if ($return_data['status'] == 0 && count($return_data['result']['cols'])>0){
    //         $high_version = $return_data['result']['cols'][0]['rom_version'];
    //         $high_version_info = $return_data['result']['cols'][0];
    //     } else {
    //         $high_version = "0.0.0";
    //         $high_version_info = "";
    //     }
    //     $param = array(
    //         "page" => $page,
    //         "page_size"  => '10',
    //         "sql_str" => 'select a.* from tb_rom_publish_log a,(select max(time_create) as TC,node_hash from  tb_rom_publish_log group by push_key) as b where a.time_create = b.TC and a.node_hash = b.node_hash and version_group>"'.$high_version.'" and push_type="灰度发布" order by id desc',
    //     );
    //     $return_data = self::loadApiData("store/query_table_by_sql_str", $param);
    //     $return_data = json_decode($return_data, true);
    //     $return_data['high_version_info'] = $high_version_info;
    //     return json($return_data);
    // }

    public function sendcmd($target_id, $version)
    {
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["equip_type","rom_version","version_name","rom_url","hashid","rom_size","md5","rom_desc","push_mod"],
            "where" => "rom_version='".$version."'",
            "order" => 'equip_type asc,version_name asc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data =  $return_data['result']['cols'];
            $update_flag = 1;
            header("Content-type: text/xml;chatset=utf-8");
            
            $xml = '<equipment_config type="ROCK64" high_version="'.$version.'" update_flag="-update_flag-">';
            for($i=0;$i<count($data);$i++) {
                $version_name=explode('_', $data[$i]['version_name']);
                $cur_version = $version_name[0];
                if ($data[$i]['equip_type'] == 'full') {
                    $xml .='<version name="high_version" cur_version="'.$version.'" url="'.$data[$i]['rom_url'].'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                } else {
                    $xml .='<version name="'.$cur_version.'" cur_version="'.$cur_version.'" url="'.$data[$i]['rom_url'].'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                }
                if ($data[$i]['push_mod'] == "PTFS") {
                    $update_flag = 2;
                }
                
            }
            $xml .='</equipment_config>';
            $xml = str_replace("-update_flag-", $update_flag, $xml);
            $param = array(
                "cmd_type" => 4,
                "snode_hash" => "*",
                "target_node"  => $target_id,
                "cmd_data" => $xml,
                "cmd_desc" => "Configuration updated"
            );
            $return_data = self::loadApiData("bg_manager_tool/send_ptfs_cmd", $param);
            $return_data = json_decode($return_data, true);
        }
        return json($return_data);     
    }

    public function otaversiontest()
    {
        $data = input('get.');
        $sn = isset($data['sn']) ? $data['sn'] : "";
        $mac = isset($data['mac']) ? $data['mac'] : "";
        //查询最新版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["rom_version", "push_mod"],
            "where" => 'status = 1',
            "order" => 'number desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        //查询用户灰度版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_publish_log',
            "col_name" => ["version_group", "push_mod"],
            "where" => "(node_hash = '".$sn."' or node_hash = '".$mac."') and push_type='灰度发布'",
            "order" => 'id desc',
        );
        $return_data2 = self::loadApiData("store/find_table", $param);
        $return_data2 = json_decode($return_data2, true);
        $sn_version = '0';
        $sn_mod = "HTTPS";
        if ($return_data2['status'] == 0 && count($return_data2['result']['cols'])>0){
            $sn_version = $return_data2['result']['cols'][0]['version_group'];
            $sn_mod = $return_data2['result']['cols'][0]['push_mod'];
        }
        $high_version = '0';
        $high_mod = "HTTPS";
        if ($return_data['status'] == 0 && count($return_data['result']['cols'])>0){
            $high_version = $return_data['result']['cols'][0]['rom_version'];
            $high_mod = $return_data['result']['cols'][0]['push_mod'];
        } 
        //显示版本
        $show_version = $this->versiontonumber($sn_version)>$this->versiontonumber($high_version) ? $sn_version : $high_version;
        $show_mod = $this->versiontonumber($sn_version)>$this->versiontonumber($high_version) ? $sn_mod : $high_mod;
        if (!$show_version) {
            return '';
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["equip_type","rom_version","version_name","rom_url","hashid","rom_size","md5","rom_desc","push_mod"],
            "where" => "rom_version='".$show_version."'",
            "order" => 'equip_type asc,version_name asc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data =  $return_data['result']['cols'];
            
            header("Content-type: text/xml;chatset=utf-8");
            
            $xml = '<equipment_config type="ROCK64" high_version="'.$show_version.'" update_flag="-update_flag-">';
            for($i=0;$i<count($data);$i++) {
                $version_name=explode('_', $data[$i]['version_name']);
                $cur_version = $version_name[0];
                if ($data[$i]['equip_type'] == 'full') {
                    $xml .='<version name="high_version" cur_version="'.$show_version.'" url="'.str_replace('47.99.193.140', $_SERVER['HTTP_HOST'], $data[$i]['rom_url']).'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                } else {
                    $xml .='<version name="'.$cur_version.'" cur_version="'.$cur_version.'" url="'.str_replace('47.99.193.140', $_SERVER['HTTP_HOST'], $data[$i]['rom_url']).'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                }
            }
            $update_flag = 1;
            if ($show_mod == "PTFS") {
                $update_flag = 2;
            }
            $xml .='</equipment_config>';
            $xml = str_replace("-update_flag-", $update_flag, $xml);
            print $xml; //输出 XML

            exit;
        }
        return json($return_data);
        
    }

    public function otaversion()
    {
        $data = input('get.');
        $sn = isset($data['sn']) ? $data['sn'] : "";
        //$mac = isset($data['mac']) ? $data['mac'] : "";
        if (!$sn) {
            $dev_type = 0;
        } else {
            $check = substr($sn, 3, 1);
            $dev_type = strtolower($check) == 'x' ? 4: $check;
        }
       
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_rom_publish',
            "col_name" => ["number"],
            "where" => "dev_type=".intval($dev_type)." and ((status = 1 and act_type = 1) or (status = 0 and act_type = 2)) and push_type = '正式发布'",
            "order" => 'number desc',
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
            $number = 0;
        } else {
            $number = $return_data['result']['cols'][0]['number'];
        }
        //查询用户灰度版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_rom_publish_user',
            "col_name" => ["number"],
            "where" => "snormac = '".$sn."' and number>".$number,
            "order" => 'number desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (isset($return_data['result']['cols'][0])) {
            $number = $return_data['result']['cols'][0]['number'];
        }
        if ($number == 0) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '暂无发布版本']);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => "*",
            "where" => "rom_type=".$dev_type." and number=".$number,
            "order" => 'equip_type asc,version_name asc',
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
            return json(['status' => -900, 'err_code' => -900, 'msg' => '未查询到安装包']);
        }
        $data =  $return_data['result']['cols'];
        
        //header("Content-type: text/xml;chatset=utf-8");
        // $type = $dev_type == 1 ? 'AMS805' : 'RK3328';
        switch($dev_type){
            case 0:
                $type = '西柚机';
            break;
            case 1:
                $type = '玩客云';
            break;
            case 2:
                $type = '小米盒子4C';
            break;
            case 3:
                $type = '小米盒子4';
            break;
            case 4:
                $type = 'PC版西柚机';
            break;
            default:
                $type = '全部';
        }
       
        $xml = '<equipment_config type="'.$type.'" high_version="'.$data[0]['rom_version'].'" update_flag="1">';
        for($i=0;$i<count($data);$i++) {
            $version_name=explode('_', $data[$i]['version_name']);
            $cur_version = $version_name[0];
            if ($data[$i]['equip_type'] == 'full') {
                $xml .='<version name="high_version" cur_version="'.$cur_version.'" url="'.$data[$i]['rom_url'].'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
            } else {
                $xml .='<version name="'.$cur_version.'" cur_version="'.$cur_version.'" url="'.$data[$i]['rom_url'].'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
            }
        }
        $xml .='</equipment_config>';
        //print $xml; //输出 XML
        print $this->encrypt($xml); //输出 XML
        exit;
    }

    public function otaversionnewbak()
    {
        $data = input('get.');
        $sn = isset($data['sn']) ? $data['sn'] : "";
        $mac = isset($data['mac']) ? $data['mac'] : "";
        //查询最新版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["rom_version", "push_mod"],
            "where" => 'status = 1',
            "order" => 'number desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        //查询用户灰度版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_publish_log',
            "col_name" => ["version_group", "push_mod"],
            "where" => "(node_hash = '".$sn."' or node_hash = '".$mac."') and push_type='灰度发布'",
            "order" => 'id desc',
        );
        $return_data2 = self::loadApiData("store/find_table", $param);
        $return_data2 = json_decode($return_data2, true);
        $sn_version = '0';
        $sn_mod = "HTTPS";
        if ($return_data2['status'] == 0 && count($return_data2['result']['cols'])>0){
            $sn_version = $return_data2['result']['cols'][0]['version_group'];
            $sn_mod = $return_data2['result']['cols'][0]['push_mod'];
        }
        $high_version = '0';
        $high_mod = "HTTPS";
        if ($return_data['status'] == 0 && count($return_data['result']['cols'])>0){
            $high_version = $return_data['result']['cols'][0]['rom_version'];
            $high_mod = $return_data['result']['cols'][0]['push_mod'];
        } 
        //显示版本
        $show_version = $this->versiontonumber($sn_version)>$this->versiontonumber($high_version) ? $sn_version : $high_version;
        $show_mod = $this->versiontonumber($sn_version)>$this->versiontonumber($high_version) ? $sn_mod : $high_mod;
        if (!$show_version) {
            return '';
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["equip_type","rom_version","version_name","rom_url","hashid","rom_size","md5","rom_desc","push_mod"],
            "where" => "rom_version='".$show_version."'",
            "order" => 'equip_type asc,version_name asc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data =  $return_data['result']['cols'];
            
            //header("Content-type: text/xml;chatset=utf-8");
            
            $xml = '<equipment_config type="ROCK64" high_version="'.$show_version.'" update_flag="-update_flag-">';
            for($i=0;$i<count($data);$i++) {
                $version_name=explode('_', $data[$i]['version_name']);
                $cur_version = $version_name[0];
                if ($data[$i]['equip_type'] == 'full') {
                    $xml .='<version name="high_version" cur_version="'.$show_version.'" url="'.str_replace('47.99.193.140', $_SERVER['HTTP_HOST'], $data[$i]['rom_url']).'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                } else {
                    $xml .='<version name="'.$cur_version.'" cur_version="'.$cur_version.'" url="'.str_replace('47.99.193.140', $_SERVER['HTTP_HOST'], $data[$i]['rom_url']).'" hash_id="'.$data[$i]['hashid'].'" size="'.$data[$i]['rom_size'].'" md5="'.$data[$i]['md5'].'" desc="'.str_replace("&", " ", $data[$i]['rom_desc']).'"/>';
                }
            }
            $update_flag = 1;
            if ($show_mod == "PTFS") {
                $update_flag = 2;
            }
            $xml .='</equipment_config>';
            $xml = str_replace("-update_flag-", $update_flag, $xml);
            print $this->encrypt($xml); //输出 XML

            exit;
        }
        return json($return_data);
        
    }

    function generate_password( $length = 8 ) { 
        // 密码字符集，可任意添加你需要的字符 
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|'; 
        $password = ''; 
        for ( $i = 0; $i < $length; $i++ ) 
        { 
            // 这里提供两种字符获取方式 
            // 第一种是使用 substr 截取$chars中的任意一位字符； 
            // 第二种是取字符数组 $chars 的任意元素 
            // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1); 
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
        } 
        return $password; 
    } 

    public function encrypt($string){
        $Key = [0x04, 0xe3, 0x78, 0xec, 0x38, 0xe5, 0x07, 0x43, 0x85, 0x6f, 0x60, 0xed, 0x04, 0xcf, 0xc7, 0x38];
        $privateKey = '';
        for($i=0;$i<count($Key);$i++) {
            $privateKey.=chr($Key[$i]);
        }
        $iv = $this->generate_password(16);
        $data = $string;
        //$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $data, MCRYPT_MODE_CBC, $iv);
        $encrypted = openssl_encrypt($data, 'AES-128-CBC', $privateKey, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv.$encrypted);
    }

    public function decrypt($string){
        $Key = [0x04, 0xe3, 0x78, 0xec, 0x38, 0xe5, 0x07, 0x43, 0x85, 0x6f, 0x60, 0xed, 0x04, 0xcf, 0xc7, 0x38];
        $privateKey = '';
        for($i=0;$i<count($Key);$i++) {
            $privateKey.=chr($Key[$i]);
        }
        $string = base64_decode($string);
        $iv = substr($string,0,16);
        $data = substr($string,16);
        //解密
        $encryptedData = $data;
        //$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $privateKey, $encryptedData, MCRYPT_MODE_CBC, $iv);
        $decrypted = openssl_decrypt($encryptedData, 'AES-128-CBC', $privateKey, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    public function versiontonumber($version){
        $version = explode(".", $version);
        $number = "";
        for($j=0;$j<count($version);$j++) {
            $number .=str_pad($version[$j],3,"0",STR_PAD_LEFT);
        }
        return $number=intval($number);
    }

    public function is_json($data = '', $assoc = false) {
        $data = json_decode($data, $assoc);
        if (($data && (is_object($data))) || (is_array($data) && !empty($data))) {
            return true;
        }
        return false;
    }

    public function test()
    {
        $string = file_get_contents("http://47.99.193.140/otaversion");
        if (!$string) {
            return 'error';
        }
        return $res = $this->decrypt($string);
    
    }

    public function uploadpfts()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'rom_url'  =>  'require',
             'md5' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $url = str_replace(request()->domain(), "/home/demon/public", $data['rom_url']);
        try {
            $ptfs = file_get_contents("http://127.0.0.1:7010/add/?path=".$url."&key=".$data['md5']);
            $is_json = $this->is_json($ptfs);
            if ($is_json) {
                $ptfs = json_decode($ptfs, true);
                $hashid = $ptfs['hash'];
            } else {
                $hashid = '';
            }
        } catch (\Exception $e) {
            return json(['status' => -901, 'err_code' => -901, 'msg' => 'ptfs服务器节点未启动或运行异常']);
        }
        $param = array(
            "tb_name"   => 'tb_rom_update_info',
            "update"    => [
                "hashid"
            ],
            "col_value" => [
                $hashid
            ],
            "where" => "md5='".$data['md5']."'",
        );
        $return_data = self::loadApiData("store/update_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status']!=0) {
            return json($return_data);
        }
        return json(['status' => 0, 'err_code' => 0, 'msg' => '上传成功', 'hashid' => $hashid]);
    }

    public function complete()
    {
        $data = input('get.');
        $md5 = isset($data['key']) ? $data['key'] : "";
        $file = fopen("uploadlog.txt","a+"); //次方法会自动生成文件test,txt,a表示追加写入，
        fwrite($file, "-----".date("H:i:s")."-----\n");
        fwrite($file, $md5."\n");
        fwrite($file, "---------------------------\n");

        if (!$md5) {
            fclose($file);
            return json(["status" => -900, "msg" => "参数错误"]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'tb_rom_update_info',
            "col_name" => ["id", "ptfs_file_upload_status"],
            "where" => "md5='".$md5."'",
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        fwrite($file, $return_data."\n");
        fwrite($file, "---------------------------\n");
        fclose($file);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0 && count($return_data['result']['cols'])>0){
            $id = $return_data['result']['cols'][0]['id'];
            $ptfs_file_upload_status = $return_data['result']['cols'][0]['ptfs_file_upload_status'];
            $param = array(
                "tb_name"   => 'tb_rom_update_info',
                "update"    => [
                    "ptfs_file_upload_status"
                ],
                "col_value" => [
                    1
                ],
                "where" => "id=".$id,
            );
            $return_data = self::loadApiData("store/update_table", $param);
            $return_data = json_decode($return_data, true);
            return json($return_data);
        }
        return json(["status" => -900, "msg" => "无效的key"]);

    }
    



}
