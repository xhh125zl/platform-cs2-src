<?php
class ShopConfig extends Base{

    private $Users_ID;//商家ID

    public function updateUsersPayCharge(){
        echo $this->Users_ID;
    }

    //__set魔术方法为属性赋值
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}