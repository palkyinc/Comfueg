cp /media/proyectos/Comfueg/docker/sshd_config /etc/ssh/sshd_config
adduser -g "Usuario Soporte" soporte
apk add sudo
echo '%wheel ALL=(ALL) ALL' > /etc/sudoers.d/wheel
adduser soporte wheel
/etc/init.d/sshd restart