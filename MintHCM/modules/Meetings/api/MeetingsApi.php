<?php

class MeetingsApi
{

    public function getUsers($args)
    {
        $users_bean = BeanFactory::getBean('Users');
        if(!empty($args['id']) && is_string($args['id'])){
            $list = $users_bean->get_list(
                "",
                "users.securitygroup_id = '{$users_bean->db->quote($args['id'])}' "
            );
        }
        $users_id=[];
        foreach ($list['list'] as $key => $value) {
            $users_id[] = array( 
                'id'=>$value->id,
                'full_name'=>$value->full_name,
                'phone_work'=>$value->phone_work,
                'phone_mobile'=>$value->phone_mobile,
                'email1'=>$value->email1,
            );
        }
        return json_encode($users_id);
    }

}
