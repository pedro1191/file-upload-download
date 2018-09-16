<?php
  // DB Params
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', 'mysql');
  define('DB_NAME', 'fileuploaddownload');

  // Default time zone for dates
  date_default_timezone_set('America/Sao_Paulo');

  // App Root
  define('APPROOT', dirname(dirname(__FILE__)));
  // Public Root
  define('PUBLICROOT', dirname(APPROOT) . DIRECTORY_SEPARATOR . 'public');
  // URL Root
  define('URLROOT', 'http://192.168.0.125/php_projects/file-upload-download');
  // Site Name
  define('SITENAME', 'File Upload Download');
  // App Version
  define('APPVERSION', '1.2.1');
  // Logging
  define('LOGFILEPATH', PUBLICROOT . DIRECTORY_SEPARATOR . 'app_log');
  define('LOG_TRACE_ACTIVE', false);
  define('LOG_WARN_ACTIVE', false);
  define('LOG_INFO_ACTIVE', true);
  define('LOG_DEBUG_ACTIVE', false);
  define('LOG_ERROR_ACTIVE', true);
  // User Files Root
  define('DIRECTORYNAME', 'userFiles'); //This constant is used only in this file
  define('USERFILESROOT', PUBLICROOT . DIRECTORY_SEPARATOR . DIRECTORYNAME);
  // Search and pagination
  define('RESULTSPERPAGE', 10);
  define('MAXNUMBEROFLINKS', 3);
  // Max file size in bytes
  define('MAXFILESIZE', 5000000); //5MB