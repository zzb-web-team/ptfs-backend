<?php
namespace app\api\controller;
use think\facade\Cache;
use think\Validate;

class Noticepush extends Common
{

    //app获取tag
    public function get_tag()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'login_token'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
        );
        $return_data = self::loadApiData("noticepush/get_tag", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['token']);
        }
        return json($return_data);
    }

	//ptfs 立即推送
    public function push_immediate()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'push_type'  => 'require',
            'audience_type'   => 'require',
            'title' => 'require',
            'content' => 'require',
            'device_types' => 'require',
            'push_user_name' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['audience_type'] == 1 && !$data['audience']) {
            return json(['status' => -900, 'msg' => 'audience不能为空']);
        }
        $param = array(
            "push_type"   => $data['push_type'],
            "audience_type"    => $data['audience_type'],
            "audience"  => $data['audience'],
            "title"  => $data['title'],
            "content"  => $data['content'],
            "device_types"  => $data['device_types'],
            "push_user_name"  => $data['push_user_name'],
            "pic_url"  => isset($data['pic_url']) ? $data['pic_url'] : "",
            "notice_url"  => isset($data['notice_url']) ? $data['notice_url'] : "",
            "query_type" => isset($data['query_type']) ? intval($data['query_type']) : 1,
            "seq_no" => isset($data['seq_no']) ? $data['seq_no'] : "",
        );
        return self::loadApiData("noticepush/push_immediate", $param);
    }

    //Ptfs定时推送
    public function push_on_timer()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'push_type'  => 'require',
            'push_name'  => 'require',
            'schedule_time'  => 'require',
            'audience_type'   => 'require',
            'title' => 'require',
            'content' => 'require',
            'device_types' => 'require',
            'push_user_name' => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        if ($data['audience_type'] == 1 && !$data['audience']) {
            return json(['status' => -900, 'msg' => 'audience不能为空']);
        }
        $param = array(
            "push_type"   => $data['push_type'],
            "push_name"   => $data['push_name'],
            "schedule_time"   => $data['schedule_time'],
            "audience_type"    => $data['audience_type'],
            "audience"  => $data['audience'],
            "title"  => $data['title'],
            "content"  => $data['content'],
            "device_types"  => $data['device_types'],
            "push_user_name"  => $data['push_user_name'],
            "pic_url"  => isset($data['pic_url']) ? $data['pic_url'] : "",
            "notice_url"  => isset($data['notice_url']) ? $data['notice_url'] : "",
            "query_type" => isset($data['query_type']) ? intval($data['query_type']) : 1,
            "seq_no" => isset($data['seq_no']) ? $data['seq_no'] : "",
        );
        return self::loadApiData("noticepush/push_on_timer", $param);
    }

    //撤销Ptfs定时推送
    public function delete_push_on_timer()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'push_id'  => 'require',
            'modify_user_name'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "push_id"   => $data['push_id'],
            "modify_user_name"   => $data['modify_user_name'],
        );
        return self::loadApiData("noticepush/delete_push_on_timer", $param);
    }


    //Ptfs查询历史推送记录
    public function query_push_history_list()
    {
        $data = input('post.');
        //表单验证规则
        $param = array(
            "query_type"   => isset($data['query_type']) ? $data['query_type'] : 0,
            "notice_type"   => isset($data['notice_type']) ? $data['notice_type'] : 0,
            "notice_status"   => isset($data['notice_status']) ? $data['notice_status'] : 0,
            "notice_title"   => isset($data['notice_title']) ? $data['notice_title'] : "",
            "start_time"   => isset($data['start_time']) ? $data['start_time'] : 0,
            "end_time"   => isset($data['end_time']) ? $data['end_time'] : 0,
            "cur_page"   =>isset($data['cur_page']) ? $data['cur_page'] : 0,
            "order"  => isset($data['order']) ? $data['order'] : 0,
        );
        return self::loadApiData("noticepush/query_push_history_list", $param);
    }

    public function getnotice()
    {
        $data = input('post.');
        $validation = new Validate([
            'push_id'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        //表单验证规则
        $param = array(
            "push_id"   => isset($data['push_id']) ? $data['push_id'] : "",
        );
        return self::loadApiData("noticepush/query_push_history_list", $param);
    }

    //Ptfs查询历史推送记录
    public function query_push_history_listex()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'login_token'  => 'require',
            'cur_page'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
            "notice_type"   => isset($data['notice_type']) ? $data['notice_type'] : 0,
            "cur_page"   => $data['cur_page'],
            "query_type" => isset($data['query_type']) ? $data['query_type'] : 0,
            "back_id" => isset($data['back_id']) ? $data['back_id'] : "",
        );
        $return_data = self::loadApiData("noticepush/query_push_history_listex", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['token']);
        }
        return json($return_data);
    }

    //Ptfs查询历史推送记录
    public function delete_push_list()
    {
        $data = input('post.');
        //表单验证规则
        $validation = new Validate([
            'login_token'  => 'require',
            'opt_type' => 'require',
            'push_id'  => 'require',
        ]);
        //验证表单
        if(!$validation->check($data)){
            return json(['status' => -900, 'msg' => $validation->getError()]);
        }
        $param = array(
            "login_token"   => $data['login_token'],
            "login_type" => isset($data['login_type']) ? $data['login_type'] : 1,
            "opt_type"   => $data['opt_type'],
            "push_id"   => $data['push_id'],
        );
        $return_data = self::loadApiData("noticepush/delete_push_list", $param);
        $return_data = json_decode($return_data, true);
        if (isset($return_data['token_info']['token'])) {
            $this->refresh_token($data['login_token'],  $return_data['token_info']['token']);
        }
        return json($return_data);
    }


}
