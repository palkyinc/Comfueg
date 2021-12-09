apk add ip6tables iptables
apk add -u awall
apk version awall
cp -RT /media/proyectos/Comfueg/docker/awall/ /etc/awall/
rc-update add iptables
rc-update add ipset
/etc/init.d/iptables save
awall activate
reboot