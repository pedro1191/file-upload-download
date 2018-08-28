<?php
  class Post {
    private $db;
    private $directoryPath;

    public function __construct(){
      $this->db = new Database;
      $this->directoryPath = USERFILESROOT . DIRECTORY_SEPARATOR . $_SESSION['user_id'];
    }

    public function getPosts(){
      $results = array();

      try {
        $directory = new DirectoryIterator($this->directoryPath);
        foreach ($directory as $fileInfo) {
          if($fileInfo->isDot()) continue;
          if($fileInfo->isFile()) array_push($results, new SplFileInfo($fileInfo->getPathname()));
        }
      } catch(UnexpectedValueException $e) {
        //directory cannot be found. Nothing to do
      }

      return $results;
    }

    public function addPost($data){
      $target_file = $this->directoryPath . DIRECTORY_SEPARATOR . basename($data['file']['name']);

      if (!file_exists(USERFILESROOT)) {
        mkdir(USERFILESROOT, 0777, true);
      }
      if (!file_exists($this->directoryPath)) {
        mkdir($this->directoryPath, 0777, true);
      }

      // Execute
      if (move_uploaded_file($data['file']['tmp_name'], $target_file)) {
        return true;
      } else {
        return false;
      }
    }

    public function getPostById($id){
      $result = null;

      try {
        $directory = new DirectoryIterator($this->directoryPath);
        foreach ($directory as $fileInfo) {
          if($fileInfo->isDot()) continue;
          if($fileInfo->getFilename() == $id) {
            $result = $fileInfo;
            break;
          }
        }
      } catch(UnexpectedValueException $e) {
        //directory cannot be found. Nothing to do
      }

      return $result;
    }

    public function deletePost($id){
      $removed = false;
      try {
        $directory = new DirectoryIterator($this->directoryPath);
        foreach ($directory as $fileInfo) {
          if($fileInfo->isDot()) continue;
          if($fileInfo->getFilename() == $id) {
            $removed = unlink($fileInfo->getPathname());
            break;
          }
        }
      } catch(UnexpectedValueException $e) {
        //directory cannot be found. Nothing to do
      }

      // Execute
      if($removed){
        return true;
      } else {
        return false;
      }
    }
  }