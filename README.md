# Avamar MAN reporting system - PHP 5.5 

This small script was created for monitoring the Avamar Tape out jobs which was not present in the software.

No database required simple send email to the users on success export

## System requirements
OS: RedHat 7.1 or CentOS 7.1
PHP: PHP 5.5.26

## Setting up the script
The settings are in the /include/base.php


### Servers config
Put the server names and the user credential to this array

```php
$servers = array(
			array("server"=>"fqd/ip","user"=>"","pass"=>""),
			array("server"=>"fqd/ip","user"=>"","pass"=>""),
		);


?>
```

### Email address settings

Add all users email address here you want to send the report
```php
$users = array(
		"example1@example.com","example3@example.com",
		);	
```

### SMTP settings

The mailer code will use the default smtp settings from the ini.
```php
ini_set("SMTP", "smtp.server.com");
```

 
