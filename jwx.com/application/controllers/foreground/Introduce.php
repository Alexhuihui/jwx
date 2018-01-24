<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Introduce extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('foreground/test.html');
    }

    public function central()
    {
        $this->load->view('foreground/central_article.html');
    }

    public function family()
    {
        $this->load->view('foreground/home_article.html');
    }

    public function project()
    {
        $this->load->view('foreground/machine_article.html');
    }

    public function example()
    {
        $this->load->view('foreground/case.html');
    }
}