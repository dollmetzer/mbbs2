mbbs2 (mobile micro bbs 2)
==========================

What is this?
-------------
This web application is a tiny messaging platform, focussed on private mail 
and closed forum functions. The intention was to build up a local and temporary 
communication platform for closed groups, e.g. for small exhibitions or 
congresses.

It's user interface is optimised for access via mobile devices and the program
code is lightweight enough to run on cheap and tiny devices, like the 
Raspberry Pi.

This second version is based on the symfony 5 framework-


Installation
------------
There are some Preconditions for an Installation of this software:

* Apache 2 Webserver
* PHP 7.2.5 or above
* Composer (PHP Dependency Manager)
* MySQL Database server and client
* GIT Version control system

Assuming your application root is /var/www/mbbs and your webroot points to 
/var/www/mbbs/public.

To install mbbs, type in the following commands:

    cd /var/www/mbbs
    git clone https://github.com/dollmetzer/mbbs.git ./
    php composer.phar install
    
To setup  your configuration, copy .env to .env.local and change at least 
APP_ENV and DATABASE_URL.

Make the following directories writeable for the web server:

* ~/var/

Set up the database (...tbd...)

    bin/console doctrine:database:create
    bin/console doctrine:schema:create
    bin/console doctrine:fixtures:load
    

Configuration
-------------
**Registration**

The following flags can be set in the .env file:

    REGISTER_SELF (default is false)
Boolean controls if guests can register for an account by themself.

    REGISTER_DOUBLE_OPT_IN (default is false)
Boolean controls if self registration (see above) needs a double opt-in via email.

    REGISTER_INVITATION (default is false)
Boolean controls if a user can invite another user. 
   

Development Hints
-----------------
- Set your IDE to PHP Version 7.4
- Use code style symfony2 