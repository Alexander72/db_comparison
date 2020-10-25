#!/bin/bash

echo "zend_extension=xdebug.so" > /etc/php/7.4/mods-available/xdebug.ini
echo "xdebug.profiler_enable=1" >> /etc/php/7.4/mods-available/xdebug.ini
echo "xdebug.remote_autostart=true" >> /etc/php/7.4/mods-available/xdebug.ini
echo "xdebug.remote_host=172.17.0.1" >> /etc/php/7.4/mods-available/xdebug.ini
