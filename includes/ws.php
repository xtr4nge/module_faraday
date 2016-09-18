<?php
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

include "../../api/includes/ws.php";

class WebServiceExtended extends WebService {
	
	// LOG
	public function addDataToLog($data)
	{
		include "../_info_.php";
		//include "../../../functions.php";
		
		//$value = explode("|", $data);
		//$output = ["date" => date('Y-m-d H:i:s'), "ip" => $value[0], "macaddress" => $value[1], "hostname" => $value[2]];
		
		$exec = "echo '$data' >> $mod_logs";
		//exec_fruitywifi($exec);
		
	}
	
	// FARADAY ADD HOST
	public function createHostAndInterface($data)
	{
		include "../_info_.php";
		//include "../../../functions.php";
		
		$value = explode("|", $data);
		$exec = "python faraday-client.py -s $mod_faraday_server -p $mod_faraday_port -f createHostAndInterface -d '$data'";
		exec_fruitywifi($exec);
		
		$output = ["date" => date('Y-m-d H:i:s'), "ip" => $value[0], "mac" => $value[1], "host" => $value[2], "vuln" => "FruityWiFi", "severity" => $value[3]];
		//echo json_encode($exec);
		echo json_encode($output);
		
		//$this->addDataToLog($data);
		
	}
	
	// FARADAY ADD HOST
	public function createAndAddVulnToHost($data)
	{
		include "../_info_.php";
		
		$value = explode("|", $data);
		$exec = "python faraday-client.py -s $mod_faraday_server -p $mod_faraday_port -f createAndAddVulnToHost -d '$data'";
		
		$output = ["date" => date('Y-m-d H:i:s'), "ip" => $value[0], "mac" => $value[1], "host" => $value[2], "vuln" => $value[3], "severity" => $value[4]];
		//echo json_encode($exec);
		echo json_encode($output);
	}
	
    
}
?>