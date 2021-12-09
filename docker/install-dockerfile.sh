cp repositories /etc/apk/repositories
apk add docker
addgroup username docker
rc-update add docker boot
service docker start
reboot