Onboard
=======

Required Vendor Packages:
-------
1. Composer
2. Facebook PHP API
3. Google+ PHP API
4. Propel
5. Swiftmailer
6. Klein


Web Server Configuration:
-------

Must have the following rewrite rules implemented to enable Klein re-router:

  1. Rewrite the exclusion of the following regex to viewReroute.php
     ^(ajax.*)|(.*\.(css|gif|png|jpg|jpeg|js))$
     
  2. Rewrite the inclusion of the following regex to ajaxReroute.php
     ^ajax.*$
     
Configuration for IIS7 is included in the web.config file under www/ directory