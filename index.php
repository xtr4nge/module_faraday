<? 
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?
include "../../login_check.php";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js?<?=time()?>"></script>

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "../../config/config.php";
include "../../login_check.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
    regex_standard($_GET["tempname"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

include "includes/options_config.php";

?>

<div class="rounded-top" align="left">&nbsp; <b><?=$mod_alias?></b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;version <?=$mod_version?><br>
    <?
    /*
    $isinstalled = exec("dpkg-query -s python-requests|grep -iEe '^status.+installed'");
    if ($isinstalled != "") {
        echo "&nbsp; $mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; $mod_alias <a href='includes/module_action.php?install=install_autostart' style='color:red'>install</a><br>";
    }
    */
    ?>
    
    <?
    $ismoduleup = exec($mod_isup);
    if ($ismoduleup != "") {
        echo "&nbsp; $mod_alias  <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='includes/module_action.php?service=$mod_name&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp; $mod_alias  <font color='red'><b>disabled</b></font>. | <a href='includes/module_action.php?service=$mod_name&action=start&page=module'><b>start</b></a>"; 
    }
    ?>

</div>

<br>


<div id="msg" style="font-size: larger;">
Loading, please wait...
</div>

<div id="body" style="display:none;">


    <div id="result" class="module">
        <ul>
            <li><a href="#tab-output">Output</a></li>
            <li><a href="#tab-options">Options</a></li>
            <li><a href="#tab-history">History</a></li>
            <li><a href="#tab-about">About</a></li>
        </ul>

        <!-- OUTPUT -->

        <div id="tab-output">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php">
            <input class="btn btn-default btn-sm" type="submit" value="Refresh">
            <br><br>
            <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
            
                $data = open_file($filename);
                
                // REVERSE
                //$data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                
            ?>
            <textarea id="output" class="module-content" style="font-family: monospace, courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="logs">
            </form>
            
        </div>
        
        <!-- END OUTPUT -->
        
        <!-- OPTIONS -->

        <div id="tab-options" class="history">
            <script>
                function getValue(id) {
                    console.log(id)
                    var e = document.getElementById(id);
                    var output = e.options[e.selectedIndex].text;
                    console.log(output)
                }
                
            </script>
            <h4>
                Faraday API (xmlrpc)
            </h4>
            <h5>
                <input id="mod_faraday_https" type="checkbox" name="my-checkbox" <? if ($mod_faraday_https == "1") echo "checked"; ?> onclick="setCheckbox(this, 'mod_faraday_https')" >
                HTTPS
                
                <br><br>
                
                Host
                <br>
                <input id="faraday_server" class="form-control input-sm" placeholder="Server" value="<?=$mod_faraday_server?>" style="width: 180px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('faraday_server', 'mod_faraday_server');">
                
                <br><br>
                
                Port
                <br>
                <input id="faraday_port" class="form-control input-sm" placeholder="Port" value="<?=$mod_faraday_port?>" style="width: 180px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('faraday_port', 'mod_faraday_port');">
                
                <br><br>
                
                Severity (new host)
                <br>
                <select class="btn btn-default btn-sm" id="faraday_severity" onchange="setOptionSelect(this, 'mod_faraday_severity')" s-tyle="width: 70px">
                    <option value="5" <? if ($mod_faraday_severity == "5") echo "selected"?> >unclassified</option>
                    <option value="0" <? if ($mod_faraday_severity == "0") echo "selected"?> >info</option>
                    <option value="1" <? if ($mod_faraday_severity == "1") echo "selected"?> >low</option>
                    <option value="2" <? if ($mod_faraday_severity == "2") echo "selected"?> >med</option>
                    <option value="3" <? if ($mod_faraday_severity == "3") echo "selected"?> >high</option>
                    <option value="4" <? if ($mod_faraday_severity == "4") echo "selected"?> >critical</option>
                </select>
            </h5>
            
        </div>
        <!-- END OPTIONS -->
        
        <!-- HISTORY -->

        <div id="tab-history" class="history">
            <a href="?tab=4"><input class="btn btn-default btn-sm" type="submit" value="refresh"></a>
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=3'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
        <!-- END HISTORY -->
        
        <!-- ABOUT -->

        <div id="tab-about" class="history">
            <? include "includes/about.php"; ?>
        </div>

        <!-- END ABOUT -->
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 0 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 5) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    }  
    ?>

</div>

<script type="text/javascript">
    $('#loading').hide();
    
    $(document).ready(function() {
        $('#body').show();
        $('#msg').hide();
    });
</script>

</body>
</html>
