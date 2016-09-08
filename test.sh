####!/bin/bash
echo "########### The server will reboot when the script is complete..."
echo "########### Changing to home dir..."
cd ~
#
####Update Server####
echo "########### Updating Ubuntu..."
sudo apt-get update -y
sudo apt-get upgrade -y
sudo apt-get install software-properties-common python-software-properties -y
sudo apt-get install build-essential libssl-dev libdb-dev libdb++-dev libboost-all-dev git libssl1.0.0-dbg -y
sudo apt-get install libdb-dev libdb++-dev libboost-all-dev libminiupnpc-dev libminiupnpc8 libevent-dev libcrypto++-dev libgmp3-dev -y
#
####Install Firewall####
echo "########### Firewall rules; allow 22,4320"
sudo apt-get install ufw -y
sudo ufw allow 80/tcp
sudo ufw allow 22/tcp
sudo ufw allow 4319/tcp
sudo ufw allow 4320/tcp
sudo ufw --force enable
#
####Install PHP5####
echo "########### Installing PHP5..."
sudo apt-get install php5 libapache2-mod-php5 -y
sudo service apache2 restart
#
####Install cURL####
echo "########### Installing cURL..."
sudo apt-get install php5-curl -y
sudo apache2ctl restart
#
####Create Swap File####
echo "########### Creating Swap..."
sudo dd if=/dev/zero of=/swapfile bs=1M count=512
sudo mkswap /swapfile
sudo swapon /swapfile
echo "/swapfile swap swap defaults 0 0" >> /etc/fstab
#
####Clone SPEC Github - Install specd####
echo "########### Adding ppa:SpecDevelopment/spec-wallet and installing specd"
sudo mkdir ~/.spec/
sudo apt-get install git -y
git clone https://github.com/SpecDevelopment/spec-wallet
cd spec-wallet/src/leveldb
chmod 755 build_detect_platform
sudo make libleveldb.a libmemenv.a
cd ..
sudo make -f makefile.unix
sudo strip specd
sudo mv specd /usr/bin/
#
####Create spec.conf####
echo "########### Creating config..."
cd ~
config=".spec/spec.conf"
touch $config
echo "listen=1" > $config
echo "server=1" >> $config
echo "daemon=1" >> $config
echo "port=4319" >> $config
echo "rpcport=4320" >> $config
echo "maxconnections=80" >> $config
randUser=`< /dev/urandom tr -dc A-Za-z0-9 | head -c30`
randPass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c30`
echo "rpcuser=$randUser" >> $config
echo "rpcpassword=$randPass" >> $config
#
####Autostart specd Upon Server Start####
echo "########### Setting up autostart (cron)"
crontab -l > tempcron
echo "@reboot specd -daemon" >> tempcron
crontab tempcron
rm tempcron
echo "########### Thanks to Max Kaye."
echo "########### Modified by SPEC Dev Team dev@speccoin.com"
echo "Rebooting..."
reboot
