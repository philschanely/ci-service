<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
    }

    public function index($_key='all')
    {
        $result = $this->category_model->route($_key);
        echo json_encode($result);
    }

}
