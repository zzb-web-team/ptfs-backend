<?php
namespace app\cloud\controller;
use think\Validate;
use think\Request;
require '../extend/PHPExcel/PHPExcel.php';

class Urlmgmt extends Common
{

    public function add_url()
    {
        $data = input('post.');
        $validation = new Validate([
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/add_url", $param);
    }

    public function addurl()
    {
        $data = input('post.');
        $validation = new Validate([
            'url_type'  => 'require',
            'url'  => 'require',
            'url_name'  => 'require',
            'buser_id'  => 'require',
            'label'  => 'require',
            'label2'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $insert = array(
            "url_type" => $data['url_type'],
            "url"  => $data['url'],
            "url_name"  => $data['url_name'],
            "buser_id"  => $data['buser_id'],
            "label"  => $data['label'],
            "label2"  => $data['label2'],
            "create_time"  => time(),
        );
        $param = array(
            "data_count" => 1,
            "data_array"  => [
                $insert
            ],
        );
        return self::loadApiData("url_mgmt/add_url", $param);
    }

    public function excelurluser()
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
        if (count($title) != 5) {
            return json(['status' => -900, 'msg' => '文件列数不正确，请检查后再上传']);
        }
        $title = ['url_type', 'url', 'url_name', 'label', 'label2'];
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

    public function excelurl()
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
        $title = ['url_type', 'url_name', 'url', 'buser_id', 'label', 'label2'];
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

        // $totalpage = ceil($total/10);
        // $failed_count = 0;
        // $res_data = [];
        // $success_count = 0;
        // for ($page = 1;$page<=$totalpage; $page++) {
        //     $data = array();
        //     $num = $page * 10;
        //     if ($page == $totalpage) {
        //         $num = $total;
        //     }
        //     $start = ($page -1) * 10 + 1;
        //     for ($i= $start;$i<= $num;$i++) {
        //         if (!isset($exdata[$i][0])) {
        //             continue;
        //         }
        //         if ($exdata[$i][0]===null || $exdata[$i][0]==="") {
        //             continue;
        //         }
        //         $temp = array();
        //         for ($j=0;$j<count($exdata[$i]);$j++) {
        //             $temp[$title[$j]] = $exdata[$i][$j];
        //         }
        //         $temp['create_time'] = time();
        //         $data[] = $temp;
        //     }
        //     $param = array(
        //         "data_count" => count($data),
        //         "data_array"  => $data,
        //     );
        //     $result  = self::loadApiData("url_mgmt/add_url", $param);
        //     if (!$result) {
        //         return json(['status' => -900, 'msg' => 'IPFS服务错误']);
        //     }
        //     $result = json_decode($result, true);
        //     if ($result['status'] !=0) {
        //         return json($result);
        //     }
        //     $failed_count +=$result['data']['failed_count'];
        //     $res_data = array_merge($res_data, $result['data']['res_data']);
        //     $success_count += $result['data']['success_count'];
        // }
        // return json([
        //     'status' => 0, 
        //     'err_msg' => 'ok', 
        //     'err_code' => 0, 
        //     'data' => [
        //         'failed_count' => $failed_count,
        //         'success_count' => $success_count,
        //         'res_data' => $res_data,
        //     ]
        // ]);  
    }

    public function check_label()
    {
        $data = input('post.');
        $validation = new Validate([
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/check_label", $param);
    }

    public function modify_label()
    {
        $data = input('post.');
        $validation = new Validate([
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/modify_label", $param);
    }

    public function config_url()
    {
        $data = input('post.');
        $validation = new Validate([
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/config_url", $param);
    }

    public function change_state()
    {
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'state' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "buser_id" => $data['buser_id'],
            "state" => $data['state'],
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/change_state", $param);
    }

    public function query_url()
    {
        $data = input('post.');
        $validation = new Validate([
            'page'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "buser_id" => isset($data['buser_id']) ? $data['buser_id'] : "",
            "url_name" => isset($data['url_name']) ? $data['url_name'] : "",
            "start_time" => isset($data['start_time']) ? $data['start_time'] : "",
            "end_time" => isset($data['end_time']) ? $data['end_time'] : "",
            "state" => isset($data['state']) ? $data['state'] : "",
            "order" => isset($data['order']) ? $data['order'] : 0,
        );
        return self::loadApiData("url_mgmt/query_url", $param);
    }

    public function query_config()
    {
        $data = input('post.');
        $validation = new Validate([
            'url_name'  => 'require',
            'type'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "url_name" => isset($data['url_name']) ? $data['url_name'] : "",
            "type" => isset($data['type']) ? $data['type'] : 0,
        );
        return self::loadApiData("url_mgmt/query_config", $param);
    }

    public function delete_url()
    {
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'data_count'  => 'require',
            'data_array'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            'buser_id' => $data['buser_id'],
            "data_count" => $data['data_count'],
            "data_array"  => $data['data_array'],
        );
        return self::loadApiData("url_mgmt/delete_url", $param);
    }

    public function query_urllabel()
    {
        $data = input('post.');
        $validation = new Validate([
            'page'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "page" => isset($data['page']) ? $data['page'] : 0,
            "buser_id" => isset($data['buser_id']) ? $data['buser_id'] : "",
            "url" => isset($data['url']) ? $data['url'] : "",
            "state" => isset($data['state']) ? $data['state'] : -1,
            "order" => isset($data['order']) ? $data['order'] : 0,
        );
        return self::loadApiData("url_mgmt/query_urllabel", $param);
    }

    public function getvideo()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'chanid' =>  'require',
            'page'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['chanid'] == 0) {
            $where = "";
        } else {
            $where = "buser_id = '".$data['chanid']."'";
        }
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => isset($data['pagesize']) ?  intval($data['pagesize']) : 10,
            "tb_name"   => 'url_mgmt',
            "col_name"  => ["url_name"],
            "where"     => $where,
            "order"     => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function check_urlname(){
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'data_count' => 'require',
            'data_array' => 'require',
        ]);
         //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => $data['buser_id'],
            'data_count' => $data['data_count'],
            'data_array' => $data['data_array']
        ];
        return self::loadApiData("url_mgmt/check_ualname",$param);
    }

    public function add_domain(){
        $data = input('post.');
        $validation = new Validate([
            'data_count' => 'require',
            'data_array' => 'require',
        ]);
         //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'data_count' => $data['data_count'],
            'data_array' => $data['data_array']
        ];
        return self::loadApiData("url_mgmt/add_domain",$param);
    }

    public function modify_domain(){
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'domain_id' => 'require',
            'domain' => 'require'
        ]);
         //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => $data['buser_id'],
            'domain_id' => $data['domain_id'],
            'domain' => $data['domain']
        ];
        return self::loadApiData("url_mgmt/modify_domain",$param);
    }

    public function change_domainstate(){
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'state' => 'require',
            'data_count' => 'require',
            'data_array' => 'require'
        ]);
         //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => $data['buser_id'],
            'state' => $data['state'],
            'data_count' => $data['data_count'],
            'data_array' => $data['data_array']
        ];
        return self::loadApiData("url_mgmt/change_domainstate",$param);
    }

    public function del_domain(){
        $data = input('post.');
        $validation = new Validate([
            'buser_id' => 'require',
            'data_count' => 'require',
            'data_array' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => $data['buser_id'],
            'data_count' => $data['data_count'],
            'data_array' => $data['data_array']
        ];
        return self::loadApiData("url_mgmt/del_domain",$param);
    }

    public function query_domain(){
        $data = input('post.');
        $validation = new Validate([
            // 'buser_id' => 'require'
            // 'domain' => 'require',
            // 'state' => 'require',
            // 'start_time' => 'require',
            // 'end_time' => 'require',
            // 'page' => 'require',
            // 'order' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = [
            'buser_id' => isset($data['buser_id']) ? $data['buser_id'] : "",
            'domain' => isset($data['domain']) ? $data['domain'] : "",
            'state' => isset($data['state']) ? $data['state'] : "",
            'start_time' => isset($data['start_time']) ? $data['start_time'] : 0,
            'end_time' => isset($data['end_time']) ? $data['end_time'] : 0,
            'page' => isset($data['page']) ? $data['page'] : 0,
            'order' => isset($data['order']) ? $data['order'] : 0
        ];
        return self::loadApiData("url_mgmt/query_domain",$param);
    }

}
