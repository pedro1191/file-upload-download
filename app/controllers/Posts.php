<?php
  class Posts extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }

      $this->postModel = $this->model('Post');
      $this->userModel = $this->model('User');
      $this->logger = new Logger(__FILE__);
    }

    public function index(){
      $this->logger->debug(__METHOD__ . " started");

      $q = '';
      $offset = 0;

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $q = isset($_POST['q']) ? $_POST['q'] : $q;
        $offset = (isset($_POST['offset']) && filter_var($_POST['offset'], FILTER_VALIDATE_INT) && ($_POST['offset'] >= 0)) ? $_POST['offset'] : $offset;
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");
      }
      // Get posts
      $posts = $this->postModel->getPosts($q, $offset);

      //prepare data
      $pages_mod = $posts[1]%RESULTSPERPAGE;
      $pages_div = intdiv($posts[1], RESULTSPERPAGE);
      $number_of_pages = ($pages_mod == 0) ? $pages_div : ++$pages_div;
      $prev_page = $offset - 1;
      $next_page = $offset + 1;
      $start = 0;
      $end = $number_of_pages;
      $max_number_of_pages = (MAXNUMBEROFLINKS * 2);

      if ($number_of_pages > $max_number_of_pages) {
        $this->logger->debug(__METHOD__ . " creating pagination for results");

        $start = (($offset - MAXNUMBEROFLINKS) < 0) ? $start : ($offset - MAXNUMBEROFLINKS);
        $end = (($offset + MAXNUMBEROFLINKS) > $number_of_pages) ? $end : ($offset + MAXNUMBEROFLINKS);

        while (($end - $start) < $max_number_of_pages && $end < $number_of_pages) {
          $end++;
        }

        while (($end - $start) < $max_number_of_pages && $start > 0) {
          $start--;
        }
      }
      $data = [
        'posts' => $posts[0],
        'total' => $posts[1],
        'query' => $q,
        'number_of_pages' => $number_of_pages,
        'start' => $start,
        'end' => $end,
        'prev_page' => ($prev_page < 0) ? null : $prev_page,
        'current_page' => $offset,
        'next_page' => ($next_page >= $number_of_pages) ? null : $next_page
      ];

      $this->view('posts/index', $data);
    }

    public function add(){
      $this->logger->debug(__METHOD__ . " started");

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'file' => isset($_FILES['file']) ? $_FILES['file'] : null,
          'user_id' => $_SESSION['user_id'],
          'file_err' => ''
        ];

        // Validate data
        if($data['file'] == null || ($data['file']['size'] == 0 && ($data['file']['error'] == UPLOAD_ERR_OK || $data['file']['error'] == UPLOAD_ERR_NO_FILE))){
          $data['file_err'] = 'File is mandatory';
        } elseif ($data['file']['error'] != 0) { // Anything else than UPLOAD_ERR_OK or UPLOAD_ERR_NO_FILE...
          $this->logger->error(__METHOD__ . " file error code {$data['file']['error']} was returned");
      	  $data['file_err'] = 'An error occurred trying to send the file. Try again';
      	}

        // Make sure no errors
        if(empty($data['file_err'])){
          // Validated
          if($this->postModel->addPost($data)){
            $this->logger->info(__METHOD__ . " file {$data['file']['name']} was successfully added");
            flash('post_message', 'File Added');
            redirect('posts');
          } else {
            $this->logger->error(__METHOD__ . " error on adding file {$data['file']['name']}");
            flash('post_message', 'Oops! Something went wrong trying to add the file', 'alert alert-danger');
            redirect('posts/add');
          }
        } else {
          // Load view with errors
          $this->view('posts/add', $data);
        }
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        $data = [
          'file' => null
        ];
  
        $this->view('posts/add', $data);
      }
    }

    public function show(){
      $this->logger->debug(__METHOD__ . " started");

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id =  isset($_POST['id']) ? $_POST['id'] : '';
        $post = $this->postModel->getPostById($id);

        if($post == null) {
          $this->logger->error(__METHOD__ . " file {$id} was not found");
        } else {
          $this->logger->info(__METHOD__ . " file {$id} was found and will be shown");
        }

        $data = [
          'post' => $post
        ];
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        $data = [
          'post' => null
        ];
      }
      $this->view('posts/show', $data);
    }

    public function delete(){
      $this->logger->debug(__METHOD__ . " started");

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        // Get existing post from model
        $post = $this->postModel->getPostById($id);

        $error = false;

        if($post != null) {
          if($this->postModel->deletePost($id)){
            $this->logger->info(__METHOD__ . " file {$id} was successfully removed");
            flash('post_message', 'File Removed');
            redirect('posts');
          } else {
            $this->logger->error(__METHOD__ . " error on removing file {$id}");
            $error = true;
          }
        } else {
          $this->logger->error(__METHOD__ . " file {$id} was not found");
          $error = true;
        }
        if ($error) {
          flash('post_message', 'Oops! Something went wrong trying to delete the file', 'alert alert-danger');
          redirect('posts/show');
        }
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        redirect('posts');
      }
    }

    public function download() {
      $this->logger->debug(__METHOD__ . " started");
      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        // Get existing post from model
        $post = $this->postModel->getPostById($id);

        if($post != null) {
          $this->logger->info(__METHOD__ . " file {$id} was found and will be downloaded");
          $this->postModel->downloadPost($post);
        } else {
          $this->logger->error(__METHOD__ . " file {$id} was not found");
          flash('post_message', 'Oops! Something went wrong trying to download the file', 'alert alert-danger');
          redirect('posts');
        }
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        redirect('posts');
      }
    }
  }