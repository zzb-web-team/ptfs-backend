<?php
namespace app\admin\controller;
use think\Validate;
require '../extend/PHPExcel/PHPExcel.php';

class Packet extends Common
{
    public function queryPacket()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $size = isset($data['size']) ? $data['size'] : 10;
        $param = array(
            "page"      => $page,
            "page_size" => $size,
            "tb_name"   => 'ipfs_xyj_packet',
            "col_name"  => "*",
            "where"     => '',
            "order"     => 'number desc,id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function findPacketById()
    {
        $data = input('post.');
        $validation = new Validate([
            'packet_version'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page"      => 0,
            "page_size" => 10,
            "tb_name"   => 'ipfs_xyj_packet',
            "col_name"  => ["*"],
            "where"     => "packet_version='".$data['packet_version']."'",
            "order"     => '',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $return_data['result'] = $return_data['result']['cols'][0];
        return json($return_data);
    }

    public function editDescription()
    {
        $data = input('post.');
        $validation = new Validate([
            'id'   => 'require',
            'packet_description'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"  => 'ipfs_xyj_packet',
            "update" => ["packet_description"],
            "col_value" => [
                $data["packet_description"], 
            ],
            "where" => "id=".intval($data['id']),
        );
        return self::loadApiData("store/update_table", $param);
    }

    public function deletePacket()
    {
        $data = input('post.');
        $validation = new Validate([
            'id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_packet',
            "where"     => "id=".$data['id'],
        );
        return self::loadApiData("store/delete_record", $param);  
    }


    public function savePacket()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'packet_name'  =>  'require',
            'packet_size'  =>  'require',
            'packet_md5'  =>  'require',
            'packet_version'  =>  'require',
            'packet_url'  =>  'require',
            'packet_description'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"   => 'ipfs_xyj_packet',
            "col_name"  => "*",
            "where"     => "packet_version='".$data['packet_version']."'",
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
        if (isset($return_data['result']['cols'][0])) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '该版本已经上传过了，请勿重复上传']);
        }
        $insert = array();
        $number = $this->versiontonumber($data['packet_version']);


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
            $data['packet_name'], 
            $data['packet_size'], 
            $data['packet_md5'], 
            $hashid, 
            0, 
            $data['packet_version'], 
            $number, 
            $data['packet_url'], 
            $data['packet_description'],
            0
        ];
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_packet',
            "insert"    => $insert
        );
        return self::loadApiData("store/insert_table", $param);
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

