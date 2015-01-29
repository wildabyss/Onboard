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
     ^(ajax.*)|(.*\.(css|gif|png|jpg|jpeg|js|ico))$
     
  2. Rewrite the inclusion of the following regex to ajaxReroute.php
     ^ajax.*$
     
Configuration for IIS7 is included in the web.config file under www/ directory


Application Configuration:
-------

1. Install the required vendor packages using Composer, using 
   /composer.json and versions as specified in /composer.lock.
   
2. Create the following directories:
   /discussions
   /www/profile_pic_cache
   
3. Configure the web server as per above. Install MySQL.

4. Modify /database/propel.yml and /database/SQL/db_setup.sql for database username and password.

5. Setup the SQL database using /database/SQL/db_setup.sql and /database/SQL/onboard.sql.
   Note that onboard.sql can be replicated using /database/schema.xml from Propel.
   
6. Populate initial data with /database/SQL/initial_data.sql.
   Modify the values accordingly.
   
7. Set up Propel using /database/propel.yml.

8. Configure the web server to run server_startup.php on server startup.


Updating Database Schema:
-------

Use /database/schema.xml to update the database structure.

In the directory run:
  composer model:build      to generate the PHP database model
  composer sql:build        to generate the sql script
  
In the top directory run:
  composer dump-autoload    to generate the composer autoload