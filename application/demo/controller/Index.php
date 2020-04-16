<?php
namespace app\demo\controller;
use think\Validate;
use think\App;
use think\Controller;
use think\facade\Cache;

class Index extends Controller
{
    public function list()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 1;
        $size = isset($data['size']) ? $data['size'] : 10;
        $faker_total = isset($data['faker_total']) ? $data['faker_total'] : 100;
        $faker_column = isset($data['faker_column']) ? $data['faker_column'] : 5;
        $faker_row = [];
        for ($i = 1; $i <= $faker_column; $i ++) {
            $faker_row[] = 'key_'.$i;
        }
        $totalpage = ceil($faker_total/$size);
        if ($page <= 0) {
            return json(['status' => -900, 'err_code' => -900,  'err_msg' => 'page不能小于1', 'data'=>[]]);
        }
        if ($page > $totalpage) {
            return json(['status' => -900, 'err_code' => -900,  'err_msg' => 'page不能大于总页数', 'data'=>[]]);
        }
        $return_data = [];
        for ($i = 0;$i < $size; $i ++) {
            $rowindex = ($page -1) * $size + $i + 1;
            if ( $rowindex > $faker_total) {
                continue;
            }
            $row = [];
            for ( $j = 0; $j < count($faker_row); $j ++) {
                $row[$faker_row[$j]] = 'value_'.($j+1)."_".$rowindex;
            }
            $return_data[] = $row;
        }
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => 'success', 'data'=>$return_data, 'total' => $faker_total]); 
       
    }

    public function detail()
    {
        $data = input('post.');
        $data['key'] = isset($data['key']) ? $data['key'] : 1;
        $faker_column = isset($data['faker_column']) ? $data['faker_column'] : 5;
        $faker_row = [];
        for ($i = 1; $i <= $faker_column; $i ++) {
            $faker_row[] = 'key_'.$i;
        }
        $return_data = [];

        for ( $j = 0; $j < count($faker_row); $j ++) {
            $return_data[$faker_row[$j]] = 'value_'.($j+1)."_".$data['key'];
        }
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => 'success', 'data'=>$return_data]); 
    }

    public function add()
    {
        $data = input('post.');
        $faker_column = isset($data['faker_column']) ? $data['faker_column'] : 5;
        $faker_row = [];
        $check_row = [];
        for ($i = 1; $i <= $faker_column; $i ++) {
            $faker_row[] = 'key_'.$i;
            $check_row['key_'.$i] = 'require';
        }
        $validation = new Validate($check_row);
        //验证表单
        if (!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $return_data = [];
        $return_data['key'] = rand(1,100); 
        for ( $j = 0; $j < count($faker_row); $j ++) {
            $return_data[$faker_row[$j]] = $data[$faker_row[$j]];
        }
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => 'success', 'data'=>$return_data]); 
    }

    public function edit()
    {
        $data = input('post.');
        $faker_column = isset($data['faker_column']) ? $data['faker_column'] : 5;
        $faker_row = [];
        $check_row = [];
        $check_row['key'] = 'require';
        for ($i = 1; $i <= $faker_column; $i ++) {
            $faker_row[] = 'key_'.$i;
            $check_row['key_'.$i] = 'require';
        }
        $validation = new Validate($check_row);
        //验证表单
        if (!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $return_data = [];
        $return_data['key'] = $data['key']; 
        for ( $j = 0; $j < count($faker_row); $j ++) {
            $return_data[$faker_row[$j]] = $data[$faker_row[$j]];
        }
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => 'success', 'data'=>$return_data]); 
    }

    public function delete()
    {
        $data = input('post.');
        $faker_column = isset($data['faker_column']) ? $data['faker_column'] : 5;
        return json(['status' => 0, 'err_code' => 0,  'err_msg' => 'success', 'data'=>[]]); 
    }


}
