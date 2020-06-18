<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host.example.com
    DocumentRoot "%path%"
    ServerName %domain%
    ServerAlias www.%domain%
    ErrorLog "logs/dummy-%domain%-error.log"
    CustomLog "logs/dummy-%domain%-access.log" common
	 <Directory "%path%">
    Options Indexes FollowSymLinks Includes ExecCGI

    #
    # AllowOverride controls what directives may be placed in .htaccess files.
    # It can be "All", "None", or any combination of the keywords:
    #   AllowOverride FileInfo AuthConfig Limit
    #
    AllowOverride All

    #
    # Controls who can get stuff from this server.
    #
    Require all granted
</Directory>
</VirtualHost>