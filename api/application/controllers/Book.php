<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('book_model');
    }

    public function index($_key='all')
    {
        $result = $this->book_model->route($_key);
        echo json_encode($result);
    }

}
