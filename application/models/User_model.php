<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model 
{
  public function __construct()
  {
    $this->load->database();
  }

  public function getAllUsers()
  {
    $sql    = "SELECT id, name, username, email, phone, active FROM users";
    $query  = $this->db->query($sql);
    
    if($query->num_rows() > 0)
    {
      return $query->result();
    }
    else
    {
      return FALSE;
    }
  }

  public function addNewUser()
  {
    $this->ion_auth->register($username, $password, $email, $data);
  }

}

?>
