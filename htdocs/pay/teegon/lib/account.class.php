<?php
class Account extends TeegonService {
    
    //创建domain账户
    public function ensure($name ='', $json = true)
    {
        $parm =[
          'name' => $name
        ];
        $shortAPI = "/account/ensure";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
    
    //创建子账户
    public function create($name ='', $json = true)
    {
        $parm =[
          'name' => $name
        ];
        $shortAPI = "/account/create";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
    
    //获取所有子账户
    public function getList($parm =[], $json = true)
    {
        $shortAPI = "/account/list";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    //获取子账户详情，参数为空则返回主账号的账号详情
    public function detail($accountid = '', $json = true)
    {
        $parm =[
          'account_id' => $accountid
        ];
        $shortAPI = "/account/get";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    //获取子帐号可以提现的金额
    public function amount($accountid, $json = true)
    {
        $parm =[
          'account_id' => $accountid
        ];
        $shortAPI = "/account/amount";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
     //获取所有账户下的流水记录
    public function journals($parm =[], $json = true)
    {
        $shortAPI = "/account/journal/list";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    //获取账户状态
    public function status($accountid, $json = true)
    {
        $parm =[
          'account_id' => $accountid
        ];
        $shortAPI = "/account/status";
        return $this->handle($shortAPI, 'get', $parm, $json);
    }
    
    //更新户信息
    public function update($accountid, $json = true)
    {
        $parm =[
          'account_id' => $accountid
        ];
        $shortAPI = "/account/update";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
    
    //获取子账户银行卡列表
    public function card_list($accountid, $json = true)
    {
        $parm =[
          'account_id' => $accountid
        ];
        $shortAPI = "/account/card_list";
        return $this->handle($shortAPI, 'post', $parm, $json);
    }
}
