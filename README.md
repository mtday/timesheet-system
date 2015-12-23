# Timesheet System

This PHP-based web application provides a simple timesheet system suitable for small businesses to manage hours billed
across multiple contracts or jobs. This system provides the necessary features to remain compliant with the Defense
Contract Audit Agency (DCAA) requirements for time management.

### Technologies

The timesheet system is intended for hosting via the Apache HTTPD web server, and makes use of PHP for dynamic web page
generation using the Zend Application Framework (http://www.zendframework.com). The application stores all data in a
MySQL database back-end, and uses the ExtJS framework for JavaScript-based user interface components. As an Apache,
MySQL, and PHP application, this system should be deployable on Linux (a LAMP stack) or Windows (a WAMP stack), as well
as MacOS.


### Installation

The installation process will involve these steps:

  - Downloading the Timesheet System
  - Installing and Configuring Apache HTTPD
  - Installing and Configuring MySQL
  - Configuring the Timesheet System
  
This section provides an overview of these steps.


###### Downloading the Timesheet System

The first step in the installation process is to download the Timesheet System. The files contained in this git
repository represent the full distribution of this system, so all you need to do is use the `git` command line to clone
this repository onto your system where the web site will be hosted. There is a lot of documentation on how to install
git on your host operating system of choice, so follow that documentation to make sure it is installed. Once installed,
the following commands can be used to download the Timesheet System and place the files in the /opt/timesheet-system
directory. Note that the /opt/timesheet-system directory is used as an example throughout this documentation, but that
location is not a strict requirement.

    [mday@mday ~]$ git clone https://github.com/mtday/timesheet-system.git /opt/timesheet-system
    Cloning into '/opt/timesheet-system'...
    remote: Counting objects: 7348, done.
    remote: Compressing objects: 100% (4978/4978), done.
    remote: Total 7348 (delta 2204), reused 7329 (delta 2188), pack-reused 0
    Receiving objects: 100% (7348/7348), 24.34 MiB | 5.22 MiB/s, done.
    Resolving deltas: 100% (2204/2204), done.
    Checking connectivity... done.
    [mday@mday ~]$   

We will come back to the installation directory later to do more configuration.


###### Installing and Configuring Apache HTTPD

Apache HTTPD is a widely used web server that is relatively easy to install and configure. There is a lot of
documentation readily available that describes how to install Apache on your host operating system of choice. Find a
suitable tutorial and step through the process until you have Apache up and running and accessible.

For the timesheet system to work correctly within the installed Apache, there are two additional modules that will need
to be turned on - the rewrite module and the php module. Update the Apache httpd.conf file and uncomment (remove the `#`
from the beginning) the lines that look like these:

    LoadModule rewrite_module libexec/apache2/mod_rewrite.so
    LoadModule php5_module libexec/apache2/libphp5.so

Additionally, a section will need to be added to provide access to the timesheet system files. There are a couple ways
to do this, but the easiest is using virtual hosts. The first step is to update the Apache httpd.conf file and turn
on virtual hosts by uncommenting (removing the `#` at the beginning) the line that includes the virtual host
configuration file, something like this:

    Include /private/etc/apache2/extra/httpd-vhosts.conf
    
The referenced httpd-vhosts.conf file will need to be updated to specify a new VirtualHost block that will point to
the timesheet system source code. An example configuration of this file looks like this:

    <VirtualHost *:80>
        DocumentRoot "/opt/timesheet-system"
        ErrorLog "/opt/timesheet-system/zendapp/logs/timesheet-system-error_log"
        CustomLog "/opt/timesheet-system/zendapp/logs/timesheet-system-access_log" common
    
        <Directory "/opt/timesheet-system">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>

Note that the above configuration references the `/opt/timesheet-system` directory in a couple places. This is the
location where the timesheet system has been cloned. If you did not use `/opt/timesheet-system`, you will need to update
your virtual host configuration to use the directory you chose.

Restart Apache to pick up the configuration changes that have been made.


###### Installing and Configuring MySQL

MySQL is a widely used relational database system that is relatively easy to install and configure. There is a lot of
documentation readily available that describes how to install MySQL on your host operating system of choice. Find a
suitable tutorial and step through the process until you have MySQL up and running and accessible through the MySQL
shell.

TODO

Edit the initialize.sql file to set up an initial user, the administrative contracts, and the first pay period.

TODO

  create database timesheetsystem_db;
  create user timesheetsystem_db identified by 'timesheetsystem_db';
  grant all on timesheetsystem_db.* to timesheetsystem_db@localhost identified by 'timesheetsystem_db';
  use timesheetsystem_db;
  source /opt/timesheet-system/zendapp/database/db.sql
  source /opt/timesheet-system/zendapp/database/initialize.sql
  quit



###### Configuring the Timesheet System

TODO

File is /opt/timesheet-system/zendapp/config/config.ini

Database configuration
Email server configuration
QuickBooks configuration

Create file /opt/timesheet-system/zendapp/logs/app.log and chmod 777 that file

Use cron to turn on the /opt/timesheet-system/zendapp/cron/ReminderEmails.php script


### QuickBooks Integration

TODO


