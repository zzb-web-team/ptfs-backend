<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: demon <327414964@qq.com>
// +----------------------------------------------------------------------

use think\facade\Route;
//官网


//后台
Route::rule('admin/upload', 									'admin/upload/index', 								'GET|POST')->allowCrossDomain();
Route::rule('admin/uploadapk', 									'admin/upload/apk', 								'GET|POST')->allowCrossDomain();
Route::rule('admin/uploadapk2', 									'admin/upload/uploadapk', 								'GET|POST')->allowCrossDomain();
Route::rule('admin/uploadzip', 									'admin/upload/zip', 								'GET|POST')->allowCrossDomain();
Route::rule('admin/uploadtest', 									'admin/upload/test', 								'GET|POST')->allowCrossDomain();
Route::rule('admin/uploadipfs', 									'admin/upload/uploadipfs', 								'GET|POST')->allowCrossDomain();
Route::rule('otaversion', 										'admin/rom/otaversion', 							'GET|POST')->allowCrossDomain();
Route::rule('otaversiontest', 										'admin/rom/otaversiontest', 							'GET|POST')->allowCrossDomain();
Route::rule('overview/queryRom', 								'admin/rom/queryRom', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/saveRom', 								'admin/rom/saveRom', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/updateRom', 								'admin/rom/updateRom', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/updateMod', 								'admin/rom/updateMod', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/deleteRom', 								'admin/rom/deleteRom', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/findRomById', 							'admin/rom/findRomById', 							'GET|POST')->allowCrossDomain();
Route::rule('overview/getpacketbyversion',  					'admin/rom/getpacketbyversion', 					'GET|POST')->allowCrossDomain();
Route::rule('overview/getversion', 								'admin/rom/getversion', 							'GET|POST')->allowCrossDomain();
Route::rule('overview/publish', 								'admin/rom/publish', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/publishuser', 								'admin/rom/publishuser', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/publishlist', 							'admin/rom/publishlist', 							'GET|POST')->allowCrossDomain();
Route::rule('overview/uploadpfts', 								'admin/rom/uploadpfts', 							'GET|POST')->allowCrossDomain();
Route::rule('overview/rollback', 								'admin/rom/rollback', 								'GET|POST')->allowCrossDomain();
Route::rule('romupload/complete', 								'admin/rom/complete', 								'GET|POST')->allowCrossDomain();
Route::rule('overview/versionlist', 								'admin/rom/versionlist', 								'GET|POST')->allowCrossDomain();
Route::rule('device/devicelist', 								'admin/device/devicelist', 						    'GET|POST')->allowCrossDomain();
Route::rule('device/savedevice', 								'admin/device/savedevice', 						    'GET|POST')->allowCrossDomain();
Route::rule('device/exceldevice', 								'admin/device/exceldevice', 						    'GET|POST')->allowCrossDomain();
Route::rule('device_manage/device_cnt_overview', 								'admin/device/device_cnt_overview', 						    'GET|POST')->allowCrossDomain();
Route::rule('dev_status/query_detail_info_list', 								'admin/device/query_detail_info_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('dev_status/query_node_info', 								'admin/device/query_node_info', 						    'GET|POST')->allowCrossDomain();
Route::rule('dev_status/query_general_info_list', 								'admin/device/query_general_info_list', 						    'GET|POST')->allowCrossDomain();

Route::rule('packet/queryPacket', 								'admin/packet/queryPacket', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/savePacket', 								'admin/packet/savePacket', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/editDescription', 								'admin/packet/editDescription', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/deletePacket', 								'admin/packet/deletePacket', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/uploadpfts', 								'admin/packet/uploadpfts', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/complete', 								'admin/packet/complete', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/publish', 								'admin/packet/publish', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/publishlist', 								'admin/packet/publishlist', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/versionlist', 								'admin/packet/versionlist', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/getversion', 								'admin/packet/getversion', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/publishuser', 								'admin/packet/publishuser', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/otaversion', 								'admin/packet/otaversion', 								'GET|POST')->allowCrossDomain();
Route::rule('packet/uploadtxt', 									'admin/packet/uploadtxt', 								'GET|POST')->allowCrossDomain();

Route::rule('overview/querySummary', 							'admin/overview/querySummary', 						'GET|POST')->allowCrossDomain();
Route::rule('overview/queryOnlineNodeHistgraph', 				'admin/overview/queryOnlineNodeHistgraph', 			'GET|POST')->allowCrossDomain();
Route::rule('overview/queryDevStoreHistgraph', 					'admin/overview/queryDevStoreHistgraph', 			'GET|POST')->allowCrossDomain();
Route::rule('overview/queryStoreUsageHistgraph', 				'admin/overview/queryStoreUsageHistgraph', 			'GET|POST')->allowCrossDomain();
Route::rule('overview/queryFileCntHistgraph', 					'admin/overview/queryFileCntHistgraph', 			'GET|POST')->allowCrossDomain();
Route::rule('overview/queryAllNodeProfiesByNodeTypes', 			'admin/overview/queryAllNodeProfiesByNodeTypes', 	'GET|POST')->allowCrossDomain();

Route::rule('PTFSNodeManage/queryNodeInfoById', 				'admin/node/queryNodeInfoById', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSNodeManage/queryNodeStoredFileListByNodeId', 	'admin/node/queryNodeStoredFileListByNodeId', 	'GET|POST')->allowCrossDomain();
Route::rule('PTFSNodeManage/queryRegionGroupInfo', 				'admin/node/queryRegionGroupInfo', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSNodeManage/queryGroupInfo', 					'admin/node/queryGroupInfo', 					'GET|POST')->allowCrossDomain();
Route::rule('PTFSNodeManage/queryAllConfigRecords', 			'admin/node/queryAllConfigRecords', 			'GET|POST')->allowCrossDomain();

Route::rule('PTFSFileManage/queryFileSummaryByConditions', 		'admin/file/queryFileSummaryByConditions', 		'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/queryOneFileBackupOwnerRecords', 	'admin/file/queryOneFileBackupOwnerRecords', 	'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/queryBlackListByCondition', 		'admin/file/queryBlackListByCondition', 		'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/updateNodeConfig', 					'admin/file/updateNodeConfig', 					'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/addNewForbiddenFile', 				'admin/file/addNewForbiddenFile', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/uploadBlackListFile', 				'admin/file/uploadBlackListFile', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSFileManage/deleteFileList', 					'admin/file/deleteFileList', 					'GET|POST')->allowCrossDomain();

Route::rule('PTFSLogSys/queryLogTypes', 						'admin/log/queryLogTypes', 						'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/showNodeNetworkLogs', 					'admin/log/showNodeNetworkLogs', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/deleteHistoryDatas', 					'admin/log/deleteHistoryDatas', 				'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/actionlog', 							'admin/log/actionlogList', 							'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/applogList', 							'admin/log/applogList', 							'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/setactionlog', 							'admin/log/setactionlog', 							'GET|POST')->allowCrossDomain();
Route::rule('PTFSLogSys/actionlog2', 							'admin/log/actionlogList2', 							'GET|POST')->allowCrossDomain();

Route::rule('bg_manager_tool/query_device_region_distribution', 		'admin/bgmanager/query_device_region_distribution', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_resource_export_rank', 				'admin/bgmanager/query_resource_export_rank', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_stream_export_rank', 				'admin/bgmanager/query_stream_export_rank', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_device_details', 					'admin/bgmanager/query_device_details', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_3rd_stream_overview', 				'admin/bgmanager/query_3rd_stream_overview', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_3rd_capacity_stream_overview', 		'admin/bgmanager/query_3rd_capacity_stream_overview', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_3rd_capacity_stream_details', 		'admin/bgmanager/query_3rd_capacity_stream_details', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/send_ptfs_cmd', 							'admin/bgmanager/send_ptfs_cmd', 	'GET|POST')->allowCrossDomain();
Route::rule('bg_manager_tool/query_ptfs_cmd_result', 					'admin/bgmanager/query_ptfs_cmd_result', 	'GET|POST')->allowCrossDomain();

Route::rule('file_bgmgr/city_heat', 		'admin/filebgmgr/city_heat', 	'GET|POST')->allowCrossDomain();
Route::rule('file_bgmgr/file_dl_range', 	'admin/filebgmgr/file_dl_range', 	'GET|POST')->allowCrossDomain();
Route::rule('file_bgmgr/time_heat', 		'admin/filebgmgr/time_heat', 	'GET|POST')->allowCrossDomain();
Route::rule('file_bgmgr/file_outline', 		'admin/filebgmgr/file_outline', 	'GET|POST')->allowCrossDomain();
Route::rule('file_bgmgr/file_store', 		'admin/filebgmgr/file_store', 	'GET|POST')->allowCrossDomain();
Route::rule('file_bgmgr/file_dlinfo', 		'admin/filebgmgr/file_dlinfo', 	'GET|POST')->allowCrossDomain();

Route::rule('domain/query_domain_list', 								'admin/domain/query_domain_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('domain/modify_domain_list', 								'admin/domain/modify_domain_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('domain/gen_token', 								'admin/domain/gen_token', 						    'GET|POST')->allowCrossDomain();
Route::rule('domain/domain_list', 								'admin/domain/domain_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('domain/report_smng_list', 								'admin/domain/report_smng_list', 						    'GET|POST')->allowCrossDomain();

Route::rule('device_report/verify', 								'admin/devicereport/verify', 						    'GET|POST')->allowCrossDomain();
Route::rule('device_report/report_data', 							'admin/devicereport/report_data', 						    'GET|POST')->allowCrossDomain();

Route::rule('filecache/query_node_info_list', 								'admin/filecache/query_node_info_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('filecache/query_node_profit_list', 							'admin/filecache/query_node_profit_list', 						    'GET|POST')->allowCrossDomain();
Route::rule('filecache/query_total_node_info', 								'admin/filecache/query_total_node_info', 						    'GET|POST')->allowCrossDomain();


Route::rule('account/ptfs_forbid_users', 		'admin/account/ptfs_forbid_users', 	'GET|POST')->allowCrossDomain();
Route::rule('account/ptfs_query_total_users', 		'admin/account/ptfs_query_total_users', 	'GET|POST')->allowCrossDomain();
Route::rule('account/ptfs_query_user_list', 		'admin/account/ptfs_query_user_list', 	'GET|POST')->allowCrossDomain();
Route::post('account/ptfs_query_user_trend_list', 		'admin/account/ptfs_query_user_trend_list')->allowCrossDomain();
Route::post('account/query_daily_sign', 		'admin/account/query_daily_sign')->allowCrossDomain();

Route::post('account/sign', 					'api/minerearn/sign')->allowCrossDomain();

//app
Route::post('app/app_add', 			'admin/app/app_add')->allowCrossDomain();
Route::post('app/add_version', 		'admin/app/add_version')->allowCrossDomain();
Route::post('app/get_app_count', 	'admin/app/get_app_count')->allowCrossDomain();
Route::post('app/get_ver_count', 	'admin/app/get_ver_count')->allowCrossDomain();
Route::post('app/get_app', 			'admin/app/get_app')->allowCrossDomain();
Route::post('app/get_ver', 			'admin/app/get_ver')->allowCrossDomain();
Route::post('app/add_app', 			'admin/app/add_app')->allowCrossDomain();
Route::post('app/app_update', 		'admin/app/app_update')->allowCrossDomain();
Route::post('app/ver_update', 		'admin/app/ver_update')->allowCrossDomain();
Route::rule('app/applist', 			'admin/app/applist', 'GET|POST')->allowCrossDomain();
Route::rule('app/saveapp', 			'admin/app/saveapp', 'GET|POST')->allowCrossDomain();
Route::rule('app/devicelist', 		'admin/app/devicelist', 	'GET|POST')->allowCrossDomain();
Route::rule('app/devicectrl', 		'admin/app/devicectrl', 	'GET|POST')->allowCrossDomain();
Route::rule('app/applistfortree', 	'admin/app/applistfortree', 	'GET|POST')->allowCrossDomain();
Route::rule('app/verlistfortree', 	'admin/app/verlistfortree', 	'GET|POST')->allowCrossDomain();
Route::rule('app/getappbydev', 		'admin/app/getappbydev', 	'GET|POST')->allowCrossDomain();
Route::rule('app/getmactype', 		'admin/app/getmactype', 	'GET|POST')->allowCrossDomain();
Route::rule('app/getappstatistics', 	'admin/app/getappstatistics', 	'GET|POST')->allowCrossDomain();
Route::rule('app/otaversion', 										'admin/app/otaversion', 							'GET|POST')->allowCrossDomain();
Route::rule('app/savedist', 										'admin/app/savedist', 							'GET|POST')->allowCrossDomain();
Route::rule('app/publishdist', 										'admin/app/publishdist', 							'GET|POST')->allowCrossDomain();

Route::rule('ptfs/report', 										'admin/report/api', 							'GET|POST')->allowCrossDomain();

Route::rule('admin/system/login', 										'admin/system/login', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/userctrl', 										'admin/system/userctrl', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/userinsert', 										'admin/system/userinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/userdelete', 										'admin/system/userdelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/userlist', 										'admin/system/userlist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/userupdate', 										'admin/system/userupdate', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menuinsert', 										'admin/system/menuinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menudelete', 										'admin/system/menudelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menulist', 										'admin/system/menulist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menuupdate', 										'admin/system/menuupdate', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menulistfortree', 										'admin/system/menulistfortree', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/menulistfortop', 										'admin/system/menulistfortop', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/roleinsert', 										'admin/system/roleinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/roleupdate', 										'admin/system/roleedit', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/roledelete', 										'admin/system/roledelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/rolelist', 										'admin/system/rolelist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/getrolebyid', 										'admin/system/getrolebyid', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/system/rolelistfortop', 										'admin/system/rolelistfortop', 							'GET|POST')->allowCrossDomain();

Route::rule('admin/ipfssystem/login', 										'admin/ipfssystem/login', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/userctrl', 										'admin/ipfssystem/userctrl', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/userinsert', 										'admin/ipfssystem/userinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/userdelete', 										'admin/ipfssystem/userdelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/userlist', 										'admin/ipfssystem/userlist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/userupdate', 										'admin/ipfssystem/userupdate', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menuinsert', 										'admin/ipfssystem/menuinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menudelete', 										'admin/ipfssystem/menudelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menulist', 										'admin/ipfssystem/menulist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menuupdate', 										'admin/ipfssystem/menuupdate', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menulistfortree', 										'admin/ipfssystem/menulistfortree', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/menulistfortop', 										'admin/ipfssystem/menulistfortop', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/roleinsert', 										'admin/ipfssystem/roleinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/roleupdate', 										'admin/ipfssystem/roleedit', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/roledelete', 										'admin/ipfssystem/roledelete', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/rolelist', 										'admin/ipfssystem/rolelist', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/getrolebyid', 										'admin/ipfssystem/getrolebyid', 							'GET|POST')->allowCrossDomain();
Route::rule('admin/ipfssystem/rolelistfortop', 										'admin/ipfssystem/rolelistfortop', 							'GET|POST')->allowCrossDomain();
//ptfs接口
Route::rule('ptfs_user_server/get_code', 						'api/ptfsstorageuser/getcode' ,  'GET|POST')->allowCrossDomain();
Route::post('ptfs_user_server/login', 							'api/ptfsstorageuser/login')->allowCrossDomain();
Route::post('ptfs_user_server/logout', 							'api/ptfsstorageuser/logout')->allowCrossDomain();
Route::post('ptfs_user_server/get_user_info', 					'api/ptfsstorageuser/getuserinfo')->allowCrossDomain();
Route::post('ptfs_user_server/update_user_info', 				'api/ptfsstorageuser/updateuserinfo')->allowCrossDomain();
Route::post('ptfs_user_server/change_user_telnum', 				'api/ptfsstorageuser/changeusertelnum')->allowCrossDomain();
Route::post('ptfs_user_server/set_user_charge_psd', 			'api/ptfsstorageuser/setuserchargepsd')->allowCrossDomain();
Route::post('ptfs_user_server/get_user_charge_status', 			'api/ptfsstorageuser/getuserchargestatus')->allowCrossDomain();

Route::post('miner_ctrl/import_node_basicinfo', 				'api/ptfsstorage/importnodebasicinfo')->allowCrossDomain();
Route::post('miner_ctrl/query_node_basicinfo', 					'api/ptfsstorage/querynodebasicinfo')->allowCrossDomain();
Route::post('miner_ctrl/edit_node_basicinfo', 					'api/ptfsstorage/editnodebasicinfo')->allowCrossDomain();
Route::post('miner_ctrl/change_device_bind_state', 				'api/ptfsstorage/changedevicebindstate')->allowCrossDomain();
Route::post('miner_ctrl/query_device_bind_state', 				'api/ptfsstorage/querydevicebindstate')->allowCrossDomain();
Route::post('miner_ctrl/query_device_id_by_sn', 				'api/ptfsstorage/querydeviceidbysn')->allowCrossDomain();
Route::post('miner_ctrl/query_bind_devinfo_list_by_user_id', 	'api/ptfsstorage/querybinddevinfolistbyuserid')->allowCrossDomain();
Route::post('miner_ctrl/bind_dev_update_dev_name', 				'api/ptfsstorage/binddevupdatedevname')->allowCrossDomain();
Route::post('miner_ctrl/query_bind_devs_online_state', 			'api/ptfsstorage/querybinddevsonlinestate')->allowCrossDomain();
Route::post('miner_ctrl/ctrl_node_state', 						'api/ptfsstorage/ctrlnodestate')->allowCrossDomain();
Route::post('miner_ctrl/query_online_histgraph', 				'api/ptfsstorage/queryonlinehistgraph')->allowCrossDomain();
Route::post('miner_ctrl/query_dev_name_chg_log', 				'api/ptfsstorage/querydevnamechglog')->allowCrossDomain();
Route::post('miner_ctrl/query_dev_phy_cap_hisgraph', 			'api/ptfsstorage/querydevphycaphisgraph')->allowCrossDomain();
Route::post('miner_ctrl/query_devinfo_by_conditions', 			'api/ptfsstorage/query_devinfo_by_conditions')->allowCrossDomain();
Route::post('miner_ctrl/query_miscell_devinfo', 				'api/ptfsstorage/query_miscell_devinfo')->allowCrossDomain();
Route::post('miner_ctrl/query_devinfo_by_conditions_grapefruit', 			'api/ptfsstorage/query_devinfo_by_conditions_grapefruit')->allowCrossDomain();
Route::post('miner_ctrl/edit_device_basicinfo', 				'api/ptfsstorage/edit_device_basicinfo')->allowCrossDomain();
Route::post('miner_ctrl/device_cnt_overview', 					'api/ptfsstorage/device_cnt_overview')->allowCrossDomain();
Route::post('miner_ctrl/delete_device_basicinfo', 				'api/ptfsstorage/delete_device_basicinfo')->allowCrossDomain();
Route::post('miner_ctrl/change_device_bind_state2', 				'api/ptfsstorage/changedevicebindstate2')->allowCrossDomain();
Route::post('miner_ctrl/ctrl_node_state2', 						'api/ptfsstorage/ctrlnodestate2')->allowCrossDomain();
Route::post('miner_ctrl/query_binded_user_cnt', 						'api/ptfsstorage/query_binded_user_cnt')->allowCrossDomain();
Route::rule('miner_ctrl/chg_device_state', 						'api/ptfsstorage/chg_device_state', "GET|POST")->allowCrossDomain();

Route::post('miner_ctrl/query_node_dynamic_info', 					'api/minerearn/query_node_dynamic_info')->allowCrossDomain();
Route::post('miner_ctrl/create_help_cat_info', 					'api/minerearn/create_help_cat_info')->allowCrossDomain();
Route::post('miner_ctrl/create_help_item_info', 					'api/minerearn/create_help_item_info')->allowCrossDomain();
Route::post('miner_ctrl/modify_help_cat_info', 					'api/minerearn/modify_help_cat_info')->allowCrossDomain();
Route::post('miner_ctrl/modify_help_item_info', 					'api/minerearn/modify_help_item_info')->allowCrossDomain();
Route::post('miner_ctrl/delete_help_cat_info', 					'api/minerearn/delete_help_cat_info')->allowCrossDomain();
Route::post('miner_ctrl/delete_help_item_info', 					'api/minerearn/delete_help_item_info')->allowCrossDomain();
Route::post('miner_ctrl/query_help_cat_info', 					'api/minerearn/query_help_cat_info')->allowCrossDomain();
Route::post('miner_ctrl/query_help_item_info', 					'api/minerearn/query_help_item_info')->allowCrossDomain();
Route::post('miner_ctrl/app_query_help_cat_info', 					'api/minerearn/app_query_help_cat_info')->allowCrossDomain();
Route::post('miner_ctrl/app_query_help_item_info', 					'api/minerearn/app_query_help_item_info')->allowCrossDomain();
Route::post('miner_ctrl/query_node_address_info', 					'api/minerearn/query_node_address_info')->allowCrossDomain();
Route::post('miner_ctrl/web_change_device_state', 					'api/minerearn/web_change_device_state')->allowCrossDomain();
Route::post('miner_ctrl/move_help_item', 					'api/minerearn/move_help_item')->allowCrossDomain();
Route::post('miner_ctrl/batch_import_devices', 					'api/minerearn/batch_import_devices')->allowCrossDomain();

Route::post('miner_earn/ptfs_total_profit_info', 					'admin/minerearn/ptfs_total_profit_info')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_user_store_list', 					'admin/minerearn/ptfs_query_user_store_list')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_user_profit_info', 					'admin/minerearn/ptfs_query_user_profit_info')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_earn_param', 					'admin/minerearn/ptfs_set_earn_param')->allowCrossDomain();
Route::post('miner_earn/ptfs_forbiden_devices', 					'admin/minerearn/ptfs_forbiden_devices')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_user_total_profit_everyday', 					'admin/minerearn/ptfs_query_user_total_profit_everyday')->allowCrossDomain();
Route::post('miner_earn/query_node_info_list', 					'api/minerearn/querynodeinfolist')->allowCrossDomain();
Route::post('miner_earn/query_node_profit_list', 				'api/minerearn/querynodeprofitlist')->allowCrossDomain();
Route::post('miner_earn/query_profit_rank', 					'api/minerearn/queryprofitrank')->allowCrossDomain();
Route::post('miner_earn/query_user_node_info_list', 			'api/minerearn/queryusernodeinfolist')->allowCrossDomain();
Route::post('miner_earn/query_user_node_profit_list', 			'api/minerearn/queryusernodeprofitlist')->allowCrossDomain();
Route::post('miner_earn/query_user_node_exchange_list', 		'api/minerearn/queryusernodeexchangelist')->allowCrossDomain();
Route::post('miner_earn/query_user_profit_list', 				'api/minerearn/queryuserprofitlist')->allowCrossDomain();
Route::post('miner_earn/savequestion', 							'api/minerearn/savequestion')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_list_user_store_list', 		'api/minerearn/ptfs_query_list_user_store_list')->allowCrossDomain();
Route::post('miner_earn/turnon', 					'api/minerearn/turnon')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_node_grade', 					'api/minerearn/ptfs_query_node_grade')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_con_value_list', 					'api/minerearn/ptfs_query_con_value_list')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_cp_value_list', 					'api/minerearn/ptfs_query_cp_value_list')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_con_param_add', 					'api/minerearn/ptfs_set_con_param_add')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_con_param_dec', 					'api/minerearn/ptfs_set_con_param_dec')->allowCrossDomain();
Route::post('miner_earn/ptfs_get_con_param_add', 					'api/minerearn/ptfs_get_con_param_add')->allowCrossDomain();
Route::post('miner_earn/ptfs_get_con_param_dec', 					'api/minerearn/ptfs_get_con_param_dec')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_node_info_list', 					'api/minerearn/ptfs_query_node_info_list')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_com_power_scale', 					'api/minerearn/ptfs_set_com_power_scale')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_com_power_add', 					'api/minerearn/ptfs_set_com_power_add')->allowCrossDomain();
Route::post('miner_earn/ptfs_set_com_power_dec', 					'api/minerearn/ptfs_set_com_power_dec')->allowCrossDomain();
Route::post('miner_earn/ptfs_get_com_power_scale', 					'api/minerearn/ptfs_get_com_power_scale')->allowCrossDomain();
Route::post('miner_earn/ptfs_get_com_power_add', 					'api/minerearn/ptfs_get_com_power_add')->allowCrossDomain();
Route::post('miner_earn/ptfs_get_com_power_dec', 					'api/minerearn/ptfs_get_com_power_dec')->allowCrossDomain();
Route::post('miner_earn/get_user_average_cp', 					'api/minerearn/get_user_average_cp')->allowCrossDomain();
Route::post('miner_earn/get_dev_cap_list', 					'api/minerearn/get_dev_cap_list')->allowCrossDomain();
Route::post('miner_earn/get_dev_bandwidth_list', 					'api/minerearn/get_dev_bandwidth_list')->allowCrossDomain();
Route::post('miner_earn/ptfs_query_user_profit_list', 					'api/minerearn/ptfs_query_user_profit_list')->allowCrossDomain();
Route::post('miner_earn/query_node_total_profit_info', 					'api/minerearn/query_node_total_profit_info')->allowCrossDomain();
Route::post('miner_earn/get_app_dev_con_val', 					'api/minerearn/get_app_dev_con_val')->allowCrossDomain();
Route::post('miner_earn/get_app_dev_con_list', 					'api/minerearn/get_app_dev_con_list')->allowCrossDomain();
Route::post('miner_earn/get_app_dev_cp_val', 					'api/minerearn/get_app_dev_cp_val')->allowCrossDomain();
Route::post('miner_earn/get_app_dev_cp_list', 					'api/minerearn/get_app_dev_cp_list')->allowCrossDomain();

Route::post('monitor/get_server', 								'api/monitor/getserver')->allowCrossDomain();
Route::post('monitor/get_cur_process_info', 					'api/monitor/getcurprocessinfo')->allowCrossDomain();
Route::post('monitor/get_cur_machine_info', 					'api/monitor/getcurmachineinfo')->allowCrossDomain();
Route::post('monitor/get_all_process_info', 					'api/monitor/getallprocessinfo')->allowCrossDomain();
Route::post('monitor/get_all_machine_info', 					'api/monitor/getallmachineinfo')->allowCrossDomain();
Route::post('monitor/get_machine', 								'api/monitor/getmachine')->allowCrossDomain();
Route::post('monitor/get_cur_machine_info2', 					'api/monitor/getcurmachineinfo2')->allowCrossDomain();
Route::post('monitor/get_all_machine_info2', 					'api/monitor/getallmachineinfo2')->allowCrossDomain();

//oauth
Route::get('oauth/index', 			'api/oauth/index')->allowCrossDomain();
Route::get('oauth/callback', 		'api/oauth/callback')->allowCrossDomain();
Route::rule('oauth/test', 			'api/oauth/test', 	'GET|POST')->allowCrossDomain();
Route::rule('oauth/check', 			'api/oauth/check', 	'GET|POST')->allowCrossDomain();
Route::rule('oauth/pay', 			'api/oauth/pay', 	'GET|POST')->allowCrossDomain();
Route::rule('oauth/order', 	'api/oauth/order', 	'GET|POST')->allowCrossDomain();
Route::rule('oauth/gettoken', 		'api/oauth/gettoken', 	'GET|POST')->allowCrossDomain();
Route::rule('oauth/orderlist', 		'api/oauth/orderlist', 	'GET|POST')->allowCrossDomain();

//Appmarket
Route::rule('appmarket/verify', 		'admin/appmarket/verify', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/add_app', 		'admin/appmarket/add_app', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/update_app', 		'admin/appmarket/update_app', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/get_all_app', 		'admin/appmarket/get_all_app', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/get_app', 		'admin/appmarket/get_app', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/download', 		'admin/appmarket/download', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/get_recommend', 		'admin/appmarket/get_recommend', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/app_on', 		'admin/appmarket/app_on', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/app_off', 		'admin/appmarket/app_off', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/add_app_dlcount', 		'admin/appmarket/add_app_dlcount', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/add_group', 		'admin/appmarket/add_group', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/update_group', 		'admin/appmarket/update_group', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/del_group', 		'admin/appmarket/del_group', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/query_all_group', 		'admin/appmarket/query_all_group', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/query_group', 		'admin/appmarket/query_group', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/get_app_by_appid', 		'admin/appmarket/get_app_by_appid', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/del_app', 		'admin/appmarket/del_app', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket/get_apptype',     'admin/appmarket/get_apptype',   'GET|POST')->allowCrossDomain();
Route::rule('appmarket_client/get_appcover',     'admin/appmarket/get_appcover',    'GET|POST')->allowCrossDomain(); 
Route::rule('appmarket_client/getapp_by_id',     'admin/appmarket/getapp_by_id',    'GET|POST')->allowCrossDomain();
Route::rule('appmarket_client/getapp_by_name',     'admin/appmarket/getapp_by_name',    'GET|POST')->allowCrossDomain(); 
Route::rule('appmarket_client/check_appversion',     'admin/appmarket/check_appversion',    'GET|POST')->allowCrossDomain(); 
Route::rule('appmarket_client/getapp_by_pkgname',     'admin/appmarket/getapp_by_pkgname',    'GET|POST')->allowCrossDomain(); 
Route::rule('appmarket_client/get_recommend', 		'admin/appmarket/client_get_recommend', 	'GET|POST')->allowCrossDomain();
Route::rule('appmarket_client/get_apptype_forclient',     'admin/appmarket/get_apptype_forclient',    'GET|POST')->allowCrossDomain(); 


Route::rule('noticepush/get_tag', 		'api/noticepush/get_tag', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/push_immediate', 		'api/noticepush/push_immediate', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/push_on_timer', 		'api/noticepush/push_on_timer', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/delete_push_on_timer', 		'api/noticepush/delete_push_on_timer', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/query_push_history_list', 		'api/noticepush/query_push_history_list', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/query_push_history_listex', 		'api/noticepush/query_push_history_listex', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/delete_push_list', 		'api/noticepush/delete_push_list', 	'GET|POST')->allowCrossDomain();
Route::rule('noticepush/getnotice', 		'api/noticepush/getnotice', 	'GET|POST')->allowCrossDomain();

Route::rule('cloud/saveimage', 		'cloud/filehtml/upload', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/saveimagemore', 		'cloud/filehtml/uploadmore', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/savehtml', 		'cloud/filehtml/html', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/uploadsdk', 		'cloud/filehtml/uploadsdk', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/savesdk', 		'cloud/filehtml/savesdk', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/sdklist', 		'cloud/filehtml/sdklist', 	'GET|POST')->allowCrossDomain();
Route::rule('cloud/editsdk', 		'cloud/filehtml/editsdk', 	'GET|POST')->allowCrossDomain();
Route::post('cloud/back_data',      'cloud/filehtml/back_data')->allowCrossDomain();

Route::rule('url_mgmt/add_url', 		'cloud/urlmgmt/add_url', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/excel_url', 		'cloud/urlmgmt/excelurl', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/check_label', 		'cloud/urlmgmt/check_label', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/modify_label', 		'cloud/urlmgmt/modify_label', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/config_url', 		'cloud/urlmgmt/config_url', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/change_state', 		'cloud/urlmgmt/change_state', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/query_url', 		'cloud/urlmgmt/query_url', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/query_config', 		'cloud/urlmgmt/query_config', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/delete_url', 		'cloud/urlmgmt/delete_url', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/query_urllabel', 		'cloud/urlmgmt/query_urllabel', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/excelurluser', 		'cloud/urlmgmt/excelurluser', 	'GET|POST')->allowCrossDomain();
Route::rule('url_mgmt/getvideo', 		'cloud/urlmgmt/getvideo', 	'GET|POST')->allowCrossDomain();
Route::post('url_mgmt/check_urlname',     'cloud/urlmgmt/check_urlname')->allowCrossDomain();
Route::post('url_mgmt/add_domain',      'cloud/urlmgmt/add_domain')->allowCrossDomain();
Route::post('url_mgmt/modify_domain',      'cloud/urlmgmt/modify_domain')->allowCrossDomain();
Route::post('url_mgmt/change_domainstate',    'cloud/urlmgmt/change_domainstate')->allowCrossDomain();
Route::post('url_mgmt/del_domain',           'cloud/urlmgmt/del_domain')->allowCrossDomain();
Route::post('url_mgmt/query_domain',       'cloud/urlmgmt/query_domain')->allowCrossDomain();
Route::post('url_mgmt/query_url_for_admin',       'cloud/urlmgmt/query_url_for_admin')->allowCrossDomain();
Route::post('url_mgmt/query_domain_for_admin',       'cloud/urlmgmt/query_domain_for_admin')->allowCrossDomain();
Route::post('url_mgmt/url_export',       'cloud/urlmgmt/url_export')->allowCrossDomain();
Route::post('url_mgmt/url_export_for_amdin',       'cloud/urlmgmt/url_export_for_amdin')->allowCrossDomain();


Route::rule('node_mgmt/node_distribute', 		'cloud/nodemgmt/node_distribute', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/query_node', 		'cloud/nodemgmt/query_node', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/query_resource', 		'cloud/nodemgmt/query_resource', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/query_rootnode', 		'cloud/nodemgmt/query_rootnode', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/resource_refresh', 		'cloud/nodemgmt/resource_refresh', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/query_relaysrv', 		'cloud/nodemgmt/query_relaysrv', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/refresh_state', 		'cloud/nodemgmt/refresh_state', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/uploadpfts', 		'cloud/nodemgmt/uploadpfts', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/query_nodefilter', 		'cloud/nodemgmt/query_nodefilter', 	'GET|POST')->allowCrossDomain();
Route::rule('node_mgmt/filter_node', 		'cloud/nodemgmt/filter_node', 	'GET|POST')->allowCrossDomain();

Route::rule('videoplay_accelerate/query_accelerate_log', 		'cloud/videoplay/query_accelerate_log', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_accelerate/query_videoplay_log', 		'cloud/videoplay/query_videoplay_log', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_accelerate/export_videoaccel_file', 		'cloud/videoplay/export_videoaccel_file', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_accelerate/export_videoplay_file', 		'cloud/videoplay/export_videoplay_file', 	'GET|POST')->allowCrossDomain();

Route::rule('logfile_download/query_logfile_table', 		'cloud/videoplay/query_logfile_table', 	'GET|POST')->allowCrossDomain();
Route::rule('logfile_download/download_logfile', 		'cloud/videoplay/download_logfile', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_manage/accelerate_flow_query_conditions', 		'cloud/resource/accelerate_flow_query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_manage/accelerate_flow', 		'cloud/resource/accelerate_flow', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_manage/accelerate_flow_table', 		'cloud/resource/accelerate_flow_table', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_manage/backsource_flow_query_conditions', 		'cloud/resource/backsource_flow_query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_manage/backsource_flow', 		'cloud/resource/backsource_flow', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/pv_uv_query_conditions', 		'cloud/resource/pv_uv_query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/pv_uv_curve', 		'cloud/resource/pv_uv_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/region_query_conditions', 		'cloud/resource/region_query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/query_topregion_accesscnt_curve', 		'cloud/resource/query_topregion_accesscnt_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/isp_query_conditions', 		'cloud/resource/isp_query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('videoaccess_statistic/query_topisp_accesscnt_curve', 		'cloud/resource/query_topisp_accesscnt_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_statistic/query_playtimes_conditions', 		'cloud/resource/query_playtimes_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_statistic/query_playtimes_curve', 		'cloud/resource/query_playtimes_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('videoplay_statistic/query_playdata_table', 		'cloud/resource/query_playdata_table', 	'GET|POST')->allowCrossDomain();

Route::rule('resource_usage/query_conditions', 		'cloud/resourceuser/query_conditions', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_usage/dataflow_curve', 		'cloud/resourceuser/dataflow_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('resource_usage/dataflow_table', 		'cloud/resourceuser/dataflow_table', 	'GET|POST')->allowCrossDomain();

Route::rule('grapefruit_analyse/app_usage_region_dist', 		'cloud/videoplay/app_usage_region_dist', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/app_version_dist_curve', 		'cloud/videoplay/app_version_dist_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/app_version_dist_table', 		'cloud/videoplay/app_version_dist_table', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_online_curve', 		'cloud/videoplay/device_online_curve', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_online_table', 		'cloud/videoplay/device_online_table', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/app_version_online_dist', 		'cloud/videoplay/app_version_online_dist', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_version', 		'cloud/videoplay/device_version', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_version_day', 		'cloud/videoplay/device_version_day', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_offline', 		'cloud/videoplay/device_offline', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_online', 		'cloud/videoplay/device_online', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_rom', 		'cloud/videoplay/device_rom', 	'GET|POST')->allowCrossDomain();
Route::rule('grapefruit_analyse/device_type', 		'cloud/videoplay/device_type', 	'GET|POST')->allowCrossDomain();


Route::rule('clouduser/getcode', 		'cloud/user/getcode', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/getemail', 		'cloud/user/getemail', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/register', 		'cloud/user/register', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/login', 		'cloud/user/login', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/editusername', 		'cloud/user/editusername', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/editemail', 		'cloud/user/editemail', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/editphone', 		'cloud/user/editphone', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/userlist', 		'cloud/user/userlist', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/denyuser', 		'cloud/user/denyuser', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/getuser', 		'cloud/user/getuser', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/forgetpassword', 		'cloud/user/forgetpassword', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/resetpassword', 		'cloud/user/resetpassword', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/loginbyphone', 		'cloud/user/loginbyphone', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/checktoken', 		'cloud/user/checktoken', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/checkuser', 		'cloud/user/checkuser', 	'GET|POST')->allowCrossDomain();
Route::rule('clouduser/resecret', 		'cloud/user/resecret', 	'GET|POST')->allowCrossDomain();

Route::rule('cloudterminal/addterminal', 		'cloud/terminal/addterminal', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudterminal/getterminal', 		'cloud/terminal/getterminal', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudterminal/editterminal', 		'cloud/terminal/editterminal', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudterminal/deleteterminal', 		'cloud/terminal/deleteterminal', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudterminal/actionlog', 		'cloud/terminal/actionlogList', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudterminal/setactionlog', 		'cloud/terminal/setactionlog', 	'GET|POST')->allowCrossDomain();

Route::rule('cloud/system/login', 										'cloud/system/login', 							'GET|POST')->allowCrossDomain();
Route::rule('cloud/system/userctrl', 										'cloud/system/userctrl', 							'GET|POST')->allowCrossDomain();
Route::rule('cloud/system/userinsert', 										'cloud/system/userinsert', 							'GET|POST')->allowCrossDomain();
Route::rule('cloud/system/userdelete', 										'cloud/system/userdelete', 							'GET|POST')->allowCrossDomain();
Route::rule('cloud/system/userlist', 										'cloud/system/userlist', 							'GET|POST')->allowCrossDomain();
Route::rule('cloud/system/userupdate', 										'cloud/system/userupdate', 							'GET|POST')->allowCrossDomain();


Route::rule('ipfs_node_ip_data/ipfs_dataflow_query_conditions' , 'cloud/ipfs/ipfs_dataflow_query_conditions', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ip_usage_table' , 'cloud/ipfs/query_ip_usage_table', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ipfs_node_region_dist' , 'cloud/ipfs/query_ipfs_node_region_dist', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ipfs_dataflow_curve' , 'cloud/ipfs/query_ipfs_dataflow_curve', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ipfs_dataflow_table' , 'cloud/ipfs/query_ipfs_dataflow_table', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ipfs_dataflow_avg_usage_curve' , 'cloud/ipfs/query_ipfs_dataflow_avg_usage_curve', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_data/query_ipfs_dataflow_avg_usage_table' , 'cloud/ipfs/query_ipfs_dataflow_avg_usage_table', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_store/query_ip_store_usage_table' , 'cloud/ipfs/query_ip_store_usage_table', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_store/query_ip_store_details_curve' , 'cloud/ipfs/query_ip_store_details_curve', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_store/query_ip_store_details_table' , 'cloud/ipfs/query_ip_store_details_table', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_store/query_ip_store_avg_usage_curve' , 'cloud/ipfs/query_ip_store_avg_usage_curve', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_ip_store/query_ip_store_avg_usage_table' , 'cloud/ipfs/query_ip_store_avg_usage_table', 'GET|POST')->allowCrossDomain();
Route::rule('channel_details/query_total_dataflow' , 'cloud/ipfs/query_total_dataflow', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_monit/ipfs_region_summary' , 'cloud/ipfs/ipfs_region_summary', 'GET|POST')->allowCrossDomain();
Route::rule('ipfs_node_monit/ipfs_basic_info' , 'cloud/ipfs/ipfs_basic_info', 'GET|POST')->allowCrossDomain();

Route::rule('ipfs_node_monit/query_ipfs_version_download' , 'cloud/ipfs/query_ipfs_version_download', 'GET|POST')->allowCrossDomain();


Route::rule('demo/list' , 'demo/index/list', 'GET|POST')->allowCrossDomain();
Route::rule('demo/detail' , 'demo/index/detail', 'GET|POST')->allowCrossDomain();
Route::rule('demo/add' , 'demo/index/add', 'GET|POST')->allowCrossDomain();
Route::rule('demo/edit' , 'demo/index/edit', 'GET|POST')->allowCrossDomain();
Route::rule('demo/delete' , 'demo/index/delete', 'GET|POST')->allowCrossDomain();

Route::rule('cloudapi/get_token', 		'api/urlmgmt/get_token_for_rest', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudapi/add_url', 		'api/urlmgmt/add_url_for_rest', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudapi/config', 		'api/urlmgmt/config_url_for_rest', 	'GET|POST')->allowCrossDomain();

Route::rule('cloudapi/iplist', 		'admin/system/iplist', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudapi/addip', 		'admin/system/addip', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudapi/editip', 		'admin/system/editip', 	'GET|POST')->allowCrossDomain();
Route::rule('cloudapi/deleteip', 		'admin/system/deleteip', 	'GET|POST')->allowCrossDomain();
return [

];
