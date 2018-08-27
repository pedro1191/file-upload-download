<?php
/**
 * Simple Logger class
 */
class Logger {
    private $fileAbsPath;

    public function __construct($fileAbsPath) {
        $this->fileAbsPath = $fileAbsPath;
    }

    public function trace($message) {
        if(LOG_TRACE_ACTIVE) {
            $this->createMessage($message, 'TRACE');
        }
    }

    public function warn($message) {
        if(LOG_WARN_ACTIVE) {
            $this->createMessage($message, 'WARN');
        }
    }

    public function info($message) {
        if(LOG_INFO_ACTIVE) {
            $this->createMessage($message, 'INFO');
        }
    }

    public function debug($message) {
        if(LOG_DEBUG_ACTIVE) {
            $this->createMessage($message, 'DEBUG');
        }
    }

    public function error($message) {
        if(LOG_ERROR_ACTIVE) {
            $this->createMessage($message, 'ERROR');
        }
    }

    private function createMessage($message, $level) {
        $date = date('d-M-Y H:i:s e');
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "Guest";
        $msg = "[{$date}] [{$level}] [User $user_id] [{$this->fileAbsPath}] $message" . PHP_EOL;
        error_log($msg, 3, LOGFILEPATH);
    }
} 
?>