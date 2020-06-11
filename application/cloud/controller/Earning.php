<?php
namespace app\cloud\controller;

class Earning extends Common
{
    public function node_base_info()
    {
        $data = input('post.');
        return self::loadApiData("earnings/node_base_info",$data);
    }

    public function node_state(){
        $data = input('post.');
        return self::loadApiData("earnings/node_state",$data);
    }

    public function flow_state(){
        $data = input('post.');
        return self::loadApiData("earnings/flow_state",$data);
    }

    public function bw_state(){
        $data = input('post.');
        return self::loadApiData("earnings/bw_state",$data);
    }

    public function node_earnings(){
        $data = input('post.');
        return self::loadApiData("earnings/node_earnings",$data);
    }

    public function node_earnings_detail(){
        $data = input('post.');
        return self::loadApiData("earnings/node_earnings_detail",$data);
    }

    public function node_h(){
        $data = input('post.');
        return self::loadApiData("earnings/node_h",$data);
    }

    public function node_h_detail(){
        $data = input('post.');
        return self::loadApiData("earnings/node_h_detail",$data);
    }

    public function update_price_param(){
        $data = input('post.');
        return self::loadApiData("earnings/update_price_param",$data);
    }

    public function get_price_param(){
        $data = input('post.');
        return self::loadApiData("earnings/get_price_param",$data);
    }
}
