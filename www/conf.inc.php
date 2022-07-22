<?php

define("DBUSER", "root");
define("DBPWD", "password");
define("DBHOST", "database");
define("DBNAME", "mvcdocker2");
define("DBPORT", "3306");
define("DBDRIVER", "mysql");
define("DBPREFIXE", "esgi_");

define("DOMAIN" , "http://localhost");

//gmail credential
//define("SMTP_USERNAME", "vgcreator@gmail.com" );
//define("SMTP_PWD", "your-password");

//Postmark email marketing api
define("SMTP_USERNAME", "your-postmark-id" );
define("SMTP_PWD", "your-postmark-api-key");

if (extension_loaded('pdo_mysql')) {
    define("BUILDER" , 'App\Core\MySqlBuilder');
}
if (extension_loaded('postgres')) {
    define("BUILDER" , 'App\Core\PostgreSqlBuilder');
}

//Google aouth
define("GOOGLE_ID" , "your-google-id");
define("GOOGLE_SECRET" , "your-google-secret");


//ROLE VGCREATOR
define("VGCREATORID" , "?");
define("VGCREATORADMIN" , "?");
define("VGCREATORMEMBER" , "?");

//LES ROLES DE L'APPLICATION
define("IS_ADMIN" , '?');
define("IS_MEMBER" , '?');
define("IS_GUEST" , '?');
define("IS_EDITOR" , '?');
define("IS_MODERATOR" , '?');