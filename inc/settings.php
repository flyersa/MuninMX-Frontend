<?php

define("MAINTAINANCE",0);

// PROTO, important, wether the frontend is running in a http or https configuration.
define("PROTO","http");

// MYSQL!
define("MYSQL_HOST","127.0.0.1");
define("MYSQL_USER","muninmx");
define("MYSQL_PASS","changeme");
define("MYSQL_DB","muninmx");

// Mongodb/Tokux
define("MONGO_HOST", "127.0.0.1");
define("MONGO_DB","muninmx");
define("MONGO_DB_ESSENTIALS","muninmxes");
define("MONGO_DB_CHECKS","muninmxchecks");
define("EXPORTDIR","/tmp"); // directory used for exports
define("EXPORT_BIN","/usr/bin/mongoexport");

// Show hourly averages if metric resultsize is > specified . This will improve graph load time
define("SWITCH_TO_AVG_PEAK",100000);

// define crowd Fallback Login for admins
define("CROWD_ADMIN_FALLBACK",false);
#define("CROWD_PATH","UNSET");
#define("CROWD_APPNAME","CROWDAPP");
#define("CROWD_APPPASS","CROWDPASS");
// what user_id to give user after login, should be the uid of user "admin"
#define("CROWD_MAPUID",1);


// Application Name
define("APP_NAME","MuninMX");

// muninmx collector settings
define("MCD_HOST","127.0.0.1");
define("MCD_PORT",49000);
define("GRAPH_PERIOD","second");

//muninmx live collector settings
define("MLD_HOST","127.0.0.1");
define("MLD_PORT",8090);

// ignore negative values in graphs (skip them)
define("IGNORE_NEGATIVES",true);

// bucket stat baseurl

// SET FOR DEMO
define("BUCKETSTAT_BASE","http://".$_SERVER['SERVER_ADDR'].":8080/");

// collector primary IP, used for munin-node.conf allow section as hint in addNode formular
define("COLLECTOR_PRIMARY_IP",$_SERVER['SERVER_ADDR']);


// url for servicedesk / plan querys , used on users setting page for limit notice
define("OPERATOR_URL","http://www.muninmx.com");
define("OPERATOR_NAME","MuninMX.com");

define("BASEURL",PROTO."://".$_SERVER['SERVER_ADDR']);

define("MAIL_ADDR","no-reply@muninmx.com");

?>