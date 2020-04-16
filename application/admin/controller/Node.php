<?php
namespace app\admin\controller;
use think\Validate;

class Node extends Common
{

    //PTFS节点管理页面，点击表格中的某一行，左侧小窗口显示节点的详细信息
    public function queryNodeInfoById()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "nodeType"      => '超级管理节点',
        //         "minerId"       => 'CiCZtT96bBpfbUP1PkahFz4nPfaECHCgHPowxSnKyvQZrF',
        //         "ip"            => '/ip4/192.168.0.168/tcp/7001',
        //         "nodeState"     => 1,
        //         "region"        => '湖北',
        //         "city"          => '武汉',
        //         "RgnGrpInfo"    => '分区0-分组0',
        //         "storeUsage"    => 0,
        //         "totalCap"      => 0,
        //     )
        // );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
            'NodeId'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "node_hash" => $data['NodeId'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_details_by_node_hash", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $node_type = $return_data['data']['node_type'];
            if ($node_type == "S") {
                $nodeType = "超级管理节点";
            } else if ($node_type == "SMiner") {
                $nodeType = "超级存储节点";
            } else if ($node_type == "Miner") {
                $nodeType = "矿机节点";
            } else {
                $nodeType = "应用层节点";
            }
            $minerId                = $return_data['data']['node_hash'];
            $ip                     = $return_data['data']['node_ip'];
            $nodeState              = $return_data['data']['node_state'];
            $node_addr              = explode("-", $return_data['data']['node_addr']);
            $region                 = $node_addr[0];
            $city                   = $node_addr[1];
            $RgnGrpInfo             = $return_data['data']['node_area_group'];
            $node_store_usage_cap   = explode("/", $return_data['data']['node_store_usage_cap']);
            $storeUsage             = $node_store_usage_cap[0];
            $totalCap               = $node_store_usage_cap[1];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "nodeType"      => $nodeType,
                    "minerId"       => $minerId,
                    "ip"            => $ip,
                    "nodeState"     => $nodeState,
                    "region"        => $region,
                    "city"          => $city,
                    "RgnGrpInfo"    => $RgnGrpInfo,
                    "storeUsage"    => $storeUsage,
                    "totalCap"      => $totalCap,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    // PTFS节点管理页面，左侧小窗口,查看详情,查询节点上备份和缓存的文件列表
    public function queryNodeStoredFileListByNodeId()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "backUpFileList"  => [
        //             [
        //                 "fileName"          => "test.ts",
        //                 "fileSize"          => 1524256,
        //                 "fileCreateTime"    => date("Y-m-d H:i:s", 1559103125),
        //                 "fileHashId"        => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //                 "fileOwner"         => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             ],
        //             [
        //                 "fileName"          => "test2.ts",
        //                 "fileSize"          => 1524256,
        //                 "fileCreateTime"    => date("Y-m-d H:i:s", 1559103125),
        //                 "fileHashId"        => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //                 "fileOwner"         => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             ],
        //         ],
        //         "cacheFileList"   => [
        //             [
        //                 "fileName"          => "test3.ts",
        //                 "fileSize"          => 1524256,
        //                 "fileCreateTime"    => date("Y-m-d H:i:s", 1559103125),
        //                 "fileHashId"        => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //                 "fileOwner"         => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             ],
        //             [
        //                 "fileName"          => "test4.ts",
        //                 "fileSize"          => 1524256,
        //                 "fileCreateTime"    => date("Y-m-d H:i:s", 1559103125),
        //                 "fileHashId"        => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //                 "fileOwner"         => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             ],
        //         ],
        //     )
        // );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
            'Nodeid'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "node_hash" => $data['Nodeid'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_backup_cached_file_list", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $backup_file_list   = $return_data['data']['backup_file_list'];
            $backUpFileList     = array();
            for ($i = 0; $i < count($backup_file_list); $i++) {
                $backUpFileList[$i]['fileName']         = $backup_file_list[$i]['file_name'];
                $backUpFileList[$i]['fileSize']         = $backup_file_list[$i]['file_size'];
                $backUpFileList[$i]['fileCreateTime']   = date("Y-m-d H:i:s", $backup_file_list[$i]['file_upload_ts']);
                $backUpFileList[$i]['fileHashId']       = $backup_file_list[$i]['file_hash'];
                $backUpFileList[$i]['fileOwner']        = $backup_file_list[$i]['file_owner'];
            }
            $cache_file_list    = $return_data['data']['cache_file_list'];
            $cacheFileList      = array();
            for ($i = 0; $i < count($cache_file_list); $i++) {
                $cacheFileList[$i]['fileName']          = $cache_file_list[$i]['file_name'];
                $cacheFileList[$i]['fileSize']          = $cache_file_list[$i]['file_size'];
                $cacheFileList[$i]['fileCreateTime']    = date("Y-m-d H:i:s", $cache_file_list[$i]['file_upload_ts']);
                $cacheFileList[$i]['fileHashId']        = $cache_file_list[$i]['file_hash'];
                $cacheFileList[$i]['fileOwner']         = $cache_file_list[$i]['file_owner'];
            }
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "backUpFileList"  => $backUpFileList,
                    "cacheFileList"   => $cacheFileList,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    // PTFS节点管理页面，分区/分组 （第二个选项页）
    public function queryRegionGroupInfo()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => [
        //         [
        //             "nodeType"      => "超级管理节点",
        //             "nodeHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             "regionNum"     => 1,
        //             "groupCnt"      => 10,
        //             "minerCnt"      => 91,
        //         ],
        //         [
        //             "nodeType"      => "超级存储节点",
        //             "nodeHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             "regionNum"     => 2,
        //             "groupCnt"      => 10,
        //             "minerCnt"      => 91,
        //         ],
        //         [
        //             "nodeType"      => "矿机节点",
        //             "nodeHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             "regionNum"     => 3,
        //             "groupCnt"      => 10,
        //             "minerCnt"      => 91,
        //         ],
        //         [
        //             "nodeType"      => "应用层节点",
        //             "nodeHashId"    => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr",
        //             "regionNum"     => 4,
        //             "groupCnt"      => 10,
        //             "minerCnt"      => 91,
        //         ],
        //     ]
        // );
        // return json($data);

        $param = array(
            "current_ts" => time(),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_region_group_info", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $node_region_group = $return_data['data']['node_region_group'];
            $noderegiongroup = array();
            for ($i = 0; $i < count($node_region_group); $i ++) {
                $node_type = $node_region_group[$i]['node_type'];
                if ($node_type == "S") {
                    $nodeType = "超级管理节点";
                } else if ($node_type == "SMiner") {
                    $nodeType = "超级存储节点";
                } else if ($node_type == "Miner") {
                    $nodeType = "矿机节点";
                } else {
                    $nodeType = "应用层节点";
                }
                $noderegiongroup[$i]['nodeType']    = $nodeType;
                $noderegiongroup[$i]['nodeHashId']  = $node_region_group[$i]['node_hash'];
                $noderegiongroup[$i]['regionNum']   = $node_region_group[$i]['region_no'];
                $noderegiongroup[$i]['groupCnt']    = $node_region_group[$i]['group_cnt'];
                $noderegiongroup[$i]['minerCnt']    = $node_region_group[$i]['region_node_cnt'];
            }
            
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => $noderegiongroup
            );
            return json($data);
        }
        return json($return_data);
    }

    // 点击 PTFS节点管理页面，分区/分组页面，表格中的超链接
    public function queryGroupInfo()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => [
        //         [
        //             "groupNum" => 1,
        //             "nodeHash" => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr"
        //         ],
        //         [
        //             "groupNum" => 1,
        //             "nodeHash" => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr2"
        //         ],
        //         [
        //             "groupNum" => 2,
        //             "nodeHash" => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr3"
        //         ],
        //         [
        //             "groupNum" => 2,
        //             "nodeHash" => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr4"
        //         ],
        //         [
        //             "groupNum" => 2,
        //             "nodeHash" => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr5"
        //         ],
        //     ]
        // );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
            'nodeId'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "node_hash" => $data['nodeId'],
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_group_detail", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $node_region_group = $return_data['data']['node_region_group'];
            $noderegiongroup = array();
            for ($i = 0; $i < count($node_region_group); $i ++) {
                for ($j = 0; $j < count($node_region_group[$i]); $j ++) {
                    $noderegiongroup[] = array(
                        "groupNum" => $node_region_group[$i][$j]['group_no'],
                        "nodeHash" => $node_region_group[$i][$j]['node_hash']
                    ); 
                }
            }
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => $noderegiongroup
            );
            return json($data);
        }
        return json($return_data);
    }

