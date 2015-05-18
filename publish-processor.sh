#!/bin/bash
if [ -f /var/www/phantasos/app/config/parameters.yml ]
then
    cp /var/www/phantasos/app/config/parameters.yml app/config/
fi
if [ -f /var/www/phantasos/version.txt ]
then
    cp /var/www/phantasos/version.txt .
fi
a=`git log -1 --format="%H"`
b=`date`
c=`whoami`
echo "$a $b $c" >> version.txt
composer install
php app/console assets:install
php app/console assetic:dump
rm -rf app/cache/*
rm -rf app/logs/*
rm -rf app/sessions/*
sudo chown -R apache:apache .
sudo rsync -az --delete --exclude-from exclude.txt . /var/www/phantasos/
sudo chown -R `whoami`:`whoami` .
sudo systemctl restart phantasos-processor.service
