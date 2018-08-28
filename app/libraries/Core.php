<?php
  /*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */
  class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];
    protected $logger;

    public function __construct(){
      $this->logger = new Logger(__FILE__);
      $this->logger->debug(__METHOD__ . " started");

      $url = $this->getUrl();

      // Look in controllers for first value
      if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
        // If exists, set as controller
        $this->currentController = ucwords($url[0]);
        $this->logger->debug(__METHOD__ . " controller {$this->currentController} was found");
        // Unset 0 Index
        unset($url[0]);
      } elseif(empty($url[0])) {
        $this->logger->debug(__METHOD__ . " controller was not set");
      } else {
        $this->logger->error(__METHOD__ . " controller {$url[0]} was not found");
      }

      // Require the controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of url
      if(isset($url[1]) && !empty($url[1])){
        // Check to see if method exists in controller
        if(method_exists($this->currentController, $url[1])){
          $this->currentMethod = $url[1];
          $this->logger->debug(__METHOD__ . " method {$this->currentMethod} was found");
          // Unset 1 index
          unset($url[1]);
        } else {
          $this->logger->error(__METHOD__ . " method {$url[1]} does not exist");
        }
      } else {
        $this->logger->debug(__METHOD__ . " method was not set");
      }

      // Get params
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      if(is_callable([$this->currentController, $this->currentMethod])) {
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
      } else {
        $this->logger->error(__METHOD__ . " callback was not valid");
        redirect('pages/index');
      }
    }

    public function getUrl(){
      $this->logger->debug(__METHOD__ . " started");

      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  } 
  
  