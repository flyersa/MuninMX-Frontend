<?php
include("inc/settings.php");
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=addNode.sh');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
?>
#!/bin/bash

muninmxurl="<?php echo BASEURL?>"
muninmxmaster=<?php echo COLLECTOR_PRIMARY_IP?>

# check all necessary informations 
if [ -z "$1" ]; then
	echo "  the api key is needed as a parameter"
	echo "  example call:"
	echo "  ./addnode.sh <apikey>"
	echo ""
	echo "  please look at your muninmx account for the correct api key"
	echo "  $muninmxurl/apisetting.php"
	exit 1
fi

apikey=$1

echo ""
echo "===================== MuninMX Installation Script ========================="
echo "==========================================================================="
echo ""
echo "We are going to check needed tools and install them if necessary."
echo ""
echo "Requirements are:"
echo "- curl"
echo "- bind-utils"
echo "- munin-node"
echo ""
echo "On RedHat/Centos Servers the additional epel repository is needed."
echo "epel repository will be installed in an disabled state."
echo "Other packages are not changed."
echo ""

decision_default="y"
read -p "-> Please type y if this is ok for you [Y/n]: " reply
reply=${reply,,}
reply=${reply:-$decision_default}

if [ "$reply" == "y" ]; then

    # check which type of OS is used
    lowercase(){
        echo "$1" | sed "y/ABCDEFGHIJKLMNOPQRSTUVWXYZ/abcdefghijklmnopqrstuvwxyz/"
    }

    OS=`lowercase \`uname\``
    KERNEL=`uname -r`
    MACH=`uname -m`

    if [ "{$OS}" == "windowsnt" ]; then
        OS=windows
    elif [ "{$OS}" == "darwin" ]; then
        OS=mac
    else
        OS=`uname`
        if [ "${OS}" = "SunOS" ] ; then
            OS=Solaris
            ARCH=`uname -p`
            OSSTR="${OS} ${REV}(${ARCH} `uname -v`)"
        elif [ "${OS}" = "AIX" ] ; then
            OSSTR="${OS} `oslevel` (`oslevel -r`)"
        elif [ "${OS}" = "Linux" ] ; then
            if [ -f /etc/redhat-release ] ; then
                DistroBasedOn='RedHat'
                DIST=`cat /etc/redhat-release |sed s/\ release.*//`
                PSUEDONAME=`cat /etc/redhat-release | sed s/.*\(// | sed s/\)//`
                REV=`cat /etc/redhat-release | sed s/.*release\ // | sed s/\ .*//`
            elif [ -f /etc/SuSE-release ] ; then
                DistroBasedOn='SuSe'
                PSUEDONAME=`cat /etc/SuSE-release | tr "\n" ' '| sed s/VERSION.*//`
                REV=`cat /etc/SuSE-release | tr "\n" ' ' | sed s/.*=\ //`
            elif [ -f /etc/mandrake-release ] ; then
                DistroBasedOn='Mandrake'
                PSUEDONAME=`cat /etc/mandrake-release | sed s/.*\(// | sed s/\)//`
                REV=`cat /etc/mandrake-release | sed s/.*release\ // | sed s/\ .*//`
            elif [ -f /etc/debian_version ] ; then
                DistroBasedOn='Debian'
                DIST=`cat /etc/lsb-release | grep '^DISTRIB_ID' | awk -F=  '{ print $2 }'`
                PSUEDONAME=`cat /etc/lsb-release | grep '^DISTRIB_CODENAME' | awk -F=  '{ print $2 }'`
                REV=`cat /etc/lsb-release | grep '^DISTRIB_RELEASE' | awk -F=  '{ print $2 }'`
            fi
            if [ -f /etc/UnitedLinux-release ] ; then
                DIST="${DIST}[`cat /etc/UnitedLinux-release | tr "\n" ' ' | sed s/VERSION.*//`]"
            fi
            OS=`lowercase $OS`
            DistroBasedOn=`lowercase $DistroBasedOn`
            DIST=`lowercase $DIST`
            readonly OS
            readonly DIST
            readonly DistroBasedOn
            readonly PSUEDONAME
            readonly REV
            readonly KERNEL
            readonly MACH
        fi
    fi

    # Install and configure necessary packages on different OS types
    if [ "${DistroBasedOn}" = "debian" ] ; then

        # generate random auth string with 40 characters
        auth=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | head -c 40);

		# check first if curl is installed and install if necessary
		PKG_OK=$(dpkg-query -W --showformat='${Status}\n' curl|grep "install ok installed")
		if [ "" == "$PKG_OK" ]; then
            echo ""
            echo "==========================================================================="
            echo ""
			echo "No curl. Setting up curl."
			sudo apt-get -q --force-yes --yes install curl > /dev/null 2>&1
		fi

		# check if munin-node is installed and install if necessary
		PKG_OK=$(dpkg-query -W --showformat='${Status}\n' munin-node|grep "install ok installed")
		if [ "" == "$PKG_OK" ]; then
            echo ""
            echo "==========================================================================="
            echo ""
			echo "No munin-node. Setting up munin-node."
			sudo apt-get -q --force-yes --yes install munin-node > /dev/null 2>&1
		fi

		# setup munin-node for the muninmx server and add restart options for server reboots
		if [ -f /etc/munin/munin-node.conf ] ; then
			grep -q -i "allow $muninmxmaster" /etc/munin/munin-node.conf
			if [ $? -eq 1 ]; then
				echo "allow $muninmxmaster" >> /etc/munin/munin-node.conf
			fi
            port=`grep -i port /etc/munin/munin-node.conf | grep -v "#" | tail -1 | cut -d' ' -f2`

            # check if auth file exist and if it is greater than 0 bytes
            if [ -s /etc/munin/plugins/muninmxauth ]; then
                auth=$(/etc/munin/plugins/muninmxauth config)
            else
