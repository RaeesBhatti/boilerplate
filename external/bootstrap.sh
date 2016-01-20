#!/bin/bash
print "\n" | add-apt-repository ppa:nginx/development
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449
add-apt-repository "deb http://dl.hhvm.com/ubuntu $(lsb_release -sc) main"
apt-get update
apt-get install hhvm nginx -y --force-yes
sed -i 's/user www-data;/user vagrant;/' /etc/nginx/nginx.conf
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
sudo apt-get install mysql-server -y --force-yes
update-rc.d hhvm defaults
sed -i 's/hhvm.server.port = 9000/hhvm.server.file_socket = \/var\/run\/hhvm\/hhvm.sock/' /etc/hhvm/server.ini
echo 'hhvm.hack.lang.look_for_typechecker = false' >> /etc/hhvm/php.ini
echo 'expose_php = false' >> /etc/hhvm/php.ini
usermod -a -G www-data vagrant
service hhvm restart
ln -s /vagrant/external/app.local /etc/nginx/conf.d/app.conf
service nginx restart
