<?php
namespace app\admin\controller;
use think\Validate;

class Store extends Common
{

    public function insert_table()
    {
        $data = input('post.');
        return self::loadApiData("store/insert_table", $data);
    }

}
