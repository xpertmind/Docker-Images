<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace leantime\core;

class config {
    /* General */

    public $sitename = getenv('SITE_NAME', true) ?: "Leantime";                        //Name of your site, can be changed later
    public $language = getenv('LANGUAGE', true) ?: "en-us";                           //Default language
    public $logoPath = '/images/logo.svg';                //Default logo path, can be changed later
    public $printLogoURL = '/images/logo.jpg';            //Default logo URL use for printing (must be jpg or png format)
    public $appUrl = '';                                  //Base URL, trailing slash not needed
    public $appUrlRoot = '';                              //Base of application withotu trailing slash (used for cookies), e.g, /leantime
    public $defaultTheme = 'default';                     //Default theme
    public $primarycolor = '#1b75bb';                     //Primary Theme color
    public $secondarycolor = '#81B1A8';                   //Secondary Theme Color
    public $defaultTimezone = getenv('TIMEZONE', true) ?: "Europe/Zagreb";      //Set default timezone
    public $enableMenuType = false;                       //Enable to specifiy menu on aproject by project basis
    public $keepTheme = true;                             //Keep theme and language from previous user for login screen
    public $debug = 0;                                    //Debug flag


    /* Database */
    public $dbHost = getenv('DB_HOST', true) ?: "localhost";                         //Database host
    public $dbUser = getenv('DB_USER', true) ?: "";                                  //Database username
    public $dbPassword = getenv('DB_PASS', true) ?: "";                             //Database password
    public $dbDatabase = getenv('DB_NAME', true) ?: "leantime";                               //Database name
    public $dbPort =  getenv('DB_PORT', true) ?: "3306";                              //Database port

    /* Fileupload */
    public $userFilePath = 'userfiles/';                  //Local relative path to store uploaded files (if not using S3)
    public $dbBackupPath = 'backupdb/';                   //Local relative path to store backup files, need permission to write

    /* S3 configuration */
    public $useS3 = false;                                //Set to true if you want to use S3 instead of local files
    public $s3Key = '';                                   //S3 Key
    public $s3Secret = '';                                //S3 Secret
    public $s3Bucket = '';                                //Your S3 bucket
    public $s3UsePathStyleEndpoint = false;               // false => https://[bucket].[endpoint] ; true => https://[endpoint]/[bucket]
    public $s3Region = '';                                //S3 region
    public $s3FolderName = '';                            //Foldername within S3 (can be emtpy)
    public $s3EndPoint = null;                              //S3 EndPoint S3 Compatible (https://sfo2.digitaloceanspaces.com)

    /* Sessions */
    public $sessionpassword = getenv('SESSION_PASS', true) ?: "98r43uiqwfhashr0zr8urIUEH2038RZIPEfweiuhskn";  //Salting sessions. Replace with a strong password
    public $sessionExpiration = getenv('SESSION_TTL', true) ?: "28800";                   //How many seconds after inactivity should we logout?  28800seconds = 8hours

    /* Email */
    public $email = '';                                   //Return email address
    public $useSMTP = getenv('SMTP_USE', true) ?: false;                                //Use SMTP? If set to false, the default php mail() function will be used
    public $smtpHosts = getenv('SMTP_HOST', true) ?: "";                               //SMTP host
    public $smtpAuth = getenv('SMTP_AUTH', true) ?: "";                              //SMTP use user/password authentication
    public $smtpUsername = getenv('SMTP_USER', true) ?: "";                            //SMTP username
    public $smtpPassword = getenv('SMTP_PASS', true) ?: "";                            //SMTP password
    public $smtpAutoTLS = getenv('SMTP_AUTOTLS', true) ?: "";                           //SMTP Enable TLS encryption automatically if a server supports it
    public $smtpSecure = getenv('SMTP_SECPROTOCOL', true) ?: "";                              //SMTP Security protocol (usually one of: TLS, SSL, STARTTLS)
    public $smtpSSLNoverify = getenv('SMTP_SECNOVERIFY', true) ?: "";                      //SMTP Allow insecure SSL: Don't verify certificate, accept self-signed, etc.
    public $smtpPort = getenv('SMTP_PORT', true) ?: "25";                                //Port (usually one of 25, 465, 587, 2526)

    /* ldap default settings (can be changed in company settings) */
    public $useLdap = false;                              //Set to true if you want to use LDAP
    public $ldapType = 'OL';                              //Select the correct directory type. Currently Supported: OL - OpenLdap, AD - Active Directory
    public $ldapHost = '';                                //FQDN
    public $ldapPort = 389;                               //Default Port
    public $baseDn = '';                                  //Base DN, example: DC=example,DC=com
    public $ldapDn = '';                                  //Location of users, example: CN=users,DC=example,DC=com
    public $ldapUserDomain = '';                          //Domain after ldap, example @example.com
    public $bindUser = '';                                //ldap user that can search directory. (Should be read only)
    public $bindPassword = '';
    //Default ldap keys in your directory.
    //Works for OL
    public $ldapKeys = '{
        "username":"uid",
        "groups":"memberof",
        "email":"mail",
        "firstname":"displayname",
        "lastname":"",
        "phonenumber":""
        }';
    //For AD use
    /*
      public $ldapKeys = '{
      "username":"cn",
      "groups":"memberof",
      "email":"mail",
      "firstname":"givenname",
      "lastname":"sn",
      "phonenumber":"telephoneNumber"
      }';
     */


    //Default role assignments upon first login. (Optional) Can be updated in user settings for each user
    public $ldapLtGroupAssignments = '{
          "5": {
            "ltRole":"readonly",
            "ldapRole":""
          },
          "10": {
            "ltRole":"commenter",
            "ldapRole":""
          },
          "20": {
            "ltRole":"editor",
            "ldapRole":""
          },
          "30": {
            "ltRole":"manager",
            "ldapRole":""
          },
          "40": {
            "ltRole":"admin",
            "ldapRole":""
          },
          "50": {
            "ltRole":"owner",
            "ldapRole":"administrators"
          }
        }';
    public $ldapDefaultRoleKey = 20;           //Default Leantime Role on creation. (set to editor)

}
