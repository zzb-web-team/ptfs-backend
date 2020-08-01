<?php
namespace app\admin\controller;
use think\facade\Cache;
use think\Validate;
use think\Request;
require '../extend/PHPExcel/PHPExcel.php';

class Device extends Common
{

     public function devicelist()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        if (!empty($data['push_key'])) {   
            $param = array(
                "page" => $page,
                "tb_name"  => 'tb_rom_publish_log',
                "col_name" => ["node_hash"],
                "where" => "push_key='".$data['push_key']."'",
                "order" => 'id desc',
            );
            $return_data = self::loadApiData("store/find_table", $param);
            $return_data = json_decode($return_data, true);
            return json($return_data);
        }
        
        $where = "1";
        if (!empty($data['dev_sn'])) {
            $where .= ' and miner_sn = "'.$data['dev_sn'].'"';
        }
        if (!empty($data['user_id'])) {
            $where .= ' and bind_user_id = '.intval($data['user_id']);
        }
        if (!empty($data['node_hash'])) {
            $where .= ' and miner_id = "'.$data['node_hash'].'"';
        }
        if (isset($data['status']) && gettype($data['status']) == 'integer') {
            $where .= ' and online_state = '.intval($data['status']);
        }
        if (isset($data['bind_state']) && gettype($data['bind_state']) == 'integer') {
            $where .= ' and bind_state = '.intval($data['bind_state']);
        }
        $param = array(
            "page" => $page,
            "page_size"  => '10',
            "sql_str" => 'select * from tb_device where '.$where. ' order by id desc',
        );

        $return_data = self::loadApiData("store/query_table_by_sql_str", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
        
    }

    public function savedevice()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
             'dev_type' => 'require',
             'dev_sn' => 'require',
             'dev_mac' => 'require',
             'total_cap' => 'require',
             'rom_version' => 'require',
             'cpu_id' => 'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            'login_token'  => isset($data['login_token']) ? $data['login_token'] : "",
            'dev_type' => $data['dev_type'],
            'dev_sn' => $data['dev_sn'],
            'dev_mac' => $data['dev_mac'],
            'total_cap' => $data['total_cap'],
            'rom_version' => $data['rom_version'],
            'cpu_id' => $data['cpu_id'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        return self::loadApiData("miner_ctrl/import_node_basicinfo", $param);
    }

    public function exceldevice()
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
        if (count($title) != 6) {
            return json(['status' => -900, 'msg' => '文件列数不正确，请检查后再上传']);
        }
        $data = array();
        for ($i= 1;$i<count($exdata);$i++) {
            for ($j=0;$j<count($exdata[$i]);$j++) {
                $data[$i-1][$title[$j]] = $exdata[$i][$j];
            }
        }
        //print_r(array_slice($data,2,2,true));
        $row = 2;
        $totalpage = ceil(count($data)/$row);
        $total = count($data);
        $success = 0;
        $right = 0;
        for ($i = 0; $i<$totalpage; $i++) {
            $param = array(
                "login_token" => "sss",
                "arr" => array_slice($data, ($i -1)*$row, $row, false)
            );
            $res = self::loadApiData("miner_ctrl/batch_import_devices", $param);
            if (!$res) {
                return json(['status' => -900, 'msg' => 'IPFS服务可能宕机了，请稍后再试']);
            }
            $res = json_decode($res, true);
            if ($res['status'] == 0) {
                $success += $res['data']['total_row_cnt'];
                $right += $res['data']['valid_row_cnt'];
            } else {
                return json($res);
            }
        }
        return json(['status' => 0, 'err_code' => 0, 'err_msg' => "success", "data" => ["total" => $total, "success" => $success, "right" => $right]]);
        
    }

    public function batch_import_devices()
    {
        
    }

    public function device_cnt_overview()
    {
        $data = input('post.');
        $param = array(
            "token" => "sss",
        );

        return $return_data = self::loadApiData("device_manage/device_cnt_overview", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function query_detail_info_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_time'  => 'require',
            'end_time'  => 'require',
            'query_type'  => 'require',
            'alarm_type' => 'require',
            //'node_id' => 'require',
            'page' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "query_type" => $data['query_type'],
            "dev_sn" => $data['dev_sn'],
            "node_id" => $data['node_id'],
            "alarm_type" => $data['alarm_type'],
            "page" => $data['page'],
            "export_file" => isset($data['export_file']) ? $data['export_file'] : 0,
        );

        $return_data = self::loadApiData("dev_status/query_detail_info_list", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function query_node_info()
    {
        $data = input('post.');
        $validation = new Validate([
            'dev_sn' => 'require',
            'time_stamp' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "dev_sn" => $data['dev_sn'],
            "time_stamp" => $data['time_stamp'],
        );

        $return_data = self::loadApiData("dev_status/query_node_info", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function query_general_info_list()
    {
        $data = input('post.');
        $validation = new Validate([
            'start_time'  => 'require',
            'end_time'  => 'require',
            'query_type'  => 'require',
            'alarm_type' => 'require',
            'page' => 'require',
            'online_status'=> 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "query_type" => $data['query_type'],
            "dev_sn" => $data['dev_sn'],
            "node_id" => $data['node_id'],
            "alarm_type" => $data['alarm_type'],
            "online_status" => $data['online_status'],
            "page" => $data['page'],
        );

        $return_data = self::loadApiData("dev_status/query_general_info_list", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function rom_info_change_notice()
    {
        $data = input('post.');
        $validation = new Validate([
            'rom_version'  => 'require',
            'pid_name'  => 'require',
            'pid_md5'  => 'require',
            'page' => 'require'
        ]);
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "rom_version" => $data['rom_version'],
            "pid_name" => $data['pid_name'],
            "pid_md5" => $data['pid_md5'],
            "page" => $data['page']
        );
        $return_data = self::loadApiData("dev_status/rom_info_change_notice", $param);
        return $return_data;
    }

    public function query_dev_pid_general_list(){
        $data = input('post.');
        $validation = new Validate([
            // 'dev_sn'  => 'require',
            'online_status'  => 'require',
            'page'  => 'require'
        ]);
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "dev_sn" => isset($data['dev_sn']) ? $data['dev_sn'] : "",
            "online_status" => $data['online_status'],
            "page" => $data['page']
        );
        $return_data = self::loadApiData("dev_status/query_dev_pid_general_list", $param);
        return $return_data;
    }

    public function query_dev_pid_detail_list(){
        $data = input('post.');
        $validation = new Validate([
            'dev_sn'  => 'require',
            'page'  => 'require'
        ]);
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "dev_sn" => $data['dev_sn'],
            "page" => $data['page']
        );
        $return_data = self::loadApiData("dev_status/query_dev_pid_detail_list", $param);
        return $return_data;
    }

    public function query_dev_pid_history_list(){
        $data = input('post.');
        $validation = new Validate([
            'dev_sn'  => 'require',
            'md5_type'  => 'require',
            'start_time'  => 'require',
            'end_time'  => 'require',
            'page'  => 'require',
            'order'  => 'require'

        ]);
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "dev_sn" => $data['dev_sn'],
            "md5_type" => $data['md5_type'],
            "start_time" => $data['start_time'],
            "end_time" => $data['end_time'],
            "page" => $data['page'],
            "order" => $data['order'],
        );
        $return_data = self::loadApiData("dev_status/query_dev_pid_history_list", $param);
        return $return_data;
    }


}
