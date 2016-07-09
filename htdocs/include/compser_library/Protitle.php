<?php
// 分销商爵位晋级类
// use Illuminate\Database\Capsule\Manager as DB;
class ProTitle
{
    private $Users_ID;
    private $User_ID;

    public function __construct($Users_ID, $UserID) {
        $this->Users_ID = $Users_ID;
        $this->User_ID = $UserID;
    }
    
     /**
     * 获取指定用户下级所有分销商用户UserID
     * @param  int $userid  [用户userid]
     * @return array    所有直接下属一级分销商用户userid]
     */
    public function get_sons_dis_userid($userid) {

        $dis_userid_arr = Dis_Account::where('invite_id', $userid)->lists('User_ID');

        return $dis_userid_arr;
    }
    // 获取下级
    function get_sons($level, $UID)
    {
        $temp = Dis_Account::where('Dis_Path', 'like', '%,' . $UID . ',%')->get(array(
            'User_ID',
            'Dis_Path'
        ))
            ->map(function ($account) {
            return $account->toArray();
        })
            ->all();
        $list = array();
        
        foreach ($temp as $t) {
            $dispath = substr($t['Dis_Path'], 1, - 1);
            $arr = explode(',', $dispath);
            $key = array_search($UID, $arr);
            
            if (count($arr) - $key <= $level) {
                $list[] = $t['User_ID'];
            } else {
                continue;
            }
        }
        
        return $list;
    }
    
    function up_nobility_level() {
        $dis_config = Dis_Config::where('Users_ID', $this->Users_ID)->first();
        if (empty($dis_config->Pro_Title_Level)) {
            return true;
        }
        
        $protitles = json_decode($dis_config->Pro_Title_Level, true);
        $protitless = array();
        $protitles_temp = array_reverse($protitles, true);

        //过滤不启用的爵位(名称为空)  
        $protitless = array();      
        foreach ($protitles as $pk => $pv) {
            if (! empty($pv['Name'])) {
                $protitless[$pk] = $pv;
            }
        }

        //爵位全部未启用
        if (empty($protitless)) return true;

        $disaccount = Dis_Account::Multiwhere(array(
            'Users_ID' => $this->Users_ID,
            'User_ID' => $this->User_ID
        ))->first();
        if (empty($disaccount->Users_ID)) {
            return true;
        }
        

        //寻找系统限制级别2内的祖先,假如关系为 99->1->4->11,当前分销商为11,则2级别内祖先分销商为[1, 4]
        $ancestors = $disaccount->getAncestorIds($dis_config->Dis_Level, 0);
        
        //加上上级分销商用户ID
        array_push($ancestors, $this->User_ID);

        $ancestors = array_reverse($ancestors);

        foreach ($ancestors as $UID) {
            $user_distribute_account = Dis_Account::Multiwhere(array(
                'Users_ID' => $this->Users_ID,
                'User_ID' => $UID
            ))->first(array(
                'Professional_Title'
            ));
            
            // 自身消费额
            // $Consume = Order::where(array('User_ID'=>$UID,'Order_Status'=>4))->sum('Order_TotalPrice');
            $Consume = Order::where(array(
                'User_ID' => $UID
            ))->where('Order_Status', '>=', $dis_config->Pro_Title_Status)->sum('Order_TotalPrice');
            
            // 自身销售额
            $Sales_Self = Order::where(array(
                'Owner_ID' => $UID
            ))->where('Order_Status', '>=', $dis_config->Pro_Title_Status)->sum('Order_TotalPrice');
            
            //团队销售额计算使用
            $Sales_Group_2 = $Sales_Self;

            //获取所有直接下级分销商用户销售额 20160620 11:17 sxf
            $sess_userid = $UID;
            $sons_dis_userid = $this->get_sons_dis_userid($sess_userid);
            if ($sons_dis_userid) {

                $user_dis_sale_amount = $this->get_dis_sale_amount($sons_dis_userid);

                $Sales_Self = $Sales_Self + $user_dis_sale_amount;
            }
            unset($sons_dis_userid);

            //团队销售额 // 下级会员
            $childs = $this->get_sons($dis_config->Dis_Level, $UID);
           
            if (! empty($childs)) {
                $Sales_Group = Order::where('Order_Status', '>=', $dis_config->Pro_Title_Status)->whereIn('Owner_ID', $childs)->sum('Order_TotalPrice');
            } else {
                $Sales_Group = 0;
            }
            
            //修正团队销售客未包含“自身消费额”和“自身销售额”,而在"自身销售额"里已经包含过了 自身消费额，所以直接加上“自身销售额”就可以了。
            $Sales_Group = $Sales_Group + $Sales_Group_2;   
            $level = 0;
            
            foreach ($protitless as $key => $item) {
                if ($item['Sales_Group'] <= $Sales_Group && $item['Sales_Self'] <= $Sales_Self && $item['Consume'] <= $Consume) {
                    
                    $level = $key;
                    // break;
                }
            }
            
            Dis_Account::Multiwhere(array(
                'Users_ID' => $this->Users_ID,
                'User_ID' => $UID
            ))->update(array(
                'Professional_Title' => $level
            ));
            continue;
        }
        
        return true;
    }


     /**
     * 获取分销商销售金额,对于分销商以前普通会员身份的销售金额不统计
     * @param  array $son_dis_userid_arr [description]
     * @return number                     [description]
     */
    public function get_dis_sale_amount($son_dis_userid_arr) {
            global $DB;

            $dis_config = Dis_Config::where('Users_ID',$this->Users_ID)->first();
            if(empty($dis_config->Pro_Title_Level)){
                return 0;
            }               

            $userids = "'" . implode("','", $son_dis_userid_arr) . "'";
            $sql = "WHERE User_ID=Owner_ID AND User_ID IN ($userids) AND Order_Status >= " . $dis_config->Pro_Title_Status;

            $ret = $DB->GetRs('user_order', 'SUM(Order_TotalPrice) AS total', $sql);
            $user_dis_sale_amount = $ret['total'];

            return $user_dis_sale_amount;

    }
}