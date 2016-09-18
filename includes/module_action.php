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
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
    regex_standard($_GET["hopping_conf"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];

function killRegex($regex){
	
	$exec = "ps aux|grep -E '$regex' | grep -v grep | awk '{print $2}'";
	exec($exec,$output);
	
	if (count($output) > 0) {
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
	}	
}

//$script_path = "/usr/share/fruitywifi/conf/dnsmasq-dhcp-script.sh";
$script_path = "/usr/share/fruitywifi/www/modules/ap/includes/dnsmasq-dhcp-script.sh";

if($service == "faraday") {
    
	if ($action == "start") {

        // INCLUDE rc.local
        $line_search = "faraday-client.py";
        
		$line_add = "python /usr/share/FruityWifi/www/modules/faraday/includes/faraday-client.py -s \\\"$mod_faraday_server\\\" -p $mod_faraday_port -f createHostAndInterface -d \\\"\\\$3|\\\$2|\\\$4|$mod_faraday_severity\\\"";
		//$line_add = "python /usr/share/FruityWifi/www/modules/faraday/includes/faraday-client.py";
		
		$exec = "sed -i '/$line_search/d' $script_path";
		exec_fruitywifi($exec);

		$exec = "echo '$line_add' >> $script_path";
		exec_fruitywifi($exec);
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            exec_fruitywifi($exec);
        }
    
    
    } else if ($action == "stop") {
        
		// INCLUDE rc.local
        $line_search = "faraday-client.py";
		
		$exec = "sed -i '/$line_search/d' $script_path";
		exec_fruitywifi($exec);

    }

}

if ($install == "install_autostart") {

    $exec = "chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module=autostart');
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name);
}

?>