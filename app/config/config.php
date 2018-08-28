<?php
  // DB Params
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', 'mysql');
  define('DB_NAME', 'fileuploaddownload');

  // App Root
  define('APPROOT', dirname(dirname(__FILE__)));
  // Public Root
  define('PUBLICROOT', dirname(APPROOT) . DIRECTORY_SEPARATOR . 'public');
  // URL Root
  define('URLROOT', 'http://192.168.0.125/file-upload-download');
  // Site Name
  define('SITENAME', 'File Upload Download');
  // App Version
  define('APPVERSION', '1.0.0');
  // Logging
  define('LOGFILEPATH', PUBLICROOT . DIRECTORY_SEPARATOR . 'app_log');
  define('LOG_TRACE_ACTIVE', false);
  define('LOG_WARN_ACTIVE', false);
  define('LOG_INFO_ACTIVE', true);
  define('LOG_DEBUG_ACTIVE', false);
  define('LOG_ERROR_ACTIVE', true);