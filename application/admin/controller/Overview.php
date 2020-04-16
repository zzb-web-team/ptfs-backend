<?php
namespace app\admin\controller;
use think\Validate;

class Overview extends Common
{

    //PTFS 网络概览页面 顶部5个数据项
    public function querySummary()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "TotalFileCnt"        => 99,
        //         "FileTotalStoreUsage" => self::formatByte(1233456456,"GB"),
        //         "OnlineNodeCount"     => 54,
        //         "TotalStoreUsage"     => self::formatByte(12312322131,"GB"),
        //         "TotalMinerStoreCap"  => self::formatByte(12312312312312,"TB"),
        //     )
        // );
        // return json($data);

        $param = array(
            "current_ts" => time() 
        );
        $return_data = self::loadApiData("bg_manager_tool/query_current_ptfs_overview", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "TotalFileCnt"        => $return_data['data']['total_file_cnt'],
                    "FileTotalStoreUsage" => self::formatByte($return_data['data']['file_store_usage'],"GB"),
                    "OnlineNodeCount"     => $return_data['data']['online_node_cnt'],
                    "TotalStoreUsage"     => self::formatByte($return_data['data']['total_store_usage'],"GB"),
                    "TotalMinerStoreCap"  => self::formatByte($return_data['data']['total_store_cap'],"TB"),
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    //PTFS网络概览---  左上角，在线节点曲线
    public function queryOnlineNodeHistgraph()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "dateList"        => ["2019-05-21", "2019-05-22", "2019-05-23", "2019-05-24", "2019-05-25", "2019-05-26", "2019-05-27"],
        //         "onlineNodeCntDailyList" => ["1232", "444", "1212", "422", "577", "110", "795"],
        //     )
        // );
        // return json($data);

        $data = input('post.');
        $validation = new Validate([
             'startTime'  =>  'require',
             'endTime'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "start_timestamp" => strtotime($data['startTime']),
            "end_timestamp" => strtotime($data['endTime']),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_online_node_histgraph", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $dateList = $return_data['data']['daytime_list'];
            for ($i=0;$i<count($dateList);$i++) {
                $dateList[$i] = date("Y-m-d", $dateList[$i]);
            }
            $onlineNodeCntDailyList = $return_data['data']['onlinecnt_list'];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "dateList"        => $dateList,
                    "onlineNodeCntDailyList" => $onlineNodeCntDailyList,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    //PTFS网络概览 ---  右上角，存储量/存储空间曲线
    public function queryDevStoreHistgraph()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "dateList"        => ["2019-05-21", "2019-05-22", "2019-05-23", "2019-05-24", "2019-05-25", "2019-05-26", "2019-05-27"],
        //         "ipfsCapList"     => ["70", "67", "75", "32", "51", "44", "32"],
        //         "totalDevCapList" => ["702", "673", "575", "332", "151", "444", "232"],
        //     )
        // );
        // return json($data);

        $data = input('post.');
        $validation = new Validate([
             'startTime'  =>  'require',
             'endTime'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "start_timestamp" => strtotime($data['startTime']),
            "end_timestamp" => strtotime($data['endTime']),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_store_usage_histgraph", $param);
        $return_data = json_decode($return_data, true);
        $return_data2 = self::loadApiData("bg_manager_tool/query_store_cap_histgraph", $param);
        $return_data2 = json_decode($return_data2, true);
        if ($return_data['status'] == 0) {
            $dateList = $return_data['data']['daytime_list'];
            for ($i=0;$i<count($dateList);$i++) {
                $dateList[$i] = date("Y-m-d", $dateList[$i]);
            }
            $ipfsCapList = $return_data['data']['store_usage_list'];
            $totalDevCapList = $return_data2['data']['store_cap_list'];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "dateList"        => $dateList,
                    "ipfsCapList"     => $ipfsCapList,
                    "totalDevCapList" => $totalDevCapList,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    public function queryStoreUsageHistgraph()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "dateList"      => ["2019-05-21", "2019-05-22", "2019-05-23", "2019-05-24", "2019-05-25", "2019-05-26", "2019-05-27"],
        //         "storeUsage"    => ["70", "67", "75", "32", "51", "44", "32"],
        //     )
        // );
        // return json($data);

        $data = input('post.');
        $validation = new Validate([
             'startTime'  =>  'require',
             'endTime'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "start_timestamp" => strtotime($data['startTime']),
            "end_timestamp" => strtotime($data['endTime']),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_file_store_usage_histgraph", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $dateList = $return_data['data']['daytime_list'];
            for ($i=0;$i<count($dateList);$i++) {
                $dateList[$i] = date("Y-m-d", $dateList[$i]);
            }
            $storeUsage = $return_data['data']['file_store_usage_list'];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "dateList"      => $dateList,
                    "storeUsage"    => $storeUsage,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    public function queryFileCntHistgraph()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "dateList"  => ["2019-05-21", "2019-05-22", "2019-05-23", "2019-05-24", "2019-05-25", "2019-05-26", "2019-05-27"],
        //         "fileCnt"   => ["70", "67", "75", "32", "51", "44", "32"],
        //     )
        // );
        // return json($data);
            
        $data = input('post.');
        $validation = new Validate([
             'startTime'  =>  'require',
             'endTime'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }

        $param = array(
            "start_timestamp" => strtotime($data['startTime']),
            "end_timestamp" => strtotime($data['endTime']),
        );
        $return_data = self::loadApiData("bg_manager_tool/query_file_cnt_histgraph", $param);
        $return_data = json_decode($return_data, true);
        if ($return_data['status'] == 0) {
            $dateList = $return_data['data']['daytime_list'];
            for ($i=0;$i<count($dateList);$i++) {
                $dateList[$i] = date("Y-m-d", $dateList[$i]);
            }
            $fileCnt = $return_data['data']['file_cnt_list'];
            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "dateList"  => $dateList,
                    "fileCnt"   => $fileCnt,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    //PTFS 节点管理，“刷新”按钮对应的请求
    public function queryAllNodeProfiesByNodeTypes()
    {
        // $data = array(
        //     "result" => "ok",
        //     "msg"    => "查询成功",
        //     "data"   => array(
        //         "totalNodeCnt"      => [
        //             [
        //                 "nodeType" => "超级管理节点", 
        //                 "nodeCount" => 1
        //             ],
        //             [
        //                 "nodeType" => "超级存储节点", 
        //                 "nodeCount" => 1
        //             ],
        //             [
        //                 "nodeType" => "矿机节点", 
        //                 "nodeCount" => 1
        //             ],
        //             [
        //                 "nodeType" => "应用层节点", 
        //                 "nodeCount" => 1
        //             ],
        //         ],
        //         "regionNodeDist"    => [
        //             [
        //                 "位置" => "湖北",
        //                 "超级管理节点" => 1,
        //                 "超级存储节点" => 0,
        //                 "矿机节点" => 0,
        //                 "应用层节点" => 0,
        //             ],
        //             [
        //                 "位置" => "北京",
        //                 "超级管理节点" => 0,
        //                 "超级存储节点" => 1,
        //                 "矿机节点" => 0,
        //                 "应用层节点" => 0,
        //             ],
        //             [
        //                 "位置" => "上海",
        //                 "超级管理节点" => 0,
        //                 "超级存储节点" => 0,
        //                 "矿机节点" => 1,
        //                 "应用层节点" => 0,
        //             ],
        //             [
        //                 "位置" => "江苏",
        //                 "超级管理节点" => 0,
        //                 "超级存储节点" => 0,
        //                 "矿机节点" => 0,
        //                 "应用层节点" => 1,
        //             ],
        //         ],
        //         "allNodeProfiles"   => [
        //             [
        //                 "nodeType" => "超级管理节点",
        //                 "nodeId" => "CiCZtT96bBpfbUP1PkahFz4nPfaECHCgHPowxSnKyvQZrF",
        //                 "backUpUsage" => 13321321,
        //                 "cacheUsage" => 1123123,
        //                 "totalUsage" => 12113123,
        //             ],
        //             [
        //                 "nodeType" => "超级存储节点",
        //                 "nodeId" => "CiNoWAXvp9LWkvGs2Y5fPHFbVvpKsTPrVq6cK31oZKUPYh",
        //                 "backUpUsage" => 13321321,
        //                 "cacheUsage" => 1123123,
        //                 "totalUsage" => 12113123,
        //             ],
        //             [
        //                 "nodeType" => "矿机节点",
        //                 "nodeId" => "CiR1CZWQdkZGV54HimgpcVPx1bt62KtLrLRZiuP2rgy6fs",
        //                 "backUpUsage" => 13321321,
        //                 "cacheUsage" => 1123123,
        //                 "totalUsage" => 12113123,
        //             ],
        //             [
        //                 "nodeType" => "应用层节点",
        //                 "nodeId" => "CiRdVsqPGuBKuVmKczznWvSNoHiLTTATZ7xnyxqu51saYk",
        //                 "backUpUsage" => 13321321,
        //                 "cacheUsage" => 1123123,
        //                 "totalUsage" => 12113123,
        //             ],
        //         ],
        //     )
        // );
        // return json($data);
        $data = input('post.');
        $validation = new Validate([
             'nodeTypeArr'  =>  'require',
             'showOnelineOnly'  =>  'require',
            ]
        );
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'err_code' => -900,  'msg' => $validation->getError()]);
        }
        $node_list = array();
        for ($i = 0; $i < count($data['nodeTypeArr']); $i ++) {
            if ($data['nodeTypeArr'][$i] == '超级管理节点') {
                $node_list[] = "S";
            }
            if ($data['nodeTypeArr'][$i] == '超级存储节点') {
                $node_list[] = "SMINER";
            }
            if ($data['nodeTypeArr'][$i] == '矿机节点') {
                $node_list[] = "MINER";
            }
            if ($data['nodeTypeArr'][$i] == '应用层节点') {
                $node_list[] = "A";
            }
        }
        if ($data['showOnelineOnly']) {
            $node_state = 1;
        } else {
            $node_state = 0;
        }

        $param = array(
            "node_list" => $node_list,
            "node_state" => $node_state,
        );
        $return_data = self::loadApiData("bg_manager_tool/query_node_area_distribution", $param);
        $return_data = json_decode($return_data, true);

        if ($return_data['status'] == 0) {
            $total_node_cnt = $return_data['data']['total_node_cnt'];
            $totalNodeCnt = array();
            for ($i=0;$i<count($total_node_cnt);$i++) {
                if ($total_node_cnt[$i]['node_type'] == "S") {
                    $totalNodeCnt[$i]['nodeType'] = '超级管理节点';
                } else if ($total_node_cnt[$i]['node_type'] == "SMiner") {
                    $totalNodeCnt[$i]['nodeType'] = '超级存储节点';
                } else if ($total_node_cnt[$i]['node_type'] == "Miner") {
                    $totalNodeCnt[$i]['nodeType'] = '矿机节点';
                } else {
                    $totalNodeCnt[$i]['nodeType'] = '应用层节点';
                }
                $totalNodeCnt[$i]['nodeCount'] = $total_node_cnt[$i]['node_count'];
            }

            $region_node_dist = $return_data['data']['region_node_dist'];
            $regionNodeDist = array();
            for ($i=0;$i<count($region_node_dist);$i++) {
                if (isset($region_node_dist[$i]['area_name'])) {
                    $regionNodeDist[$i]['位置'] = $region_node_dist[$i]['area_name'];
                }
                if (isset($region_node_dist[$i]['node_s'])) {
                    $regionNodeDist[$i]['超级管理节点'] = $region_node_dist[$i]['node_s'];
                }
                if (isset($region_node_dist[$i]['node_sminer'])) {
                    $regionNodeDist[$i]['超级存储节点'] = $region_node_dist[$i]['node_sminer'];
                }
                if (isset($region_node_dist[$i]['node_app'])) {
                    $regionNodeDist[$i]['应用层节点'] = $region_node_dist[$i]['node_app'];
                }
                if (isset($region_node_dist[$i]['node_miner'])) {
                    $regionNodeDist[$i]['矿机节点'] = $region_node_dist[$i]['node_miner'];
                }
            }

            $all_node_profiles = $return_data['data']['all_node_profiles'];
            $allNodeProfiles = array();
            for ($i=0;$i<count($all_node_profiles);$i++) {
                if ($all_node_profiles[$i]['node_type'] == "S") {
                    $allNodeProfiles[$i]['nodeType'] = '超级管理节点';
                } else if ($all_node_profiles[$i]['node_type'] == "SMiner") {
                    $allNodeProfiles[$i]['nodeType'] = '超级存储节点';
                } else if ($all_node_profiles[$i]['node_type'] == "Miner") {
                    $allNodeProfiles[$i]['nodeType'] = '矿机节点';
                } else {
                    $allNodeProfiles[$i]['nodeType'] = '应用层节点';
                }
                $allNodeProfiles[$i]['nodeId'] = $all_node_profiles[$i]['node_hash'];
                $allNodeProfiles[$i]['backUpUsage'] = $all_node_profiles[$i]['backup_usage'];
                $allNodeProfiles[$i]['cacheUsage'] = $all_node_profiles[$i]['cache_usage'];
                $allNodeProfiles[$i]['totalUsage'] = $all_node_profiles[$i]['total_usage'];
            }

            $data = array(
                "status" => 0,
                "err_code" => $return_data['err_code'],
                "result" => "ok",
                "msg"    => "查询成功",
                "data"   => array(
                    "totalNodeCnt"      => $totalNodeCnt,
                    "regionNodeDist"    => $regionNodeDist,
                    "allNodeProfiles"   => $allNodeProfiles,
                )
            );
            return json($data);
        }
        return json($return_data);
    }

    public function test()
    {
        echo file_get_contents("http://127.0.0.1:7006/add/?path=/www/wwwroot/test.zzb.com/public/download/1.0.0/1.0.2_1.0.0_diff.zip");
        exit;
    }
    



}