cat <<EOM >/etc/munin/plugins/muninmxauth
#!/bin/bash
if [ "\$1" = "config" ]; then
      echo $auth
fi
EOM
                chmod +x /etc/munin/plugins/muninmxauth
            fi

			update-rc.d munin-node defaults > /dev/null 2>&1
			#/etc/init.d/munin-node restart > /dev/null 2>&1
            service munin-node restart > /dev/null 2>&1
		else
            echo ""
            echo "==========================================================================="
            echo ""
			echo "munin-node config file /etc/munin/munin-node.conf does not exist"
			echo "please check munin-node installation and restart $0 script for the node installation"
			exit 1;
		fi

    elif [ "${DIST}" = "centos" ] ; then

        # generate random auth string with 40 characters
        auth=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | head -c 40);

        # check if curl is installed
        PKG_OK=$(yum -q list installed curl > /dev/null 2>&1)
        yum_result=$?
        if [ $yum_result -eq 1 ]; then
            echo ""
            echo "==========================================================================="
            echo ""
            echo "No curl. Setting up curl."
            yum -q -y install curl > /dev/null 2>&1
        fi

        # check if bind-utils is installed
        PKG_OK=$(yum -q list installed bind-utils > /dev/null 2>&1)
        yum_result=$?
        if [ $yum_result -eq 1 ]; then
            echo ""
            echo "==========================================================================="
            echo ""
            echo "No bind-utils. Setting up bind-utils."
            yum -q -y install bind-utils > /dev/null 2>&1
        fi

        # check if munin-node is installed and install if necessary
        PKG_OK=$(yum -q list installed munin-node > /dev/null 2>&1)
        yum_result=$?
        if [ $yum_result -eq 1 ]; then
            echo ""
            echo "==========================================================================="
            echo ""
            echo "No munin-node. Setting up munin-node."

            # check if epel repository is installed and enabled
            repo_ok=$(yum repolist epel|grep -i epel)
            if [ "" == "$repo_ok" ]; then
                echo "Installing epel repository in disabled state."
                echo "Other packages are not going to be changed."
                sleep 3

cat <<EOM >/etc/yum.repos.d/epel-bootstrap.repo
[epel]
name=Bootstrap EPEL
mirrorlist=http://mirrors.fedoraproject.org/mirrorlist?repo=epel-\$releasever&arch=\$basearch
failovermethod=priority
enabled=0
gpgcheck=0
EOM

                yum --enablerepo=epel -q -y install epel-release > /dev/null 2>&1
                rm -f /etc/yum.repos.d/epel-bootstrap.repo
                sed -i 's/^enabled=1/enabled=0/' /etc/yum.repos.d/epel.repo
            fi
            # install munin from epel repo
            yum --enablerepo=epel -y install munin-node > /dev/null 2>&1
        fi

        # setup munin-node for the muninmx server and add restart options for server reboots
        if [ -f /etc/munin/munin-node.conf ] ; then
            grep -q -i "allow $muninmxmaster" /etc/munin/munin-node.conf
            if [ $? -eq 1 ]; then
                echo "allow $muninmxmaster" >> /etc/munin/munin-node.conf
            fi
            port=`grep -i port /etc/munin/munin-node.conf | grep -v "#" | tail -1 | cut -d' ' -f2`
            chkconfig --add munin-node
            chkconfig --level 345 munin-node on

            # check if auth file exist and if it is greater than 0 bytes
            if [ -s /etc/munin/plugins/muninmxauth ]; then
                auth=$(/etc/munin/plugins/muninmxauth config)
            else
