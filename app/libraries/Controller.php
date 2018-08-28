<?php
  /*
   * Base Controller
   * Loads the models and views
   */
  class Controller {
    protected $logger;

    // Load model
    public function model($model){
      $this->logger = new Logger(__FILE__);
      $this->logger->debug(__METHOD__ . " started");

      // Require model file
      if(file_exists('../app/models/' . $model . '.php')){
        $this->logger->debug(__METHOD__ . " model $model was found");
        require_once '../app/models/' . $model . '.php';
        // Instantiate model
        return new $model();
      } else {
        // Model does not exist
        $this->logger->error(__METHOD__ . " model $model was not found");
        die('Oops! Model was not found');
      }
    }

    // Load view
    public function view($view, $data = []){
      $this->logger = new Logger(__FILE__);
      $this->logger->debug(__METHOD__ . " started");

      // Check for view file
      if(file_exists('../app/views/' . $view . '.php')){
        $this->logger->debug(__METHOD__ . " view $view was found");
        require_once '../app/views/' . $view . '.php';
      } else {
        // View does not exist
        $this->logger->error(__METHOD__ . " view $view was not found");
        die('Oops! View was not found');
      }
    }
  }