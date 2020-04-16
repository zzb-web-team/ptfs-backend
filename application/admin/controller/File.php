<?php
namespace app\admin\controller;
use think\Validate;
use think\Request;

class File extends Common
{
    // PTFS 文件管理，根据条件查询
    public function queryFileSummaryByConditions()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => [
        //         [
        //             "fileName"      => "test.ts",
        //             "fileSize"      => 125585325,
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd",
        //             "fileStoreTime" => "2019-05-30 12:00:01"
        //         ],
        //         [
        //             "fileName"      => "test2.ts",
        //             "fileSize"      => 124585325,
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd2",
        //             "fileStoreTime" => "2019-05-30 12:00:02"
        //         ],
        //         [
        //             "fileName"      => "test3.ts",
        //             "fileSize"      => 94585325,
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd3",
        //             "fileStoreTime" => "2019-05-30 12:00:03"
        //         ],
        //         [
        //             "fileName"      => "test4.ts",
        //             "fileSize"      => 334585325,
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd4",
        //             "fileStoreTime" => "2019-05-30 12:00:04"
        //         ]
        //     ],
        //     "count"  => 0,
        //     "page"   => 0
        // );
        // return json($data);
            
        $data = input('post.');
        $page = isset($data['page']) ? $data['page'] : 0;
        $param = array(
            "start_ts"      => strtotime($data['startTime']),
            "end_ts"        => strtotime($data['endTime']),
            "owner_hash"    => $data['userId'],
            "file_name"     => $data['fileName'],
            "node_hash"     => $data['minerId'],
            "file_hash"     => $data['fileHashId'],
            "page_no"       => $page
        );
        $return_data = self::loadApiData("bg_manager_tool/query_file_detail_by_params", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $result_cols = $return_data['data']['cols'];
            $result = array();
            for ($i = 0; $i < count($result_cols); $i ++) {     
                $result[] = array(
                    "fileName"      => $result_cols[$i]['file_name'],
                    "fileSize"      => $result_cols[$i]['file_size'],
                    "fileHashId"    => $result_cols[$i]['file_hash'],
                    "fileStoreTime" => date("Y-m-d H:i:s", $result_cols[$i]['upload_timestamp']),
                ); 
            }
            $count  = $return_data['data']['les_count'];
            $page   = $return_data['data']['page'];
            $data   = array(
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => $result,
                "count"  => $count,
                "page"   => $page
            );
            return json($data);
        }
        return json($return_data);
    }

