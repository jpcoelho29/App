<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/header');
echo $the_view_content;
$this->load->view('templates/footer');
?>