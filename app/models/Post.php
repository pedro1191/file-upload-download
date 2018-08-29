<?php
  class Post {
    private $db;
    private $directoryPath;
    private $logger;

    public function __construct(){
      $this->db = new Database;
      $this->directoryPath = USERFILESROOT . DIRECTORY_SEPARATOR . $_SESSION['user_id'];
      $this->logger = new Logger(__FILE__);
    }

    public function getPosts($q, $offset){
      $this->logger->debug(__METHOD__ . " started");

      $results = array();
      $q = strtolower(trim($q));
      $qLen = strlen($q);

      try {
        $directory = new DirectoryIterator($this->directoryPath);
        foreach ($directory as $fileInfo) {
          if($fileInfo->isDot()) continue;
          if($fileInfo->isFile()) {
            if ($qLen > 0) {
              if(strpos(strtolower($fileInfo->getFilename()), $q) !== false) {
                array_push($results, new SplFileInfo($fileInfo->getPathname()));
              }
            } else {
              array_push($results, new SplFileInfo($fileInfo->getPathname()));
            }
          }
        }
      } catch(UnexpectedValueException $e) {
        $this->logger->error(__METHOD__ . " error trying to iterate over {$this->directoryPath}. Directory exists? It has enough privileges?");
      }
      usort($results, array('PostComparator', 'cmp'));

      $total = count($results);
      $offset = ($offset * RESULTSPERPAGE);
      $results = array_slice($results, $offset, RESULTSPERPAGE);

      return array($results, $total);
    }

    public function addPost($data){
      $this->logger->debug(__METHOD__ . " started");
      
      $fileName = basename($data['file']['name']);
      $target_file = $this->directoryPath . DIRECTORY_SEPARATOR . $fileName;

      if (!file_exists(USERFILESROOT)) {
        if(!mkdir(USERFILESROOT, 0777, true)) {
          $this->logger->error(__METHOD__ . " creating directory " . USERFILESROOT . " failed. It has enough privileges?");
        }
      }
      if (!file_exists($this->directoryPath)) {
        if(!mkdir($this->directoryPath, 0777, true)) {
          $this->logger->error(__METHOD__ . " creating directory {$this->directoryPath} failed. It has enough privileges?");
        }
      }

      // Execute
      if (move_uploaded_file($data['file']['tmp_name'], $target_file)) {
        $this->logger->info(__METHOD__ . " file {$fileName} was written successfully");
        return true;
      } else {
        $this->logger->error(__METHOD__ . " writing file {$fileName} failed. It has enough privileges?");
        return false;
      }
    }

    public function getPostById($id){
      $this->logger->debug(__METHOD__ . " started");

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
        $this->logger->error(__METHOD__ . " error trying to iterate over {$this->directoryPath}. Directory exists? It has enough privileges?");
      }

      return $result;
    }

    public function deletePost($id){
      $this->logger->debug(__METHOD__ . " started");

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
        $this->logger->error(__METHOD__ . " error trying to iterate over {$this->directoryPath}. Directory exists? It has enough privileges?");
      }

      // Execute
      if($removed){
        $this->logger->info(__METHOD__ . " file {$id} was deleted successfully");
        return true;
      } else {
        $this->logger->error(__METHOD__ . " deleting file {$id} failed. It has enough privileges?");
        return false;
      }
    }

    public function downloadPost($post) {
      $this->logger->debug(__METHOD__ . " started");
  
      $type = mime_content_type($post->getPathname());
      $fileName = $post->getFilename();
  
      // Send file headers
      header("Content-type: $type");
      header("Content-Disposition: attachment;filename=\"$fileName\"");
      header("Content-Transfer-Encoding: binary");
      header('Pragma: no-cache');
      header('Expires: 0');
      // Send the file contents.
      set_time_limit(0);
      if(readfile($post->getPathname())) {
        $this->logger->info(__METHOD__ . " file {$fileName} successfully downloaded");
      } else {
        $this->logger->error(__METHOD__ . " error reading file {$fileName}. It has enough privileges?");
      }
    }
  }