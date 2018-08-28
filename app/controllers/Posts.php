<?php
  class Posts extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }

      $this->postModel = $this->model('Post');
      $this->userModel = $this->model('User');
    }

    public function index(){
      // Get posts
      $posts = $this->postModel->getPosts();

      $data = [
        'posts' => $posts
      ];

      $this->view('posts/index', $data);
    }

    public function add(){
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
      	  $data['file_err'] = 'An error occurred trying to send the file. Try again';
      	}

        // Make sure no errors
        if(empty($data['file_err'])){
          // Validated
          if($this->postModel->addPost($data)){
            flash('post_message', 'File Added');
            redirect('posts');
          } else {
            flash('post_message', 'Oops! Something went wrong trying to add the file', 'alert alert-danger');
            redirect('posts/add');
          }
        } else {
          // Load view with errors
          $this->view('posts/add', $data);
        }
      } else {
        $data = [
          'file' => null
        ];
  
        $this->view('posts/add', $data);
      }
    }

    public function show(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id =  isset($_POST['id']) ? $_POST['id'] : '';
        $post = $this->postModel->getPostById($id);

        $data = [
          'post' => $post
        ];
      } else {
        $data = [
          'post' => null
        ];
      }
      $this->view('posts/show', $data);
    }

    public function delete(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        // Get existing post from model
        $post = $this->postModel->getPostById($id);

        $error = false;

        if($post != null) {
          if($this->postModel->deletePost($id)){
            flash('post_message', 'File Removed');
            redirect('posts');
          } else {
            $error = true;
          }
        } else {
          $error = true;
        }
        if ($error) {
          flash('post_message', 'Oops! Something went wrong trying to delete the file', 'alert alert-danger');
          redirect('posts/show');
        }
      } else {
        redirect('posts');
      }
    }

    public function download() {
      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST array
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        // Get existing post from model
        $post = $this->postModel->getPostById($id);

        if($post != null) {
          $this->postModel->downloadPost($post);
        } else {
          flash('post_message', 'Oops! Something went wrong trying to download the file', 'alert alert-danger');
          redirect('posts');
        }
      } else {
        redirect('posts');
      }
    }
  }