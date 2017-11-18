<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_Type extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('task_type_model');
    }

    public function index($_key='all')
    {
        $result = $this->task_type_model->route($_key);
        echo json_encode($result);
    }

}
