<?php
namespace app\admin\controller;
use think\Validate;

class Log extends Common
{

    public function queryLogTypes()
    {
        $data = array(
            "status" => 0,
            "err_code" => 0,
            "result" => "ok",
            "msg"    => "查询成功",
            "data"   => ["日志", "通知"],
        );
        return json($data);
    }


    // 日志查看页面，根据条件搜索符合条件的消息
    public function showNodeNetworkLogs()
    {
            
        $data = input('post.');
        $validation = new Validate([
            'startTime' => 'require',
            'stopTime'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "start_ts"  => strtotime($data['startTime']),
            "end_ts"    => strtotime($data['stopTime']),
            "file_hash" => $data['fileHash'],
            "log_type"  => $data['logType'],
            "node_hash" => $data['nodeHash'],
            "page_no"   => isset($data['page']) ? $data['page'] : 0
        );
        $return_data = self::loadApiData("bg_manager_tool/query_network_run_log_local", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $logs = $return_data['data']['rows'];
            $result = array();
            for ($i = 0; $i < count($logs); $i ++) { 
                $node_type = $logs[$i]['node_type'];
                if ($node_type == "S") {
                    $nodeType = "超级管理节点";
                } else if ($node_type == "SMiner") {
                    $nodeType = "超级存储节点";
                } else if ($node_type == "Miner") {
                    $nodeType = "矿机节点";
                } else {
                    $nodeType = "应用层节点";
                }    
                $result[] = array( 
                    "timestamp" => date("Y-m-d H:i:s", $logs[$i]['log_timestamp']),
                    "minerId"   => $logs[$i]['node_hash'],
                    "nodeType"  => $nodeType,
                    "msg"       => $logs[$i]['log_msg'],
                    "logType"   => $logs[$i]['opt_type'],
                ); 
            }
            //$count = $return_data['result']['les_count'];
            //$page = $return_data['result']['page'];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => $result,
                //"count"  => $count,
                //"page"   => $page
            );
            return json($data);
        }
        return json($return_data);
    }

     // 删除日志表
    public function deleteHistoryDatas()
    {
        // $data = input('post.');
        // $validation = new Validate([
        //     'tables'  => 'require',
        // ]);
        // //验证表单
        // if(!$validation->check($data)){
        //     return json(['status' => -1, 'msg' => $validation->getError()]);
        // }
        // if ($data['tables'][0] == '元表') {
        //     $tb_name = "metadata_t";
        // } else if ($data['tables'][0] == '文件存储分布表') {
        //     $tb_name = "file_storedist_t";
        // } else if ($data['tables'][0] == '节点状态表') {
        //     $tb_name = "node_basicinfo_t";
        // } else if ($data['tables'][0] == '日志详情表') {
        //     $tb_name = "notify_t";
        // } else {
        //     return json(['status' => -1, 'msg' => '未查到表单']);
        // }
        // $data = array(
        //         "result" => "ok",
        //         "msg"    => "表内容已清空",
        //         "data"   => null,
        //     );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
            'tables'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        if ($data['tables'][0] == '元表') {
            $tb_name = "metadata_t";
        } else if ($data['tables'][0] == '文件存储分布表') {
            $tb_name = "file_storedist_t";
        } else if ($data['tables'][0] == '节点状态表') {
            $tb_name = "node_basicinfo_t";
        } else if ($data['tables'][0] == '日志详情表') {
            $tb_name = "notify_t";
        } else {
            return json(['status' => -900, 'err_code' => -900,  'msg' => '未查到表单']);
        }
        $param = array(
            "tb_name"   => $tb_name,
            "where"     => ""
        );
        $return_data = self::loadApiData("store/delete_record", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "表内容已清空",
                "data"   => null,
            );
            return json($data);
        }
        return json($return_data);
    }

    public function applogList()
    {
        $data = input('post.');
        $page = isset($data['page']) ? intval($data['page']) : 0;
        $param = array(
            "page" => $page,
            "tb_name"  => 'tb_action_log',
            "col_name" => ["action", "description", "username", "time_create"],
            "where" => "action='手机App更新'",
            "order" => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);
    }

    public function actionlogList2()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $where = "action in ('新增','修改', '删除', '导入', '导出')";
        $where.= $data['search']=="" ? "" : " and username = '".$data['search']."'";
        $where.= $data['action']=="" ? "" : " and action = '".$data['action']."'";
        $where.= $data['start_ts']=="" ? "" : " and time_create >= ".$data['start_ts'];
        $where.= $data['end_ts']=="" ? "" : " and time_create <= ".$data['end_ts'];
        $where.= $data['status']===null ? "" : ($data['status'] == 1 ? " and beforevalue<>'失败'" : " and beforevalue='失败'");
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"   => 'tb_action_log',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function actionlogList()
    {   
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'page'  =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $where = "action not in ('新增','修改', '删除', '导入', '导出')";
        $param = array(
            "page" => isset($data['page']) ?  intval($data['page']) : 0,
            "page_size" => 10,
            "tb_name"   => 'tb_action_log',
            "col_name"  => "*",
            "where"     => $where,
            "order"     => 'id desc',
        );
        return self::loadApiData("store/find_table", $param);

    }

    public function setactionlog()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'action'  =>  'require',
            'description'  =>  'require',
            'remark'  =>  'require',
            'type' =>  'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $uid = isset($_COOKIE['adminid']) ? $_COOKIE['adminid'] : 0;
        $uname = isset($_COOKIE['adminuser']) ? $_COOKIE['adminuser'] : "本地测试用户";
        return self::actionLog($data['action'], $data['description'], $data['remark'], $data['type'], $uid, $uname);

    }

}
