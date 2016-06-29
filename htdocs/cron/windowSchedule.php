<?php
header("Content-type: text/html; charset=UTF-8");

Class Task
{
    private $parm = [];
    private $cmd = "schtasks";
    private $action = "";
    //添加参数
    public function add($key,$value){
        if(empty($key) || !$key || empty($value) || !$value){
            return false;
        }
        $this->parm[$key] = $value;
    }
    
    //创建计划任务
    public function change($taskName, $taskRun, $schedule = 'DAILY')
    {
        $string = $this->cmd ." /change /sc {$schedule} /tn \"{$taskName}\" /tr \"{$taskRun}\"";
        $string .= $this->componeParm();
        exec($string);
    }
    
    public function getXML($taskName)
    {
        $string  = "chcp 437 & ";
        $string .= "schtasks.exe /query /xml /tn \"{$taskName}\" > ".$_SERVER["DOCUMENT_ROOT"]."/uploadfiles/{$taskName}.xml";
        exec($string);
        sleep(1);
        $fileStr = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/uploadfiles/{$taskName}.xml");
        if($fileStr){
            $pos = stripos($fileStr,"</Command>");
            $temp = substr($fileStr,0,$pos+10);
            $temp1 = substr($fileStr,$pos+10);
            $fileStr = $temp."\r\n";
            $fileStr .= "      <WorkingDirectory>".$_SERVER["DOCUMENT_ROOT"]."\\cron\\</WorkingDirectory>";
            $fileStr .= $temp1;
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/uploadfiles/{$taskName}.xml",$fileStr);
        }
        $string  = "chcp 437 & ";
        $string .= "schtasks.exe /create /tn \"{$taskName}\" /xml ".$_SERVER["DOCUMENT_ROOT"]."/uploadfiles/{$taskName}.xml /f";
        exec($string);
    }
    
    public function query($user ="", $pwd = "")
    {
        $string = " chcp 437 &";
        $string .= $this->cmd . " /query /fo csv";
        if($user && $pwd){
            $string .= " /u {$user} /p {$pwd}";
        }
        $cstr = exec($string);
    }
    
    //修改计划任务
    public function create($taskName, $taskRun, $schedule = 'DAILY')
    {
        $string = $this->cmd ." /create /sc {$schedule} /tn \"{$taskName}\" /tr \"{$taskRun}\"";
        $string .= $this->componeParm();
        exec($string);
    }
    
    //立即运行计划任务，用于测试要运行的计划任务是否正确
    public function run($taskName ,$user ="", $pwd = "",$host="")
    {
        $this->execute($taskName, "run", $user, $pwd, $host);
    }
    
    //立即停止计划任务
    public function stop($taskName ,$user ="", $pwd = "", $host="")
    {
        $this->execute($taskName, "end", $user, $pwd, $host);
    }
    
    //立即删除计划任务
    public function remove($taskName ,$user ="", $pwd = "", $host="")
    {
        $this->execute($taskName, "delete", $user, $pwd, $host);
    }
    
    private function execute($taskName, $type, $user, $pwd, $host="")
    {
        $string = $this->cmd. " /{$type} /tn \"{$taskName}\" /f".($host?"/s {$host}":"");
        if($user){
            $string .= " /u {$user} /p {$pwd}";
        }
        exec($string);
    }
    
    //合并参数
    private function componeParm()
    {
        $string = "";
        foreach ($this->parm as $k => $v)
        {
            $string.=' /'.$k.' '.$v;
        }
        return $string;
    }
}