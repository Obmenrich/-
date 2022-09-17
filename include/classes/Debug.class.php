<?php
if(!defined('INSIDE')){ die("attemp hacking");}

class Debug {
    var $log,$numqueries,$page,$times,$table;
    function __construct() {
        $this->vars = $this->log = array();
        $this->numqueries = 0;
        $this->times = 0;
        $this->table = "";
    }

    function add($mes, $page, $time, $table) {
        array_push($this->log, $mes);
        $this->numqueries++;
        $this->page = $page;
        $this->times += $time;
        $this->table = $table;
    }

    function addf($mes) {
        $this->logf = $mes;
        $this->write_log();
    }

    function write_log() {
        global $user;
        $write_array = array(
		"stones",

        );

        $write = 0;
        foreach ( $write_array as $element ) {
            if ($this->table == $element ) {
                $write++;
            }
        }
        if ($write != 0) {
            $dat = date("H:i", time());
            $datsms = date("i", time());
            $logfname = "debug_".date( 'd.m.y', time())."_log.txt";
            $logs = "/home/evangel/oglog/".$logfname;
            reWriteDataInFile($dat.' : log: '.$user['username'].' - '.$this->logf, $logs);
            //if ($user['authlevel'] != 0) echo $dat.' : log:'.$this->logf;
        }
    }

    function printDebug() {
        $debug_n = "\n<center><table width=90%><tr><td class=k colspan=6>MySQL Debug Log</td></tr>";
        foreach ($this->log as $value) {
            $debug_n .=  "<tr><th>{$value[0]}</th><th>{$value[1]}</th><th>{$value[2]}</th><th>{$value[3]}</th><th>{$value[4]}</th><th>{$value[5]}</th></tr>";
        }
        $debug_n .= "<tr><th colspan=5>Times</th><th>".$this->times."</th></tr></table></center>";
        return $debug_n;
        //die();
    }

    function error($message,$title) {
        global $game_config,$user,$ugamela_root_path;
        if($game_config['debug']==1){
            echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
            echo  "<table>".$this->log."</table>";
        }
        //else{
        //A futuro, se creara una tabla especial, para almacenar
        //los errores que ocurran.
        $next = '<br><b>Пожалуйста свяжитесь с администратором проекта.<br>Приносим свои извинения!!!</b>';

        die($this->errorTable('MySQL в настоящее время не доступен...'.$next));
        if ($user){
            $query = "INSERT INTO {{table}} SET `error_sender` = '{$user['id']}', `error_time` = '".time()."', `error_type` = '{$title}', `error_text` = '".mysql_escape_string($message)."';";
            $sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors',$query)) or die($this->errorTable('Ошибка при работе с Базой Данных...'.$next));
            $query = "explain select * from {{table}}";
            $q = mysql_fetch_array(mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors', $query))) or die($this->errorTable('error fatal: '.$next));
            if (!function_exists('message'))
            echo "Ошибка, пожалуйста, свяжитесь с администратором. Ошибка №: <b>".$q['rows']."</b>";
            else
            message("Ошибка, пожалуйста, свяжитесь с администратором. Ошибка №: <b>".$q['rows']."</b>", "Ошибка");
        }
        die();
    }

    function errorTable($message) {
        global $game_config,$user,$ugamela_root_path;
        return "<html><head><title>ERROR</title></head><style>body {background-color: #000000;color:#ffffff;text-align: center;}</style><table style='width: 100%; height: 100%; position: fixed;'><tr><td  align='center'><div style='width: 550px; border: 2px solid #8CCA28; border-radius: 10px 10px 10px 10px;'><table><tr><td><img src='/images/alert.png'</td><td>{$message}</td></tr></table></div></td></tr></table></html>";
    }
}
?>