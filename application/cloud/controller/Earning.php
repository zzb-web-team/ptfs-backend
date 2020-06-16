<?php
namespace app\cloud\controller;

class Earning extends Common
{
    public function node_base_info()
    {
        $data = input('post.');
        return self::loadApiData("earnings/node_base_info", $data);
    }

    public function node_state()
    {
        $data = input('post.');
        return self::loadApiData("earnings/node_state", $data);
    }

    public function flow_state()
    {
        $data = input('post.');
        return self::loadApiData("earnings/flow_state", $data);
    }

    public function bw_state()
    {
        $data = input('post.');
        return self::loadApiData("earnings/bw_state", $data);
    }

    public function node_pf()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/node_pf", $data);
    }

    public function node_pf_detail()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/node_pf_detail", $data);
    }

    public function node_pv()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/node_pv", $data);
    }

    public function node_pv_detail()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/node_pv_detail", $data);
    }

    public function update_net_info()
    {
        $data = input('post.');
        // if (count($data)>0) {
        //     foreach ($data as $k => $v) {
        //         if (count($v)>0) {
        //             foreach ($v as $m => $n) {
        //                 $v[$m] = floatval($n);
        //             }
        //             $data[$k] = $v;
        //         }
                
        //     }
        // }
        $data = $this->parseValtoFloat($data);
        return self::loadApiData("ipfs_profit/update_net_info", $data);
    }

    private function parseValtoFloat($data) {
        if (count($data)>0) {
            foreach ($data as $k => $v) {
                if (!is_array($v)) {
                    $data[$k] = floatval($v);
                } else {
                    $data[$k] = $this->parseValtoFloat($v);
                }
            }
        }
        return $data;
    }

    public function get_net_info()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/get_net_info", $data);
    }

    public function export_excel()
    {
        $data = input('post.');
        return self::loadApiData("ipfs_profit/export_excel", $data);
    }
}
