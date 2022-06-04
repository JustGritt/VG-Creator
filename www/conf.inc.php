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
define("SMTP_USERNAME", "vgcreator1@gmail.com" );
define("SMTP_PWD", "ESGI2021");


//Postmark email marketing api
//define("SMTP_USERNAME", "364a064d-be01-4096-97fd-1591c1132128" );
//define("SMTP_PWD", "364a064d-be01-4096-97fd-1591c1132128");
if (extension_loaded('pdo_mysql')) {
    define("BUILDER" , 'App\Core\MySqlBuilder');
}
if (extension_loaded('postgres')) {
    define("BUILDER" , 'App\Core\PostgreSqlBuilder');
}

//Google aouth
define("GOOGLE_ID" , "158468091523-k9rdagaqclul2r7vtk6evgv8tnen866i.apps.googleusercontent.com");
define("GOOGLE_SECRET" , "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");


//ROLE VGCREATOR
define("VGCREATORID" , "1");
define("VGCREATORADMIN" , "1");
define("VGCREATORMEMBER" , "2");

//LES ROLES DE L'APPLICATION
define("IS_ADMIN" , '1');
define("IS_MEMBER" , '2');
define("IS_GUEST" , '3');
define("IS_EDITOR" , '4');
define("IS_MODERATOR" , '5');