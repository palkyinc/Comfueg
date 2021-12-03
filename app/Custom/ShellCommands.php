<?php

namespace App\Custom;

class ShellCommands 
{
    
    private $pid;
    private $command;

    /* 
    * implementar como:
    * $output = new ShellCommands($comando);
    * while ($output->status()){}
    * $output->status(); para detener
     */

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            return ($this->runCom());
        }
    }
    private function runCom(){
        $command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $this->pid = (int)$op[0];
        return $op;
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
