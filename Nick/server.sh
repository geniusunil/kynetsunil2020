cd /root;
cat /proc/loadavg > work_files/server/load.txt;
free -m > work_files/server/ram.txt;
uptime > work_files/server/uptime.txt;
php -f server.php