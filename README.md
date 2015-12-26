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

The next step involves editing the `opt/timesheet-system/zendapp/database/initialize.sql` file to set up the correct
pay period information for your company. Find the lines in the file that look like these:

    INSERT INTO pay_periods (`start`, `end`, `type`) VALUES
    ('2015-12-01', '2015-12-15', 'semimonthly');

The start and end dates (shown as `2015-12-01` and `2015-12-15` in the above example) need to be changed to reflect
one of your company pay periods. It does not matter which pay period you choose to enter in here - it can be a year
old or a year into the future, but it has to match up with a real pay period in your company. These pay periods
define how which days show up in each timesheet, along with how payroll and the timesheet approval process is
managed. The last field indicates the type of pay periods your company uses, and is shown as `semimonthly` in the
above example. This value needs to accurately reflect the type of pay periods used by your company, and should be
one of these values:

  - weekly - the company pay periods are managed every week
  - biweekly - the company pay periods fall into two-week cycles
  - semimonthly - the company has exactly two pay periods every month

Note that you only need to specify one valid pay period in this file, and the system will automatically calculate
all the rest of the pay periods based on the one provided.

The remaining items in this file can all be modified through the timesheet system web interface, so no other changes
are required in this file.

The next step is to log into the mysql console as the root user and enter these commands to initialize the
database used by the timesheet system. First, create the database. The database name shown in this line is `ts_db`.
Remember this value because we will need it later during configuration as the value for `db.dbname`.

    create database ts_db;

Next, create the database user and password. The command below uses `ts_user` as the user name and `ts_pass` as the
password, but you can choose whichever values you prefer here. Whatever you choose, remember these values because they
will be needed later during configuration as the values for `db.username` and `db.password`.

    create user ts_user identified by 'ts_pass';

This next command grants the new user access to the database. Use the same database name, username, and password that
was specified in the previous two commands here also.

    grant all on ts_db.* to ts_user@localhost identified by 'ts_pass';

Switch to the database we just created.

    use ts_db;

Load the database schema using the database creation script.

    source /opt/timesheet-system/zendapp/database/db.sql

Load some initial data into the database.

    source /opt/timesheet-system/zendapp/database/initialize.sql

We are done executing commands in the MySQL shell, so exit the shell.

    quit


###### Configuring the Timesheet System

Now that the database has been configured, we are ready to configure the timesheet system. The timesheet system
configuration is located in the `/opt/timesheet-system/zendapp/config/config.ini` file. Open this file in a text
editor so the values can be updated.

The first thing to change is the database configuration properties, which all begin with "db." in the file. Make sure
the "db.dbname" value matches the database name you chose when configuring MySQL in the previous step. Also, make sure
the "db.username" and "db.password" values match the database user and password you created.

The email configuration properties are required in order for the timesheet system to be able to send emails. The
ability to send emails is required in order for the "Forgot Password" functionality to work correctly (which resets
user passwords and then emails them the new value). Also, the system can send reminder emails to employees that need to
enter their hours, and the system can notify supervisors when employees have completed their timesheets. The appropriate
values for these properties depends on the configuration of your mail server. Check with your mail system administrator
for the correct values to place in this section.

TODO: QuickBooks configuration

Depending on the configuration of your web server and the user account under which it runs, the timesheet system may
not have the ability to write to the system log file. Create the log file `/opt/timesheet-system/zendapp/logs/app.log`
and make it world-writable using commands like these:

    touch /opt/timesheet-system/zendapp/logs/app.log
    chmod 777 /opt/timesheet-system/zendapp/logs/app.log

If you want to allow the timesheet system to send reminder emails to users that have not entered timesheet hours, then
configure cron to invoke the `/opt/timesheet-system/zendapp/cron/ReminderEmails.php` script using the php interpreter.


### QuickBooks Integration

TODO


