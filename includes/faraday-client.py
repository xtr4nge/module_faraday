#!/usr/bin/env python

import os, sys
import time
import getopt
import xmlrpclib
import datetime, time
import json

# ------- MENU -------
def usage():
    print "\nfaraday-client 1.0 by xtr4nge"
    
    print "Usage: faraday-client.py <options>\n"
    print "Options:"
    print "-s <ip>, --server=<ip>                   Faraday server IP"
    print "-p <num>, --port=<num>                   Faraday port"
    print "-f <value>, --function=<value>           [createHostAndInterface|createAndAddVulnToHost]"
    print "-d <value>, --data=<value>               Data to be passed to the function"
    print "-x, --https                              HTTPS"
    print "-l <log>, --log=<log>                    log file"
    print "-h                                       Print this help message."
    print ""
    print "Author: xtr4nge"
    print ""


def parseOptions(argv):    
    faraday_server = ""
    faraday_port = "9876"
    faraday_proto = "http"
    FUNCTION = ""
    DATA = ""

    try:
        opts, args = getopt.getopt(argv, "hs:p:xf:d:",
                                   ["help", "server=", "port=", "https", "function=", "data="])

        for opt, arg in opts:
            if opt in ("-h", "--help"):
                usage()
                sys.exit()
            elif opt in ("-s", "--server"):
                faraday_server = arg
            elif opt in ("-p", "--port"):
                faraday_port = int(arg)
            elif opt in ("-x", "--https"):
                faraday_proto = "https"
            elif opt in ("-f", "--function"):
                FUNCTION = arg
            elif opt in ("-d", "--data"):
                DATA = arg
            
            
        if faraday_server == "":
            usage()
            sys.exit()
        
        return (faraday_server, faraday_port, faraday_proto, FUNCTION, DATA)
                    
    except getopt.GetoptError:           
        usage()
        sys.exit(2) 

# -------------------------
# GLOBAL VARIABLES
# -------------------------

(faraday_server, faraday_port, faraday_proto, FUNCTION, DATA) = parseOptions(sys.argv[1:])

def save_json(v_ip, v_macaddress, v_name, v_severity, v_vuln):
    
    log_datetime = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    LOG = {
        "date": log_datetime,
        "ip": v_ip,
        "mac": v_macaddress,
        "host": v_name,
        "vuln": v_vuln,
        "severity": v_severity
    }
    
    f = open('/usr/share/fruitywifi/logs/faraday.log', 'a+')
    f.write(json.dumps(LOG)+"\n")
    f.close()
    
def save_log(v_ip, v_macaddress, v_name, v_severity, v_vuln):

    log_datetime = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    LOG = "%s %s %s %s %s %s " % (log_datetime, v_ip, v_macaddress, v_name, v_severity, v_vuln)

    f = open("/usr/share/fruitywifi/logs/faraday.log", 'a+')
    f.write(LOG + "\n")
    f.close()

def createHostAndInterface(api, data):
    
    value = data.split("|")
    IP = value[0].strip()
    MACADDRESS = value[1].strip()
    NAME = value[2].strip()
    SEVERITY = value[3].strip()
    
    #save_json(IP, MACADDRESS, NAME, SEVERITY, "FruityWiFi")
    save_log(IP, MACADDRESS, NAME, SEVERITY, "FruityWiFi")
    
    desc = "Client ip: " + IP + \
        " has been connected to FruityWiFi\n"
    desc += "More information:"
    desc += "\nname: " + NAME
    
    h_id = api.createAndAddHost(IP, "")
    
    i_id = api.createAndAddInterface(
                h_id,
                IP,
                MACADDRESS,
                IP,
                "0.0.0.0",
                "0.0.0.0",
                [],
                "0000:0000:0000:0000:0000:0000:0000:0000",
                "00",
                "0000:0000:0000:0000:0000:0000:0000:0000",
                [],
                "",
                []
                )
    
    v_id = api.createAndAddVulnToHost(
                h_id,
                "FruityWiFi",
                desc,
                "http://www.fruitywifi.com/",
                SEVERITY,
                ""
                )

def createAndAddVulnToHost(api, data):
    
    value = data.split("|")
    IP = value[0].strip()
    NAME = value[1].strip()
    VULN = value[2].strip()
    
    print IP, NAME, VULN
    
    desc = "Client ip: " + IP + \
        " has been connected to FruityWiFi\n"
    desc += "More information:"
    desc += "\nvuln: " + VULN
    
    h_id = api.createAndAddHost(IP, "")
    
    v_id = api.createAndAddVulnToHost(
                h_id,
                NAME,
                desc,
                "http://www.fruitywifi.com/",
                "1",
                "x"
                )

try:
    print "Connecting Farday"
    #api = xmlrpclib.ServerProxy("http://127.0.0.1:9876/")
    server = "%s://%s:%s/" % (faraday_proto, faraday_server, faraday_port)
    print server
    api = xmlrpclib.ServerProxy(server)

    if FUNCTION == "createHostAndInterface":
        createHostAndInterface(api, DATA)
    elif FUNCTION == "createAndAddVulnToHost":
        createAndAddVulnToHost(api, DATA)

except Exception, e:
        print 'Error: %s' % e 

