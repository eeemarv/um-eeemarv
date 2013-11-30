Multi-Site
-----------

The /vendor and /scr directories can be shared with more websites. The /app and /web directories are site-specific.

Recommanded setup:


	+--/base--+--/.git
	|         +--/bin
	|	      +--/web
	|         +--/app
	|         +--/scr
	|         +--/vendor
	|         +--.gitignore
	|         +--composer.phar
	|         +--composer.json
	|         +--composer.lock
	|
	+--/model--+--/web
	|          +--/app
	|          +--/scr  --> symlink ../../base/scr
	|          +--/vendor --> symlink ../../base/vendor
	|
	+--/site1--+--/web --> point your server  (contains all css, downloadable files, images, scripts)
	|          +--/app --> all configuration here + cache + logs + site specific custom templates, translations
	|          +--/scr  --> symlink ../../base/scr
	|          +--/vendor --> symlink ../../base/vendor
	|
	+--/site2-- etc. Copy for every new site the /model directory
	|
	+--
	
	
	