    public function rollback()
    {
        $data = input('post.');
        $validation = new Validate([
            'rom_version'   => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"   => 'tb_rom_update_info',
            "update"    => [
                "status"
            ],
            "col_value" => [
                0
            ],
            "where" => "rom_version='".$data['rom_version']."'",
        );
        $return_data = self::loadApiData("store/update_table", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function getversion()
    {
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet_publish',
            "col_name" => ["number"],
            "where" => "push_type = '正式发布' and status = 1",
            "order" => 'number desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);

        $return_data = json_decode($return_data, true);
        if (!$return_data) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        if (!isset($return_data['result']['cols'][0])) {
            $number = 0;
        } else {
            $number = $return_data['result']['cols'][0]['number'];
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet',
            "col_name" => ["packet_version","ptfs_file_upload_status"],
            "where" => 'number>'.$number,
            "order" => 'number desc',
        );
        return self::loadApiData("store/find_table", $param);
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
            "tb_name"  => 'ipfs_xyj_packet_publish_user',
            "col_name" => ["user"],
            "where" => "push_key = '".$data['push_key']."'",
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function publish()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'packet_version'  =>  'require',
            'push_type'  =>  'require',
            'description'  =>  'require',
            'atonce'  =>  'require',
            'timing'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        
        $number = $this->versiontonumber($data['packet_version']);
        if ($data['push_type'] == '正式发布') {
            $insert = array();
            $insert[] = [
                $data['packet_version'], 
                $data['push_type'], 
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
                "tb_name"   => 'ipfs_xyj_packet_publish',
                "insert"    => $insert
            );
            return self::loadApiData("store/insert_table", $param);
        }
        $push_key = md5(time());
        $user_list = isset($data['user_list']) ? $data['user_list'] : [];
        if (!$user_list) {
            return json(['status' => -900, 'msg' => '灰度发布必须指定至少1个用户']);
        }
        $insert = array();
        $insert[] = [
            $data['packet_version'], 
            $data['push_type'], 
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
            "tb_name"   => 'ipfs_xyj_packet_publish',
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
        for($i = 0; $i<count($user_list); $i++) {
            $insert[] = [
                $push_key, 
                $user_list[$i]."",
                $number
            ];
        }
        if (!$insert) {
            return json(['status' => -900, 'msg' => '缺少参数']);
        }
        $param = array(
            "tb_name"   => 'ipfs_xyj_packet_publish_user',
            "insert"    => $insert
        );
        return self::loadApiData("store/insert_table", $param);

    }

    public function publishlist()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $param = array(
            "page" => $page,
            "tb_name"  => 'ipfs_xyj_packet_publish',
            "col_name" => "*",
            "where" => '',
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function versionlist()
    {
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet_publish',
            "col_name" => "*",
            "where" => "status = 1 and push_type = '正式发布'",
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
            $high_version = "0.0.0";
            $high_version_info = "";
            $number = 0;
        } else {
            $high_version = $return_data['result']['cols'][0]['packet_version'];
            $high_version_info = $return_data['result']['cols'][0]['description'];
            $number = $return_data['result']['cols'][0]['number'];
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet_publish',
            "col_name" => "*",
            "where" => "status = 1 and push_type = '灰度发布' and number>".$number,
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        $return_data['packet_version'] = $high_version;
        $return_data['high_version_info'] = $high_version_info;
        return json($return_data);
    }

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
        $phone = isset($data['phone']) ? $data['phone'] : "";
        //查询全网最新版本
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet_publish',
            "col_name" => ["number"],
            "where" => "push_type = '正式发布' and status = 1",
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
        if ($phone) {
            //查询用户灰度版本
            $param = array(
                "page" => 0,
                "page_size" => 10,
                "tb_name"  => 'ipfs_xyj_packet_publish_user',
                "col_name" => ["number"],
                "where" => "user = '".$phone."' and number>".$number,
                "order" => 'number desc',
            );
            $return_data2 = self::loadApiData("store/find_table", $param);
            if (!$return_data2) {
                return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
            }
            $return_data2 = json_decode($return_data2, true);
            if ($return_data2['status'] != 0) {
                return json($return_data2);
            }
            if (isset($return_data2['result']['cols'][0]['number'])) {
                $number =  $return_data2['result']['cols'][0]['number'];
            }
        }
        $param = array(
            "page" => 0,
            "page_size" => 10,
            "tb_name"  => 'ipfs_xyj_packet',
            "col_name" => ["packet_version","packet_url","number"],
            "where" => 'number = '.$number,
            "order" => 'number desc',
        );
        $return_data3 = self::loadApiData("store/find_table", $param);
        if (!$return_data3) {
            return json(['status' => -900, 'err_code' => -900, 'msg' => '服务器可能开小差去了']);
        }
        $return_data3 = json_decode($return_data3, true);
        if ($return_data3['status'] != 0) {
            return json($return_data3);
        }
        if (!isset($return_data3['result']['cols'][0])) {
            return json(['status' => 0, "data" => []]);
        }
        return json(['status' => 0, "data" =>$return_data3['result']['cols'][0]]);
        
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
            'url'  =>  'require',
            'type'  =>  'require',
            'id'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "url"   => $data['url'],
            "type"   => intval($data['type']),
            "uuid"   => $data['id']."",
        );
        return self::loadApiData("node_mgmt/uploadpfts", $param);
    }

    public function complete()
    {
        $data = input('post.');
        $validation = new Validate([
            'packet_hash'  =>  'require',
            'type' => 'require',
            'uuid' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['type'] == 1) {
            $table = 'ipfs_xyj_packet';
            $column = 'packet_hash';
        } else if ($data['type'] == 2) {
            $table = 'tb_rom_update_info';
            $column = 'hashid';
        } else {
            return json(['status' => -900, 'err_code' => -900, 'msg' => 'type字段有误']);
        }
        $param = array(
            "tb_name"   => $table,
            "update"    => [
                "ptfs_file_upload_status" , $column
            ],
            "col_value" => [
                1, $data['packet_hash']
            ],
            "where" => "id=".intval($data['uuid']),
        );
        return self::loadApiData("store/update_table", $param);

    }    

    public function uploadtxt()
    {
        $file = request()->file('excel');
        //将文件保存到public/uploads目录下面
        $info = $file->validate(['size'=>1048576,'ext'=>'xls,xlsx'])->move( './upload/excel');
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = './upload/excel/'.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            return json(['status' => -900, 'msg' => '文件过大或格式不正确导致上传失败']);
        }
        //载入excel文件
        $excel = $reader->load("$filePath",$encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        $exdata = $sheet->toArray();
        if (count($exdata) <= 1) {
            return json(['status' => -900, 'msg' => '文件行数不正确，请检查后再上传']);
        }
        $title = $exdata[0];
        if (count($title) != 1) {
            return json(['status' => -900, 'msg' => '文件列数不正确，请检查后再上传']);
        }
        $title = ['phone'];
        $total = count($exdata);
        $data = [];
        for ($i= 1;$i<= $total;$i++) {
            if (!isset($exdata[$i][0])) {
                continue;
            }
            if ($exdata[$i][0]===null || $exdata[$i][0]==="") {
                continue;
            }
            $temp = array();
            for ($j=0;$j<count($exdata[$i]);$j++) {
                $temp[$title[$j]] = $exdata[$i][$j];
            }
            $temp['create_time'] = time();
            $data[] = $temp;
        }
        return json([
            'status' => 0, 
            'err_msg' => 'ok', 
            'err_code' => 0, 
            'data' => $data,
            'total' => $total - 1
        ]); 
    }

}
