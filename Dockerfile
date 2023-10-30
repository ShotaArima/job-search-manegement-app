From ubuntu:22.04

# # rev 22b

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update                \
 && apt-get -y upgrade            \
 && apt-get install -y tzdata     \
 && ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
 && dpkg-reconfigure -f noninteractive tzdata \
 && apt-get install -y sudo       \
        curl                      \
        openssh-server            \
        nginx                     \
        supervisor                \
        mysql-server              \
        php-fpm                   \
        php-mysql                 \
        vim-tiny less grep        \
 && apt-get clean

# # add user sspuser/ssppass, and add him to sudo group, and chsh

# substitute 0750 => 0755 default homedir permission
RUN perl -i -pe 's/^HOME\_MODE\s\d+/HOME_MODE 0755/' /etc/login.defs && \
    useradd -D -s /bin/bash                                          && \
    useradd -m sspuser                                               && \
    gpasswd -a sspuser sudo
RUN echo 'sspuser:ssppass' |chpasswd
RUN sudo -u sspuser mkdir /home/sspuser/public_html
# RUN chsh -s /bin/bash sspuser

# # sshd
RUN test -e /var/run/sshd || install -m 755 -o root -g root -d /var/run/sshd
# # modify /etc/ssh/sshd_config 
#     (from) #PasswordAuthentication yes  (to) PasswordAuthentication yes 
RUN perl -i -pe 's/^\s*\#\s*(PasswordAuthentication\s+yes)/$1/' /etc/ssh/sshd_config

# # php-fpm
RUN mkdir -p /var/run/php

# # modify the following line in /etc/php/7.2/apache2/php.ini
#      (begin) display_errors = Off
#      (end)   display_errors = On
RUN perl -i -pe 's/^(\s*display_errors\s*\=\s*)Off/$1On/;' /etc/php/8.1/fpm/php.ini

# # mysql
# RUN test -e /var/run/mysqld || install -m 755 -o mysql -g root -d /var/run/mysqld

RUN {  \
    echo ''                      ; \
    echo '[mysqld]'              ; \
    echo 'skip-name-resolve'     ; \
    } >>/etc/mysql/my.cnf

# passwd set up   / dbpass
RUN service mysql start && sleep 12 && \
    mysqladmin -u root password 'dbpass' && \
    (echo 'create user root@"%" identified by "dbpass"; grant all privileges on *.* to root@"%",root@"localhost" with grant option; flush privileges;' | mysql -u root -pdbpass) && \
    service mysql stop

# nginx setting (/etc/nginx/sites-available/default) from/to
#    index index.html index.htm index.nginx-debian.html;
#    index index.html index.htm index.php index.nginx-debian.html;
# and insert lines after 'location / {}'
RUN perl -i -pe ' s/^\sindex\s+index.html.*\;/ index index.html index.htm index.php index.nginx-debian.html;/x;  \
$posf++ if /^\s*location \s+ \/ \s* \{ /x;                 \
if ($posf == 1 and /^\s*\}/) { $_ .= join (qq{\n},q!!     \
,q!location ~ /\.ht {!                                     \
,q!    deny all;!                                          \
,q!}!                                                      \
,q!location ~ ^/~([^/]+)/(.+\.php)$ {!                     \
,q!    alias /home/$1/public_html/$2;!                     \
,q!    include snippets/fastcgi-php.conf;!                 \
,q!    fastcgi_param SCRIPT_FILENAME $request_filename;!   \
,q!    fastcgi_pass unix:/run/php/php8.1-fpm.sock;!        \
,q!}!                                                      \
,q!location ~ \.php$ {!                                    \
,q!    include snippets/fastcgi-php.conf;!                 \
,q!    fastcgi_pass unix:/run/php/php8.1-fpm.sock;!        \
,q!}!                                                      \
,q!location ~ ^/~(.+?)(/.*)?$ {!                           \
,q!    alias /home/$1/public_html$2;!                      \
,q!    autoindex on;!                                      \
,q!}!                                                      \
,q!! ); $posf++;}                                         \
' /etc/nginx/sites-available/default


# (/etc/nginx/sites-available/default) insert following lines below location / {}
################
# location ~ /\.ht {
#     deny all;
# }
# location ~ ^/~([^/]+)/(.+\.php)$ {
#     alias /home/$1/public_html/$2;
#     include snippets/fastcgi-php.conf;
#     fastcgi_param SCRIPT_FILENAME $request_filename;
# #     fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
# #     fastcgi_param SCRIPT_FILENAME /home/sspuser/public_html/test.php;
# #     fastcgi_param SCRIPT_FILENAME /var/www/html/test.php;
#     fastcgi_pass unix:/run/php/php8.1-fpm.sock;
# #     return 200 "Hi404b:$request_filename::$document_root:$fastcgi_script_name:";
# }
# location ~ \.php$ {
#     include snippets/fastcgi-php.conf;
#     fastcgi_pass unix:/run/php/php8.1-fpm.sock;
# }
# location ~ ^/~(.+?)(/.*)?$ {
#     alias /home/$1/public_html$2;
#     autoindex on;
# }
################

# (/etc/nginx/snippets/fastcgi-php.conf) from/to
#     try_files $fastcgi_script_name =404;
#     if (!-f $request_filename) { return 404; }
RUN perl -i -pe ' if (/^\s*try\_files\s+\$fastcgi\_script\_name\s*\=404\;/) { \
  $_ = q:if (!-f $request_filename) { return 404; }: .qq{\n} }' \
  /etc/nginx/snippets/fastcgi-php.conf





# # supervisor (with nginx sshd mysqld php)
RUN {  \
    echo '[supervisord]'                    ; \
    echo 'nodaemon=true'                    ; \
    echo ''                                 ; \
    echo '[program:sshd]'                   ; \
    echo 'command=/usr/sbin/sshd -D'        ; \
    echo ''                                 ; \
    echo '[program:mysqld]'                 ; \
    echo 'command=/usr/bin/mysqld_safe'     ; \
    echo ''                                 ; \
    echo '[program:php-fpm]'                ; \
    echo 'command=/usr/sbin/php-fpm8.1 -F'  ; \
    echo 'autostart=true'                   ; \
    echo 'autorestart=unexpected'           ; \
    echo 'exitcodes=0'                      ; \
    echo ''                                 ; \
    echo '[program:nginx]'                  ; \
    echo 'command=/usr/sbin/nginx -c /etc/nginx/nginx.conf'  ; \
    echo 'process_name=%(program_name)s'    ; \
    echo 'numprocs=1'                       ; \
    echo 'stopsignal=QUIT'                  ; \
    } >  /etc/supervisor/conf.d/supervisord.conf

# # Expose ports.  22,80 (no 3306)
EXPOSE 22 80

CMD ["/usr/bin/supervisord"]


# # docker build --no-cache -t sspi .
# # docker create -p 127.0.0.1:10800:80 -p 127.0.0.1:10220:22 --name sspc sspi
# # docker start sspc

# # docker exec -it sspc bash
# # docker start sspc
# # docker ps -a
# # docker rm sspc
# # docker images
# # docker rmi sspi
# # docker cp

# # end of file
