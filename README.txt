------ INSTALLATION ON APACHE ------
The content of this directory have to be placed directly in the DOCUMENT_ROOT folder of the Apache.
If you want to place the whole folder in the Apache, make sure you change the DOCUMENT_ROOT in the http.conf
file. After this restart your Apache-Server.

------ PHP CONFIGURATION ------
Note this application is written with the shortags of php. This is an option wich isnt enabled by default.
You can enable this option in the php.ini file located in the php folder on your Apache-Server. Open the file and set the value of "short_open_tag"
to "On". After this restart your Apache-Server.

------ DB SETUP -----
This application configures and creates the required database itself with the first request. Just make sure your
MySql-Server has a user-profile called "root" with all global permissions on the server and with no password set.
If you dont have an user like the described "root" or its named different on your server you have to change the 
"rootUser" and the "rootPassword" values in the db-array in the config.php file.

------ UPDATE THE CREATE-SCRIPT OF THE DB ------
If you want to update the backup / create-script of the db make sure when you generate the backup-script
with phpMyAdmin to check the  option "Add CREATE DATABASE / USE statement" and to uncheck the data-option
from the tables selection in the custom export settings.

------ CONFIGURATION ------
All the configurations can be made in the config.php file. But please note this config is globaly used in the app.
So changes in the config can cause major malfunctions in the application.

------ THIRD PARTY LIBRARIES ------
All rights reserved to the owners / creators of these libraries. The use of these libraries is inside the boundries 
of their licenses. All licenses are inlcuded in the licenses folder of this application.
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
        - js-datepicker v4.0.10 by The Qodesmith / included via CDN
            => https://www.npmjs.com/package/js-datepicker
    Backend:
        - CryptoLib v1 by IcyApril / downloaded from GitHub
            => https://github.com/IcyApril/CryptoLib

------- SECURITY ------
-   All the passwords are hashed with the ARGON2i algorythm before theyre stored as hash in the database.
-   Every form is on the client-side and the server-side validated (validate configurations via the config.php file).
    Also every form is equipped with an antiforgery-token wich is stored in the session and will be recreated on every site
    request to prevent CSRF-attacks.
-   The access on files from the client-side shouldn't be possible because of the routing configuration. But every folder is from
    access protected with an .htaccess file anyways.
-   All characters of a user input are sanitized / escaped before processing further to prevent XSS-attacks.
-   The remember-me cookie is created to be "unforgeable". The content of the cookie is splitted in three parts. The first part is the 
    user-id encoded as Base64. The second part is a ramdom generated token, hashed with ARGON2i algorythm. The third part is a control-hash
    generated from the first two content-parts by a private-key using the hmac-method and the sha256 algorythm. The cookie is per default 
    valid for seven days (changes can be made in the config.php file). After the creation the user-token and the control-hash are stored in
    the database to validate the cookie in the future. The private key is a 256 char long random string generated with the CriptoLib library.
-   Cookie theft isnt protected yet (a small protection exists because of the prevention of XSS-attacks). This requires a HTTPS-configuration 
    wich doesnt exist at the moment.