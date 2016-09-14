<?php
class Refund extends TeegonService {
    
    /** 
        列出退款
     */
    public function getlist($json = true){
        $shortAPI = '/refund/list/';
        $parm = [];
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
         
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    /** 
        列出退款
     */
    public function show($id, $json = true){
        $shortAPI = '/refund/show/';
        $parm = [
            'id' => $id
        ];
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
         
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    /** 
        确认退款
     */
    public function confirm($parm, $json = true){
        $shortAPI = '/refund/confirm/';
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
         
        return $this->handle($shortAPI, 'post', $parm, $json);
    }

    /** 
        创建退款
     */

    public function create($parm = [], $json = true){
        $method = "/refund/create/";
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
        return $this->handle($shortAPI, 'post', $parm, $json);
    }

    /** 
        批量退款
     */

    public function batch_refund($data, $json = true){
        $method = "/refund/batch_refund/";
        if(!is_string($data)) return [ 'code' => '10001', 'msg' => 'data参数必须是以‘|’分割的字符串'];
        $parm = [
            'changes' => $data
        ];
        if(is_array($parm)){
            $parm = array_merge($parm, $this->parm);
        }
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
}