    public function queryAllConfigRecords()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => [
        //         [
        //             "timeStamp"         => date("Y-m-d H:i:s", 1559103125),
        //             "msgId"             => "123123123",
        //             "commandTargetId"   => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr3",
        //             "commandExecResult" => "成功",
        //             "commandExecData"   => "",
        //             "descript"          => "1231231",
        //         ],
        //         [
        //             "timeStamp"         => date("Y-m-d H:i:s", 1559103125),
        //             "msgId"             => "123123123",
        //             "commandTargetId"   => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr3",
        //             "commandExecResult" => "成功",
        //             "commandExecData"   => "",
        //             "descript"          => "1231231",
        //         ],
        //         [
        //             "timeStamp"         => date("Y-m-d H:i:s", 1559103125),
        //             "msgId"             => "123123123",
        //             "commandTargetId"   => "CidsbdV6TXeNybdDPUKv78uvWbYVcdGcYq3k2S7Adt3Usr3",
        //             "commandExecResult" => "成功",
        //             "commandExecData"   => "",
        //             "descript"          => "1231231",
        //         ],
        //     ]
        // );
        // return json($data);
        $param = array(
            "current_ts" => time(),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_group_detail", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $node_region_group = $return_data['data']['node_region_group'];
            $noderegiongroup = array();
            for ($i = 0; $i < count($backup_file_list); $i ++) {
                for ($j = 0; $j < count($backup_file_list[$i]); $j ++) {
                    $noderegiongroup[] = array(
                        "groupNum" => $backup_file_list[$i][$j]['group_no'],
                        "nodeHash" => $backup_file_list[$i][$j]['node_hash']
                    ); 
                }
            }
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => $noderegiongroup
            );
            return json($data);
        }
        return json($return_data);
    }

}
