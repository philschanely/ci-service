<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('task_model');
    }

    public function index($_key='all')
    {
        $result = $this->task_model->route($_key);
        echo json_encode($result);
    }

}
