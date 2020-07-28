<?php
namespace app\admin\controller;
use think\Request;
use ApkParser;
use think\Validate;
/** 
* 后台主页面控制器 
*  
* @author         Demon<327414964@qq.com> 
* @since          1.0 
*/ 

class Upload extends Common{

    public function index()
    {
        set_time_limit(0);
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'num'=>'require',
            'total'=>'require',
            'fileName' => 'require',
            'rom_type' => 'require'
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $name = $data['fileName'];
        $ext = ".zip";
        $zipname = substr($name, 0 , -4);
        $zipary = explode("_", $zipname);
        $version = "unknow";
        if (count($zipary) == 2 && $zipary[1] == 'full') {
            //全更新
            $type = 'full';
            $version = $zipary[0];
        } else if (count($zipary) == 3 && $zipary[2] == 'diff') {
            //差异更新
            $type = 'diff';
            $version = $zipary[1];
        } else {
            return json(['status' => -900, 'err_code' => -900,  'msg' => '包名不正确,请修改包名为full或diff结尾的zip压缩包']);
        }

        $filename = $name."_".$data['num'];
        $rs = $file->move('./download/'.$version.'/'.$data['rom_type'], $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './download/'.$version .'/'.$data['rom_type'].'/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./download/'.$version .'/'.$data['rom_type'].'/'.$name, file_get_contents($path));
                    } else {
                        file_put_contents('./download/'.$version .'/'. $data['rom_type'].'/'.$name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                return json(['status' => 1, 'msg' => '上传成功', 'url' => request()->domain().'/download/'.$version."/". $data['rom_type'].'/'.$name, 'type' => $type, 'version' => $version, 'size' => filesize('./download/'.$version .'/'.$data['rom_type'].'/'. $name), 'name' => $name, 'md5' => md5_file('./download/'.$version .'/'.$data['rom_type'].'/'. $name), 'hashid' => '']);
            } else {
                return json(['status' => 0, 'err_code' => 0,  'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }    



        // $file = request()->file('file');
        // $info = $file->getInfo();
        // $name = $info['name'];
        // $ext = ".zip";
        // $zipname = substr($name, 0 , -4);
        // $zipary = explode("_", $zipname);
        // $version = "unknow";
        // if (count($zipary) == 2 && $zipary[1] == 'full') {
        //     //全更新
        //     $type = 'full';
        //     $version = $zipary[0];
        // } else if (count($zipary) == 3 && $zipary[2] == 'diff') {
        //     //差异更新
        //     $type = 'diff';
        //     $version = $zipary[1];
        // } else {
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => '包名不正确,请修改包名为full或diff结尾的zip压缩包']);
        // }
        // $size = $info['size'];
        // $filename = $name;
        // $data = $file->move('./download/'.$version, $filename);//图片保存路径
        // if ($data) {
        //     // $ptfs = file_get_contents("http://127.0.0.1:7010/add/?path=/home/demon/public/download/".$version."/". $filename."&key=".$data->md5());
        //     // $is_json = $this->is_json($ptfs);
        //     // if ($is_json) {
        //     //     $ptfs = json_decode($ptfs, true);
        //     //     $hashid = $ptfs['hash'];
        //     // } else {
        //     //     $hashid = '';
        //     // }
        //     return json(['status' => 0, 'msg' => '上传成功', 'url' => request()->domain().'/download/'.$version."/". $filename, 'type' => $type, 'version' => $version, 'size' => $size, 'name' => $name, 'md5' => $data->md5(), 'hashid' => '']);
        // }else{
        //     return json(['status' => -900, 'err_code' => -900, 'msg' => '上传失败']);
        // }
        // exit;
    }

    public function apk()
    {
        set_time_limit(0);
        $file = request()->file('file');
        $info = $file->getInfo();
        $name = $info['name'];
        $ext = ".apk";
        $size = $info['size'];
        $filename = $name;
        $data = $file->move('./downloadapk', $filename);//图片保存路径
        if ($data) {
            $appObj  = new Apkparser(); 
            $targetFile = './downloadapk/'.$filename;//apk所在的路径地址
            $res   = $appObj->open($targetFile);
            return json(['status' => 0, 'msg' => '上传成功', 'url' => request()->domain().'/downloadapk/'. $filename, 'version' => $appObj->getVersionName(), 'size' => $size, 'name' => $appObj->getPackage(), 'md5' => $data->md5()]);
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

    public function uploadapk()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'num'=>'require',
            'total'=>'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $name = $data['fileName'];
        $filename = $name."_".$data['num'];
        $rs = $file->move('./downloadapk', $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './downloadapk/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./downloadapk/'. $name, file_get_contents($path));
                    } else {
                        file_put_contents('./downloadapk/'. $name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                $appObj  = new Apkparser(); 
                $targetFile = './downloadapk/'.$name;//apk所在的路径地址
                $res   = $appObj->open($targetFile);
                return json(['status' => 1, 'msg' => '上传成功', 'url' => request()->domain().'/downloadapk/'. $name, 'version' => $appObj->getVersionName(), 'size' => filesize('./downloadapk/'. $name), 'name' => $appObj->getPackage(), 'md5' => md5_file('./downloadapk/'. $name)]);
            } else {
                return json(['status' => 0, 'err_code' => 0,  'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

    public function is_json($data = '', $assoc = false) {
        $data = json_decode($data, $assoc);
        if (($data && (is_object($data))) || (is_array($data) && !empty($data))) {
            return true;
        }
        return false;
    }

    public function image()
    {
        $file = request()->file('file');
        $filename = date('Ymd').'/'. md5(date('His')).'.jpg';
        $info = $file->move('./upload/files', $filename);//图片保存路径
        if ($info) {
            return json(['code' => 1, 'msg' => request()->domain().'/upload/files/'. $filename]);
        }else{
            return json(['code' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

    public function zip()
    {
        // $file = request()->file('file');
        // $info = $file->getInfo();
        // $name = $info['name'];
        // $filename = $name;
        // $data = $file->validate(['ext'=>'zip'])->move('./downloadzip', $filename);//图片保存路径
        // if ($data) {
        //     $url = request()->domain().'/downloadzip/'. $filename;
        //     return self::actionLog("手机App更新", $url, "zip上传");
        // }else{
        //     return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        // }
        // exit;
        set_time_limit(0);
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
        $zipname = substr($name, 0 , -4);
        $zipary = explode("_", $zipname);
        $version = "unknow";
        if (count($zipary) == 2 && $zipary[0] == 'dist') {
            $version = $zipary[1];
        } else {
            return json(['status' => -900, 'err_code' => -900,  'msg' => '包名不正确,请修改包名为dist_版本号的zip压缩包']);
        }


        $filename = $name."_".$data['num'];
        $rs = $file->move('./downloadzip', $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './downloadzip/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./downloadzip/'. $name, file_get_contents($path));
                    } else {
                        file_put_contents('./downloadzip/'. $name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                // $res = self::actionLog("手机App更新", request()->domain().'/downloadzip/'. $name, "zip上传");
                // if (!$res) {
                //     return json(['code' => -900, 'err_code' => -900,  'msg' => 'IPFS服务异常']);
                // }
                // $res = json_decode($res,true);
                // if ($res['status'] !=0) {
                //     return json($res);
                // }
                return json(['status' => 1, 'err_code' => 0, 'msg' => '上传成功', 'url' => request()->domain().'/downloadzip/'. $name, 'version' => $version, 'size' => filesize('./downloadzip/'. $name), 'name' => $name, 'md5' => md5_file('./downloadzip/'. $name), 'hashid' => '']);
            } else {
                return json(['status' => 0, 'err_code' => 0, 'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }  
    }

    public function test()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'num'=>'require',
            'total'=>'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $name = $data['fileName'];
        $filename = $name."_".$data['num'];
        $rs = $file->move('./downloadtest', $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './downloadtest/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./downloadtest/'. $name, file_get_contents($path));
                    } else {
                        file_put_contents('./downloadtest/'. $name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                return json(['status' => 1, 'msg' => request()->domain().'/downloadtest/'. $name]);
            } else {
                return json(['status' => 0, 'err_code' => 0,  'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

    public function uploadipfs()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'num'=>'require',
            'total'=>'require',
            'channel1'=>'require',
            'channel2'=>'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $name = $data['fileName'];
        $zipname = substr($name, 0 , -4);
        $zipary = explode("_", $zipname);
        $version = "unknow";
        if (count($zipary) == 2 && $zipary[0] == 'ipfs') {
            $version = $zipary[1];
        } else {
            return json(['status' => -900, 'err_code' => -900,  'msg' => '包名不正确,请修改包名为ipfs_版本号的zip压缩包']);
        }
        $filename = $name."_".$data['num'];
        $rs = $file->move('./download/ipfs', $filename);//图片保存路径
        if ($rs) {
            if ($data['total'] == $data['num']) {
                //合并处理
                for($i= 1;$i<=$data['total'];$i++){
                    $path = './download/ipfs/'. $name . "_". $i;
                    if ($i == 1) {
                        file_put_contents('./download/ipfs/'. $name, file_get_contents($path));
                    } else {
                        file_put_contents('./download/ipfs/'. $name, file_get_contents($path), FILE_APPEND);
                    }
                    unlink($path);//删除合并过的文件
                }
                $param = array(
                    "tb_name"   => 'tb_action_log',
                    "insert"    => [
                        [
                            $data['channel1'], $data['channel2'], $version, request()->domain().'/download/ipfs/'. $name, md5_file('./download/ipfs/'. $name)
                        ]
                    ]
                );
                $res = self::loadApiData("store/insert_table", $param);
                if (!$res) {
                    return json(['code' => -900, 'err_code' => -900,  'msg' => 'IPFS服务异常']);
                }
                $res = json_decode($res,true);
                if ($res['status'] !=0) {
                    return json($res);
                }
                return json(['status' => 1, 'err_code' => 0,  'msg' => '上传成功']);
            } else {
                return json(['status' => 0, 'err_code' => 0,  'msg' => '上传成功']);
            }
            
        }else{
            return json(['status' => -900, 'err_code' => -900,  'msg' => '上传失败']);
        }
        exit;
    }

}
