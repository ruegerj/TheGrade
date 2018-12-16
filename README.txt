------ INSTALLATION ON APACHE ------
The content of this directory have to be placed directly in the DOCUMENT_ROOT folder of the Apache.
If you want to place the whole folder in the Apache, make sure you change the DOCUMENT_ROOT in the http.conf
file.

------ DB SETUP -----
This application configures and creates the required database itself with the first request. Just make sure youre 
MySql-Server has a user-profile called "root" with all global  permissions on the server and with no password set.
If you dont have an user like the described "root" or its named different on your server you have to change the 
"rootUser" and the "rootPassword" values in the config.php file.

------ UPDATE THE CREATE-SCRIPT OF THE DB ------
If you want to update the backup / create-script of the db make sure when you let it generate by phpMyAdmin to add
two lines after the transaction to the script:
-> CREATE DATABASE thegradedb;
-> USE thegradedb;

------ CONFIGURATION ------
All the configurations can be made in the config.php file. But please note this config is globaly used in the app.
So can changes in the config cause major malfunctions in the application.