cat <<EOM >/etc/munin/plugins/muninmxauth
#!/bin/bash
if [ "\$1" = "config" ]; then
      echo $auth
fi
EOM
                chmod +x /etc/munin/plugins/muninmxauth
            fi

            if [ ""  == "$(ps -ef|grep -i munin|grep -v grep)" ]; then
                # /etc/init.d/munin-node start
                service munin-node start
            else
                # /etc/init.d/munin-node restart
                service munin-node restart
            fi
        else
            echo ""
            echo "==========================================================================="
            echo ""
            echo "munin-node config file /etc/munin/munin-node.conf does not exist"
            echo "please check munin-node installation and restart $0 script for the node installation"
            exit 1;
        fi

    elif [ "${DIST}" = "redhat" ] ; then
		echo "redhat based installation must be created"
        exit 1
    fi	

    # gether first not local ip adress
    ipadress=`ifconfig | sed -n 's/.*inet addr:\([0-9.]\+\)\s.*/\1/p' | grep -v '127.0.0.1' | head -1`

    # try to findout hostname with reversedns entry
    reversedns=`host $ipadress`
    if [ $? -eq 1 ]; then
		echo "reversedns lookup failed. using ip adress."
		hostname=$ipadress
    else
		reversedns=`echo $reversedns| cut -d' ' -f 5|sed 's/.$//'`
		hostname=$reversedns
    fi

    # get hostname
    echo ""
    echo "==========================================================================="
    echo ""
    echo "Please enter hostname or ip."
    echo "If left empty I would use [ $hostname ]."
    read -p "-> hostname [$hostname]: " input_hostname
    if [ ! -z "$input_hostname" ]; then
		hostname=$input_hostname
    fi

    # get group informations
    groupname="default"
    echo ""
    echo "==========================================================================="
    echo ""
    echo "Please enter groupname."
    echo "If left empty I would use the [ default ] group."
    read -p "-> groupname [default]: " input_groupname
    if [ ! -z "$input_groupname" ]; then
        groupname=$input_groupname
    fi

    # get check interval
    interval="5"
    echo ""
    echo "==========================================================================="
    echo ""
    echo "Please enter check interval between 1, 5, 10 or 15 minutes."
    echo "If left empty I would use [ 5 ] minutes as default."
    read -p "-> check interval [5]: " input_interval
    if [ ! -z "$input_interval" ]; then
        interval=$input_interval
    fi

    # check if interval is valid
    valid_interval="1"
    while [  "$valid_interval" != "0" ]; do
        if [ "$interval" != "1" ] && [ "$interval" != "5" ] && [ "$interval" != "10" ] && [ "$interval" != "15" ]; then
            interval="5"
            echo ""
            echo "==========================================================================="
            echo ""
            echo "Invalid check interval"
            echo "Please enter valid check interval between 1, 5, 10 or 15 minutes."
            echo "If left empty I would use [ 5 ] minutes as default."
            read -p "-> check interval [5]: " input_interval
            if [ ! -z "$input_interval" ]; then
                interval=$input_interval
            fi
        else 
            valid_interval="0"
            break
        fi
    done

    # make the api call to add a node

    # json parsing function, needs json and prop variables
    function jsonval {
        temp=`echo $json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w $prop`
        echo ${temp##*|}
    }

    # get output, append HTTP status code in separate line, discard error message
    out=$( curl -qSsw '\n%{http_code}' "$muninmxurl/api.php?key=$apikey&method=addNode&hostname=$hostname&port=$port&interval=$interval&groupname=$groupname&authpw=$auth" ) 2>/dev/null

    code=$(echo "$out" | tail -n1 )
    json=$(echo "$out" | head -n-1 )

    if [[ $code -ne 200 ]] ; then
        # if http code not 200, print code and json message
        echo ""
        echo "==========================================================================="
        echo ""
        echo "Error $code appeared"
        prop='msg'
        value=`jsonval`

        # print json error mesage
        echo "$value"
        exit 1
    else
        # Node successfull added
        prop='id'
        value=`jsonval`
        echo ""
        echo "==========================================================================="
        echo ""
        echo "Congratulations, your node was added to MuninMX. You can view metrics for this node at:"
        echo "$muninmxurl/view.php?nid=$value"
    fi
else
    echo "Installation aborted"
fi
echo ""

exit 0
