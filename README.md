#Telematics solution

*************************************************************************************************************************************************************************
					README - TELEMATICS (Environment settings)

*************************************************************************************************************************************************************************

OS - Ubuntu 16.04


    sudo apt-get update	// update ubuntu


*************************************************************************************************************************************************************************
Install Apache

*************************************************************************************************************************************************************************

https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04



    sudo apt-get install apache2	//install apache

    sudo ufw app list

sudo ufw app info "Apache Full"	//If you look at the Apache Full profile, it should show that it enables traffic to ports 80 and 443


http://your_server_IP_address	// Apache home page should load


*************************************************************************************************************************************************************************
Install PHP

*************************************************************************************************************************************************************************

    sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql

In most cases, we'll want to modify the way that Apache serves files when a directory is requested. Currently, if a user requests a directory from the server, 
Apache will first look for a file called index.html. We want to tell our web server to prefer PHP files, so we'll make Apache look for an index.php file first.

To do this, type this command to open the dir.conf file in a text editor with root privileges:

    sudo nano /etc/apache2/mods-enabled/dir.conf

It will look like this:

/etc/apache2/mods-enabled/dir.conf

					<IfModule mod_dir.c>
					    DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
					</IfModule>

We want to move the PHP index file highlighted above to the first position after the DirectoryIndex specification, like this:

/etc/apache2/mods-enabled/dir.conf

					<IfModule mod_dir.c>
					    DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
					</IfModule>

When you are finished, save and close the file by pressing Ctrl-X. You'll have to confirm the save by typing Y and then hit Enter to confirm the file save location.


    sudo systemctl restart apache2	//restart apache2

    sudo systemctl status apache2	//check status


sudo nano /var/www/html/info.php	//This will open a blank file. We want to put the following text, which is valid PHP code, inside the file:

					info.php
					<?php
					phpinfo();
					?>

http://your_server_IP_address/info.php 	//should load php home page

*************************************************************************************************************************************************************************
PHP Environment settings for making AWS API calls

*************************************************************************************************************************************************************************
https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_installation.html (prerequiste PHP version of over 5.0 and also installed php7.0-xml package)

    curl -sS https://getcomposer.org/installer | php

    php -d memory_limit=-1 composer.phar require aws/aws-sdk-php


    require '/home/ubuntu/vendor/autoload.php';	//include this path in all php scripts

*************************************************************************************************************************************************************************
Sample scripts to check API calls - We use only SNS component, run below sample script to check if SNS call from PHP script is working.

*************************************************************************************************************************************************************************

      <?php
      //Declaring AWS SNS client
      require '/home/ubuntu/vendor/autoload.php';
      use Aws\Sns\SnsClient;
      $client = SnsClient::factory(array(
          'region'  => 'us-east-1',
          'version' => '2010-03-31'
      ));
      $result = $client->publish([
          'Message' => 'Overspeeding',
          'TopicArn' => 'ARN NAME FROM SNS',
      ]);
      ?>

*************************************************************************************************************************************************************************
