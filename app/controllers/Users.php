<?php
  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
      $this->logger = new Logger(__FILE__);
    }

    public function register(){
      $this->logger->debug(__METHOD__ . " started");

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
  
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data =[
          'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
          'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
          'password' => isset($_POST['password']) ? trim($_POST['password']) : '',
          'confirm_password' => isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
          $data['email_err'] = 'Please enter a valid email';
        } elseif($this->userModel->findUserByEmail($data['email'])){// Check email
            $data['email_err'] = 'Email is already taken';
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Password must be at least 6 characters';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm password';
        } elseif($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Validated
          
          // Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Register User
          if($this->userModel->register($data)){
            $this->logger->info(__METHOD__ . " user [{$data['name']}, {$data['email']}] was registered successfully");
            flash('register_success', 'You are registered and can log in');
            redirect('users/login');
          } else {
            $this->logger->error(__METHOD__ . " error trying to register user [{$data['name']}, {$data['email']}]");
            flash('register_error', 'Oops! Something went wrong trying to register you. Try again', 'alert alert-danger');
            redirect('users/register');
          }
        } else {
          // Load view with errors
          $this->view('users/register', $data);
        }
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/register', $data);
      }
    }

    public function login(){
      $this->logger->debug(__METHOD__ . " started");

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
          'password' => isset($_POST['password']) ? trim($_POST['password']) : '',
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
          $data['email_err'] = 'Please enter a valid email';
        } elseif($this->userModel->findUserByEmail($data['email'])){ // Check for user/email
          // User found
          $this->logger->debug(__METHOD__ . " {$data['email']} was found");
        } else {
          // User not found
          $this->logger->debug(__METHOD__ . " {$data['email']} was not found");
          $data['email_err'] = 'No user found';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);

          if($loggedInUser){
            // Create Session
            $this->createUserSession($loggedInUser);
            $this->logger->info(__METHOD__ . " user {$loggedInUser->id} was logged in successfully");
          } else {
            $data['password_err'] = 'Password incorrect';

            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
        }
      } else {
        $this->logger->debug(__METHOD__ . " request method {$_SERVER['REQUEST_METHOD']}");

        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('users/login', $data);
      }
    }

    public function createUserSession($user){
      $this->logger->debug(__METHOD__ . " started");

      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      $_SESSION['user_name'] = $user->name;
      redirect('posts');
    }

    public function logout(){
      $this->logger->info(__METHOD__ . " user {$_SESSION['user_id']} is logging out");

      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      session_destroy();
      redirect('users/login');
    }
  }