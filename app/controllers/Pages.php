<?php
  class Pages extends Controller {
    public function __construct(){
     
    }
    
    public function index(){
      if(isLoggedIn()){
        redirect('posts');
      }

      $data = [
        'title' => 'File Upload Download',
        'description' => 'Upload files to a server and download/delete these same files'
      ];
     
      $this->view('pages/index', $data);
    }

    public function about(){
      $data = [
        'title' => 'About Us',
        'description' => 'Simple system built on the TraversyMVC PHP framework'
      ];

      $this->view('pages/about', $data);
    }
  }