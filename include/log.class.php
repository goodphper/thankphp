<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2016/6/1
 * Time: 10:02
 */
/*
 * 日志类，记录信息到日志
 * 思路：给定文件，写入文件（fopen，fwrite）
 * 如果文件大于1M，重新写一份
 */

/*
 * 传我一个内容
 * 判断当前日志大小
 * 如果>1M,备份
 * 否则写入
 */

class Log{
    const LOGFILE = 'curr.log';//设置一个常量，代表日志文件的名称；

    //写日志$con 要写的内容
    public static function write($cont){
        $cont .= "\r\n";//这里一定要用双引号，不能用单引号
        //判断是否备份
        $log = self::isBak();
        $fh = fopen($log,'ab');
        fwrite($fh,$cont);
        fclose($fh);
    }

    //备份日志
    //就是把原来的日志文件，改个名，保存起来
    //改成 年-月-日.bak
    public static function bak(){
        $log = ROOT.'data/log/'.self::LOGFILE;
        $bak = ROOT.'data/log'.date('ymd').mt_rand(10000,99999).'.bak';
        return rename($log,$bak);
    }


    //读取并判断日志的大小
    public static function isBak(){
        $log = ROOT.'data/log/'.self::LOGFILE;
        //如果文件不存在，则创建该文件
        if(!file_exists($log)){
            touch($log);
            return $log;//返回文件路径
        }

        //要是存在，则判断大小
        //clearstatcache()
        $size = filesize($log);
        if($size < 1024 *1024){//说明还能往里写，直接return
            return $log;
        }
        //如果能走到这行，说明大于1M；备份旧文件，生成新的；
        if(!self::bak()){
            return $log;
        }else{
            touch($log);
            return $log;
        }

    }




}