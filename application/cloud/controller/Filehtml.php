<?php
namespace app\cloud\controller;
use think\Validate;

class Filehtml extends Common
{

    //base64地址转为图片 
    public function upload()
    {
        $data = input('post.');
        $validation = new Validate([
            'data'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $image = $data['data'];
        $imageName =  md5($image).'.png';
        if (strstr($image,",")){
            $image = explode(',',$image);
            $image = $image[1];
        }

        $path = "./upload/image/".date("Ymd");
        if (!is_dir($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }
        $imageSrc=  $path."/". $imageName;  //图片名字

        $r = file_put_contents($imageSrc, base64_decode($image));//返回的是字节数
        if (!$r) {
            return json(["status"=>-1, "msg"=>"图片生成失败"]);
        }else{
            return json(['status'=>0, "msg"=>"图片生成成功", 'data'=>request()->domain()."/upload/image/".date("Ymd")."/". $imageName]);
        }
       
    }

    public function uploadmore()
    {
        $data = input('post.');
        $validation = new Validate([
            'data'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $imglist = $data['data'];
        if (count($imglist)<=0) {
            return json(['status' => -900, 'err_code' => -900,  'msg' => 'img not found']);
        }
        $msg = [];
        for($i=0;$i<count($imglist);$i++) {
            $image = $imglist[$i];
            $imageName =  md5($image).'.png';
            if (strstr($image,",")){
                $image = explode(',',$image);
                $image = $image[1];
            }

            $path = "./upload/image/".date("Ymd");
            if (!is_dir($path)){ //判断目录是否存在 不存在就创建
                mkdir($path,0777,true);
            }
            $imageSrc=  $path."/". $imageName;  //图片名字

            $r = file_put_contents($imageSrc, base64_decode($image));//返回的是字节数
            if (!$r) {
                $msg[$i] = ["status"=>-1, "msg"=>"图片生成失败"];
            }else{
                $msg[$i] = ['status'=> 1, "msg"=>"图片生成成功", 'data'=>request()->domain()."/upload/image/".date("Ymd")."/". $imageName];
            }
        }
        return json(['status'=>0, 'msg' => $msg]);
        
       
    }

    //生成静态html 
    public function html()
    {
        $data = input('post.');
        $validation = new Validate([
            'data'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $filename = md5($data['data']);
        file_put_contents("./html/".$filename.".html", $data['data']);
        return json(['status' => 0, 'err_code' => 0,  'msg' => request()->domain().'/html/'.$filename.".html"]);
    }

    //查询设备地域分布 
    public function ptfs_query_user_list()
    {
        $data = input('post.');
        $param = array(
            "page"  => empty($data['page']) ? 0 : $data['page'],
            "query_type"  => empty($data['query_type']) ? "" : $data['query_type'],
            "user_id"  => empty($data['user_id']) ? "" : $data['user_id'],
            "user_name"  => empty($data['user_name']) ? "" : $data['user_name'],
            "tel_num"  => empty($data['tel_num']) ? "" : $data['tel_num'],
            "account_status"  => empty($data['account_status']) ? "" : $data['account_status'],
            "account_active"  => empty($data['account_active']) ? "" : $data['account_active'],
            "sex"  => empty($data['sex']) ? "" : $data['sex'],
            "reg_start_time"  => empty($data['reg_start_time']) ? "" : $data['reg_start_time'],
            "reg_end_time"  => empty($data['reg_end_time']) ? "" : $data['reg_end_time'],
            "bind_start_time"  => empty($data['bind_start_time']) ? "" : $data['bind_start_time'],
            "bind_end_time"  => empty($data['bind_end_time']) ? "" : $data['bind_end_time'],
        );
        return self::loadApiData("account/ptfs_query_user_list", $param);
        $return_data = json_decode($return_data, true);
        return json($return_data);
    }

    public function uploadsdk()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'num'=>'require',
            'total'=>'require',
            'fileName' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $name = $data['fileName'];
        $filename = $name."_".$data['num'];
        $rs = $file->move('./downloadsdk', $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './downloadsdk/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./downloadsdk/'. $name, file_get_contents($path));
                    } else {
                        file_put_contents('./downloadsdk/'. $name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                return json(['status' => 1, 'err_code' => 0,  'msg' => '上传成功', 'url' => request()->domain().'/downloadsdk/'. $name]);
            } else {
                return json(['status' => 0, 'err_code' => 0,  'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

    public function savesdk()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'url'=>'require',
            'type'=>'require',
            'content' => 'require',
            'version' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $description = isset($data['description']) ? $data['description'] : "";
        $param = array(
            "tb_name"   => 'cloud_sdk',
            "insert"    => [
                [
                    $data['url'], $data['type'], $data['content'], $data['version'], $description
                ]
            ]
        );
        return self::loadApiData("store/insert_table", $param);
    }

    public function sdklist()
    {
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $where = "1";
        $where.= $data['search']=="" ? "" : " and (url = '".$data['search']."' or version = '".$data['search']."')";
        $where.= $data['type']=="" ? "" : " and type = '".$data['type']."'";
        $where.= $data['start_ts']=="" ? "" : " and time_create >= ".$data['start_ts'];
        $where.= $data['end_ts']=="" ? "" : " and time_create <= ".$data['end_ts'];
        $order = isset($data['order']) ? $data['order'] : 'id desc';
        $param = array(
            "page"      => $page,
            "page_size" => 10,
            "tb_name"   => 'cloud_sdk',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => $order,
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function editsdk()
    {
        $data = input('post.');
        $validation = new Validate([
            'id'=>'require',
            'url'=>'require',
            'type'=>'require',
            'content' => 'require',
            'description' => 'require',
            'version' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "tb_name"  => 'cloud_sdk',
            "update" => ["url","type","content","description","version"],
            "col_value" => [
                $data["url"], $data["type"], $data["content"], $data["description"], $data["version"], 
            ],
            "where" => "id='".$data['id']."'",
        );
        return self::loadApiData("store/update_table", $param);
    
    }


}
