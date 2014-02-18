eeemarv
======

Eeemarv is open source web software for LETS groups. 

It is based on the [Symfony2](http://symfony.com/) php framework. 



Reporting an issue or a feature request.
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/marttii/eeemarv/issues).


Requirements
-------------

  * Git 1.6+
  * PHP 5.3.3+
  * php5-intl
  * [memcached](http://php.net/manual/en/memcached.installation.php) php extension 
  * [mailparse](http://pecl.php.net/package/mailparse) php extension ([wiki on installation](http://wiki.cerbweb.com/Installing_PHP_Mailparse))
  * phpunit 3.6+ (optional)
  * [composer](http://getcomposer.org/) (see Installation)
  * MySQL 5.5+
  * [LESS compiler](http://www.lesscss.org/) to compile the stylesheets of Twitter Bootstrap. To install LESS, you need NPM.  


Installation
--------------

### LESS

    npm install -g less

// todo node and node_paths params from assetic ->composer

### Git  

go to the directory where you want to install eeemarv and get the files with git (from the command line):

    git clone https://github.com/marttii/eeemarv.git

### Database 

Create an empty MySQL database. The parameters are needed in the next step. 

### Composer 

Composer is used to handle the dependencies. 
To get composer, run the following command in the eeemarv directory:

    curl -s http://getcomposer.org/installer | php

To install the dependencies, run

    php composer.phar install
    
You will be asked to fill in some parameters for your project (these are in the app/config/parameters.yml file):

  * database parameters
  * mailserver parameters
  * a public and a private key for reCaptcha -> get them at [recaptcha.net](http://recaptcha.net)
  * a random secret key for csrf protection, you can get one from [eeemarv.net/unique](http://eeemarv.net/unique) 
  * a random unique_id for your letsgroup, you can get one from [eeemarv.net/unique](http://eeemarv.net/unique) Never it change afterwards.
  * a code for your letsgroup, maximum length 8 characters. Use lowercase characters from the english alphabet - and numbers if you wish.
  * locale : code of the fallback locale (language + country) or the main locale. This can be a ISO639-1 two character language code, e.g. `en` for English OR language code extended with an underscore _ and ISO3166 Alpha-2 country code, e.g. `fr_FR` for French/France
  * locales : array of all locales supported i.g. `[ en, fr ]` all translations have to present (general + site-specific).
  * currency_rate : divider to display the value of your currency. Lets-seconds are used by eeemarv internally, 3600/hour. e.g. if your currency unit is worth 30/hour then currency_rate is 120 (30 = 3600/120)    
	
The name of your currency and your site-name are site specific translations, these you have to edit in `app/resources/translations/site.{locale-code}.yml`. Provide a file for every locale you support.

### Access by Web Browser

Only the /web folder should be accessible by the browser.
Keep the other folders (/app, /src, /vendor) outside the server root.

### Set up Permissions

The app/cache and app/logs directories must be writable both by the web server and the command line user. 
Here a command to try to determine the name of your webserver user:

    ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1

e.g. set permissions in Ubuntu with setfacl (whoami is your name, www-data is the name of your webserver user):

    sudo setfacl -R -m u:www-data:rwX -m u:whoami:rwX app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwX -m u:whoami:rwX app/cache app/logs

For other systems you might use chmod +a, see [Symfony2 installation](http://symfony.com/doc/2.3/book/installation.html) for details.


### Check your System Configuration

Execute the `check.php` script from the command line:

    php app/check.php

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/eeemarv/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

### Create Database Schema 

Run command 

    app/console doctrine:schema:update --force

### Database Fixtures

Run command

	app/console doctrine:fixtures:load

### Assetic 

run command `assetic:dump`  

    app/console assetic:dump



License
---------------
eeemarv is distributed under the MIT license. See the LICENSE file for the complete text.


Versioning
--------------

For transparency and insight into the release cycle, releases (from version 1.0.0) will be numbered with the follow format:

<major>.<minor>.<patch>

And constructed with the following guidelines:

  * Breaking backwards compatibility bumps the major
  * New additions without breaking backwards compatibility bumps the minor
  * Bug fixes and misc changes bump the patch

For more information on semantic versioning, please visit [http://semver.org/].



