<?php
$mod_name="faraday";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_logs_panel="disabled";
$mod_panel="show";
$mod_alias="Faraday";

# OPTIONS
$mod_faraday_https="0";
$mod_faraday_server="127.0.0.1";
$mod_faraday_port="9876";
$mod_faraday_severity="3";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_dos2unix = "/usr/bin/dos2unix";
$bin_touch = "/usr/bin/touch";
$bin_mv = "/bin/mv";

# ISUP
$mod_isup="grep 'faraday-client.py' /usr/share/fruitywifi/www/modules/ap/includes/dnsmasq-dhcp-script.sh";
?>
