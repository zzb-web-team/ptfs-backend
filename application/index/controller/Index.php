<?php
namespace app\index\controller;
use think\facade\Lang;
class Index
{
	//首页
    public function index()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        //分别进行判断
        if(strpos($agent,'iphone') || strpos($agent,'ipad'))
        {
            return view('mobile');
        }
        return view('pc');
    }

}
