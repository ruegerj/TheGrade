------ INSTALLATION ON APACHE ------
The content of this directory have to be placed directly in the DOCUMENT_ROOT folder of the Apache.
If you want to place the whole folder in the Apache, make sure you change the DOCUMENT_ROOT in the http.conf
file.

------ DB SETUP -----
This application configures and creates the required database itself with the first request. Just make sure your
MySql-Server has a user-profile called "root" with all global permissions on the server and with no password set.
If you dont have an user like the described "root" or its named different on your server you have to change the 
"rootUser" and the "rootPassword" values in the db-array in the config.php file.

------ UPDATE THE CREATE-SCRIPT OF THE DB ------
If you want to update the backup / create-script of the db make sure when you generate the backup-script
with phpMyAdmin to check the the option "Add CREATE DATABASE / USE statement" and to uncheck the data-option
from the tables selection in the custom export settings.

------ CONFIGURATION ------
All the configurations can be made in the config.php file. But please note this config is globaly used in the app.
So changes in the config can cause major malfunctions in the application.

------ THIRD PARTY LIBRARIES ------
All rights reserved to the owners / creators of these libraries. The use of these libraries is inside the boundries 
of their licenses.
Libraries used:
    Frontend: 
        - Bootstrap v4.1 by Twitter / included via CDN
            => https://getbootstrap.com/
        - fontawesome Free v5.6.1 by Fonticons Inc. / included via CDN 
            => https://fontawesome.com/
        - jQuery v3.3.1 by The jQuery Foundation / included via CDN
            => https://jquery.com/
        - popper.js v1.14.3 by FEDERICO ZIVOLO & CONTRIBUTORS / included via CDN
            => https://popper.js.org/
    Backend:
        - CryptoLib v1 by IcyApril / downloaded from GitHub
            => https://github.com/IcyApril/CryptoLib