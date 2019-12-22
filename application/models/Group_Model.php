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

    if(is_array($group_name)){
      
      $string = '';
      
      for($i = 0; $i<count($group_name); $i++){
        $string .= '"';
        $string .= $group_name[$i];
        $string .= '" OR name=';
      }
      
      $string = substr_replace($string, '', -9);

      $sql = 'SELECT id FROM groups WHERE name=' . $string;

    }
    else
    {
      $sql = 'SELECT id FROM groups WHERE name="' . $group_name . '"';
    }

    $query  = $this->db->query($sql);
    $result = $query->result();
    
    if($query->num_rows() == 1)
    {
      return $result[0]->id;
    }
    elseif($query->num_rows() > 1)
    {
      $group_id = [];
      foreach($query->result_array() as $row):
        array_push($group_id, $row['id']);
      endforeach;
      return $group_id;  
    }
    else{
      return false;
    }
  }

  public function getUserTypes(){
    $sql = 'SELECT DISTINCT name FROM groups WHERE description = "Tipo de Utilizador"';
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