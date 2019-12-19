<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Group_Model extends CI_Model 
{
  public function __construct()
  {
    $this->load->database();
  }

  public function getGroupId($group_name)
  {
    $sql = 'SELECT id FROM groups WHERE name="' . $group_name . '"';
    $query  = $this->db->query($sql);
    $result = $query->result();
    
    if($query->num_rows() >0)
    {
      return $result[0]->id;
    }
    else{
      return false;
    }
  }

  public function getUserType(){
    $sql = 'SELECT * FROM groups WHERE user_id = "'. $user_id .'"':
  }

  public function getGroupPermission(){
    $sql = 'SELECT DISTINCT description FROM groups WHERE description != "Tipo de Utilizador"';
    $query  = $this->db->query($sql);
    $result = $query->result();

    if($query->num_rows() >0)
    {
      return $result;
    }
    else{
      return false;
    } 
    
  }

}  