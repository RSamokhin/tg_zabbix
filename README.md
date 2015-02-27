* php_js_zabbix_rnet_mon - web interface for zabbix monitoring dashboard
* meved from https://github.com/RSamokhin/done/tree/master/php_js_zabbix_rnet_mon 
* now is more actual


1. yum install git
2. yum install nano
2. git config --global http.proxy http://ip:port
3. git config --global user.name "rsamokhin"
4. git config --global user.email poshliemail@googlemail.com
5. git clone https://github.com/RSamokhin/done.git
6. cp -r done/php_js_zabbix_rnet_mon /usr/share/zabbix
7. cd /usr/share/zabbix 
8. mv php_js_zabbix_rnet_mon custom/
9. nano /etc/php.ini (memory_limit = 1024M; post_max_size = 1024M)
10. chmod -R 777 /usr/share/zabbix/custom
11. nano /etc/crontab (add-> * * * * * root cd /usr/share/zabbix/custom && php srg.php)