    // PTFS 文件管理，查看详情按钮弹出窗口第一个页面（根据文件哈希，查询文件备份、缓存详情） 
    public function queryOneFileBackupOwnerRecords()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => [
        //         "fileBackUpList" => [
        //             [
        //                 "storeNodeId"           => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd",
        //                 "storeNodeRegionNum"    => 1,
        //                 "storeNodeGroupNum"     => 2,
        //                 "storeTimestamp"        => date("Y-m-d H:i:s", 1559103125),
        //                 "storeType"             => "备份",
        //                 "nodeType"              => "超级管理节点",
        //                 "nodeStoreUsage"        => 124585325, 
        //             ],
        //             [
        //                 "storeNodeId"           => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd",
        //                 "storeNodeRegionNum"    => 1,
        //                 "storeNodeGroupNum"     => 2,
        //                 "storeTimestamp"        => date("Y-m-d H:i:s", 1559103125),
        //                 "storeType"             => "缓存",
        //                 "nodeType"              => "超级存储节点",
        //                 "nodeStoreUsage"        => 124585325, 
        //             ]
        //         ],
        //         "fileOwnerList" => [
        //             [
        //                 "owner"                 => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd",
        //                 "fileName"              => "test2.ts",
        //                 "timestamp"             => date("Y-m-d H:i:s", 1559103125),
        //             ],
        //              [
        //                 "owner"                 => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdgwd",
        //                 "fileName"              => "test3.ts",
        //                 "timestamp"             => date("Y-m-d H:i:s", 1559104125),
        //             ]
        //         ],
        //         "fileExpLogList" => [
        //             [
        //                 "code"          => 1,
        //                 "action"        => 1,
        //                 "timestamp"     => date("Y-m-d H:i:s", 1559103125),
        //                 "nodeSelf"      => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg12",
        //                 "nodeTarget"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg34",
        //                 "descript"      => "miner begin download file",
        //             ],
        //             [
        //                 "code"          => 1,
        //                 "action"        => 2,
        //                 "timestamp"     => date("Y-m-d H:i:s", 1559103125),
        //                 "nodeSelf"      => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg56",
        //                 "nodeTarget"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg78",
        //                 "descript"      => "miner begin download file",
        //             ]
        //         ]
        //     ]
        // );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
             'FileHashId'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "file_hash" => $data['FileHashId'],
            "page_num" => isset($data['page']) ? $data['page'] : 0
        );
        $return_data    = self::loadApiData("bg_manager_tool/query_file_backup_cache_detail", $param);
        $return_data    = json_decode($return_data, true);
        if ($return_data['status'] !=0) {
            return json($return_data);
        }
        $return_data2   = self::loadApiData("bg_manager_tool/query_file_all_owner_list", $param);
        $return_data2   = json_decode($return_data2, true);
        if ($return_data2['status'] !=0) {
            return json($return_data2);
        }
        $return_data3   = self::loadApiData("bg_manager_tool/query_file_upload_log", $param);
        $return_data3   = json_decode($return_data3, true);
        if ($return_data3['status'] !=0) {
            return json($return_data3);
        }
        if ($return_data['status'] == 0) {
            $result_rows = $return_data['data']['rows'];
            $fileBackUpList = array();
            for ($i = 0; $i < count($result_rows); $i ++) {  
                $node_type = $result_rows[$i]['store_node_type'];
                if ($node_type == "S") {
                    $nodeType = "超级管理节点";
                } else if ($node_type == "SMiner") {
                    $nodeType = "超级存储节点";
                } else if ($node_type == "Miner") {
                    $nodeType = "矿机节点";
                } else {
                    $nodeType = "应用层节点";
                } 
                $store_type = $result_rows[$i]['store_type'];
                if ($store_type == 1) {
                    $storeType = "备份";
                } else if ($store_type == 2) {
                    $storeType = "缓存";
                } else {
                    $storeType = "备份(sminer)";
                }
                $fileBackUpList[] = array(
                    "storeNodeId"           => $result_rows[$i]['node_hash'],
                    "storeNodeRegionNum"    => $result_rows[$i]['node_region'],
                    "storeNodeGroupNum"     => $result_rows[$i]['node_group'],
                    "storeTimestamp"        => date("Y-m-d H:i:s", $result_rows[$i]['store_ts']),
                    "storeType"             => $storeType,
                    "nodeType"              => $nodeType,
                    "nodeStoreUsage"        => $result_rows[$i]['file_size'],
                ); 
            }
            //$count = $return_data['result']['les_count'];
            //$page = $return_data['result']['page'];
            $result_rows2 = $return_data2['data']['rows'];
            $fileOwnerList = array();
            for ($i = 0; $i < count($result_rows2); $i ++) {  
                $fileOwnerList[] = array(
                    "owner"     => $result_rows2[$i]['file_owner_hash'],
                    "fileName"  => $result_rows2[$i]['file_name'],
                    "timestamp" => date("Y-m-d H:i:s", $result_rows2[$i]['file_upload_ts']),
                ); 
            }
            //$count = $return_data['result']['les_count'];
            //$page = $return_data['result']['page'];
            $result_rows3   = $return_data3['data']['rows'];
            $fileExpLogList = array();
            for ($i = 0; $i < count($result_rows3); $i ++) {  
                $fileExpLogList[] = array(
                    "code"          => $result_rows3[$i]['code'],
                    "action"        => $result_rows3[$i]['action'],
                    "timestamp"     => date("Y-m-d H:i:s", $result_rows3[$i]['notify_timestamp']),
                    "nodeSelf"      => $result_rows3[$i]['node_self'],
                    "nodeTarget"    => $result_rows3[$i]['node_target'],
                    "descript"      => $result_rows3[$i]['descript'],
                ); 
            }
            //$count = $return_data['result']['les_count'];
            //$page = $return_data['result']['page'];
            $data = array(
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => [
                    "fileBackUpList"    => $fileBackUpList,
                    "fileOwnerList"     => $fileOwnerList,
                    "fileExpLogList"    => $fileExpLogList
                ]
            );
            return json($data);
        }
        return json($return_data);
    }

    public function updateNodeConfig()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "上传配置文件成功",
        //     "data"   => null,
        // );
        // return json($data);
        $data = input('post.');
        $validation = new Validate([
             'setConfigDescript'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $file = request()->file('file');
        $filename = date('Ymd').'/'. md5(date('His')).'.cfg';
        $info = $file->validate(['ext'  => 'cfg'])->move('./upload/config', $filename);//图片保存路径
        if ($info) {
            $param =  array(
                "task_id" => "CONFIG".time(),
                "cmd_id" => 1003,
                "cmd_data" => array(
                    "file"      => $data['setConfigDescript'],
                    "descript"  => request()->domain().'/upload/config/'. $filename
                )
            );
            $return_data = self::loadApiData("store/send_cmd_msg", $param);
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] == 0) {
                $data = array(
                    "result" => "ok",
                    "msg"    => "上传配置文件成功",
                    "data"   => null,
                );
                return json($data);
            }
            return json($return_data);
        }
        return json(['status' => -900, 'err_code' => -900,  'msg' => '上传cfg文件失败']);
    }

    public function queryBlackListByCondition()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "success",
        //     "data"   => [
        //         [
        //             "descript"      =>"测试1",
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg12",
        //             "fileName"      => "tess.bl",
        //             "version"       => "1.0.1"
        //         ],
        //         [
        //             "descript"      =>"测试2",
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg34",
        //             "fileName"      => "tess2.bl",
        //             "version"       => "1.0.2"
        //         ],
        //         [
        //             "descript"      =>"测试3",
        //             "fileHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt31xdg56",
        //             "fileName"      => "tess3.bl",
        //             "version"       => "1.0.3"
        //         ]

        //     ],
        // );
        // return json($data);
        $data = input('post.');
        $where = "1";
        if (!empty($data['startTime'])) {
            $where .= " and time_create>".strtotime($data['startTime']);
        }
        if (!empty($data['endTime'])) {
            $where .= " and time_create<".strtotime($data['endTime']);
        }
        if (!empty($data['fileName'])) {
            $where .= " and file_name='".$data['fileName']."'";
        }
        if (!empty($data['fileHashId'])) {
            $where .= " and file_hash='".$data['fileHashId']."'";
        }
        if (!empty($data['descript'])) {
            $where .= " and bl_list_descript='".$data['descript']."'";
        }

        $param = array(
            "page" => 0,
            "tb_name"  => 'block_list_config_log',
            "col_name" => ["file_name","file_hash", "bl_list_descript", "bl_list_ver", "id", "time_create"],
            "where" => $where,
            "order" => 'id desc',
        );
        $return_data = self::loadApiData("store/find_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] != 0) {
            return json($return_data);
        }
        $cols = $return_data['data']['cols'];
        $list = array();
        for ($i = 0; $i < count($cols); $i ++) {
            $list[$i]['descript'] = $cols[$i]['bl_list_descript'];
            $list[$i]['fileHashId'] = $cols[$i]['file_hash'];
            $list[$i]['fileName'] = $cols[$i]['file_name'];
            $list[$i]['version'] = $cols[$i]['bl_list_ver'];
            $list[$i]['id'] = $cols[$i]['id'];
        }
        $data = array(
            "result" => "ok",
            "msg"    => "success",
            "data"   => $list,
        );
        return json($data);
    }

    public function addNewForbiddenFile()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "success",
        // );
        // return json($data);
        $data = input('post.');
        $validation = new Validate([
            'descript'      => 'require',
            'fileHashId'    => 'require',
            'fileName'      => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -1, 'msg' => $validation->getError()]);
        }
        $insert[] = [
            $data['fileName'], $data['fileHashId'], '1.0.1', $data['descript']
        ];
        $param = array(
            "tb_name"   => 'block_list_config_log',
            "insert"    => $insert
        );
        $return_data = self::loadApiData("store/insert_table", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status']!=0) {
            return json($return_data);
        }
        $param = array(
            "cmd_type" => 2,
            "snode_hash" => "*",
            "target_node"  => "*",
            "cmd_data" => $data['fileHashId'],
            "cmd_desc" => "block file setted!"
        );
        $return_data = self::loadApiData("bg_manager_tool/send_ptfs_cmd", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data = array(
                "result" => "ok",
                "msg"    => "success",
            );
            return json($data);
        }
        return json($return_data);
        // $return_data = self::loadApiData("store/send_cmd_msg", $param);
        // $return_data = json_decode($return_data, true);
        // if ($return_data['status'] == 0) {
        //     $data = array(
        //         "result" => "ok",
        //         "msg"    => "success",
        //     );
        //     return json($data);
        // }
        // return json($return_data);
        // }
        //return json(['status' => -1, 'msg' => '上传cfg文件失败']);
    }


    public function uploadBlackListFile()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "上传配置文件成功",
        //     "data"   => null,
        // );
        // return json($data);
        $file = request()->file('file');
        $filename = date('Ymd').'/'. md5(date('His')).'.bl';
        $info = $file->validate(['ext' => 'bl'])->move('./upload/black', $filename);//图片保存路径
        if ($info) {
            $param =  array(
                "task_id"   => "CONFIG".time(),
                "cmd_id"    => 1002,
                "cmd_data"  => array(
                    "file"  => request()->domain().'/upload/black/'. $filename
                )
            );
            $return_data = self::loadApiData("store/send_cmd_msg", $param);
            $return_data = json_decode($return_data, true);
            if ($return_data['status'] == 0) {
                $data = array(
                    "result" => "ok",
                    "msg"    => "上传配置文件成功",
                    "data"   => null,
                );
                return json($data);
            }
            return json($return_data);
        }
        return json(['status' => -1, 'msg' => '上传cfg文件失败']);
    }

    public function deleteFileList()
    {
        $data = input('post.');
        $validation = new Validate([
            'FileHashId'    => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $param = array(
            "cmd_type" => 1,
            "snode_hash" => "*",
            "target_node"  => "*",
            "cmd_data" => $data['FileHashId'],
            "cmd_desc" => "delete file cmd send!"
        );
        $return_data = self::loadApiData("bg_manager_tool/send_ptfs_cmd", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data = array(
                "result" => "ok",
                "msg"    => "success",
            );
            return json($data);
        }
        return json($return_data);
    }

}
