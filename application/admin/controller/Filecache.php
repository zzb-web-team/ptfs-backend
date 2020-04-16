<?php
namespace app\admin\controller;
use think\Controller;
use think\Validate;

class Filecache extends Controller
{

    private function loadApiData($method, $data, $signature = null)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, config("ipfs.apiurl2"));
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data['method'] = $method;
        $post_data['data'] = $data;
        if ( $signature ) {
            $post_data['signature'] = $signature;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
        //执行命令
        $response = curl_exec($curl);
        //显示获得的数据
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        } else {
            $body = $response;
        }
        //关闭URL请求
        curl_close($curl);
        return $body;
    }

    //查询设备地域分布 
    public function query_node_info_list()
    {
        $data = input('post.');
        $signature = isset($data['signature']) ? $data['signature'] : null;
        if (isset($data['signature'])){
            unset($data['signature']);
        }
        return $return_data = $this->loadApiData("filecache/query_node_info_list", $data, $signature);
    }

    //查询设备地域分布 
    public function query_node_profit_list()
    {
        $data = input('post.');
        $signature = isset($data['signature']) ? $data['signature'] : null;
        if (isset($data['signature'])){
            unset($data['signature']);
        }
        return $return_data = $this->loadApiData("filecache/query_node_profit_list", $data, $signature);
    }

    public function query_total_node_info()
    {
        $data = input('post.');
        $signature = isset($data['signature']) ? $data['signature'] : null;
        if (isset($data['signature'])){
            unset($data['signature']);
        }
        return $return_data = $this->loadApiData("filecache/query_total_node_info", $data, $signature);
    }

}
