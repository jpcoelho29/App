<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

  public $data = [];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_Model');
    $this->load->library( 'form_validation');
  }
  
  public function index()
  {
     // Check if user has permission. Only administrators can view this!
     if (!$this->ion_auth->is_admin() || !$this->ion_auth->logged_in())
     {
       redirect('dashboard');
     }
     $this->data['title'] = 'Lista de Utilizadores';
     $this->data['users'] = $this->ion_auth->users()->result();
 
     foreach ($this->data['users'] as $k => $user)
     {
       $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
     }
 
     $this->render('users/users_list_view');
  }

  /**
	 * Log the user in
	 */
  public function login()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username', 'username', 'trim|required');
    $this->form_validation->set_rules('password', 'password', 'required');

    if ($this->form_validation->run() === TRUE)
    {
      // All the user data is correct
      // Check if user is trying to log in
      // Set "remember me" function to true

      $remember = TRUE;

      if ($this->ion_auth->login(
        $this->input->post('username'), 
        $this->input->post('password'), 
        $remember))
      {
        // If login was successful, redirect user to main menu
        redirect('dashboard','refresh');
      }
      else
      {
        // If login was un-successful, redirect user to login page
        redirect('login','refresh');
      }
    }
    else
    {
      // The user is not logging in, so display the login view      
      $this->load->helper('form');
      $this->render('login_form_view','login');
    } 
  }

  public function logout()
  {
    $this->ion_auth->logout();
    redirect('login','refresh');
  }
  
  public function getAllUsers()
  {
    $result = $this->User_Model->getAllUsers();
    echo json_encode($result);
  }

  public function addNewUser()
  {

    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    }

    $this->load->model('Group_Model');

    $msg['success'] = FALSE;
    $group_id = [];  
   
    $tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
    $this->data['identity_column'] = $identity_column;
    
    // Validate form input
    $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
    $this->form_validation->set_rules('name', 'Nome', 'trim');
    $this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email');
    $this->form_validation->set_rules('phone', 'Telefone', 'trim');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
    $this->form_validation->set_rules('password_confirm', 'Confirmar Password', 'required');

    if ($this->form_validation->run() === TRUE)
    {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $email    = strtolower($this->input->post('email'));
      $data = [
        'name'      => $this->input->post('name'),
        'phone'     => $this->input->post('phone'),
      ];
    }

    array_push($group_id,$this->Group_Model->getGroupId($this->input->post('group')));
    
    if ($this->form_validation->run() === TRUE && $this->ion_auth->register($username, $password, $email, $data, $group_id))
		{
      $msg['success'] = TRUE;
    }
    else
    {
      $msg['errors'] = validation_errors();
    }
    echo json_encode($msg);
  }

  public function editUserData()
  {
    $msg['success'] = FALSE;
    $user_id = $this->input->get('id');
    $user = $this->ion_auth->user($user_id)->result();

    if($user)
    {
      $msg['success'] = TRUE;
      $msg['user'] = $user; 
            
      if($this->ion_auth->in_group('members', $user_id) || $this->ion_auth->is_admin($user_id))
      {
        $msg['is_member'] = TRUE;
      }
    }    
    echo json_encode($msg);
  }

  public function updateUser()
  {
    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    }

    $this->load->model('Group_Model');

    $msg['success'] = FALSE;
    $user_id = $this->input->post('userId');
   
    $tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
    $this->data['identity_column'] = $identity_column;

    $this->form_validation->set_rules('name', 'Nome', 'trim|required');
    $this->form_validation->set_rules('phone', 'Telefone', 'trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');

    if ($this->input->post('password'))
    {
      $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
      $this->form_validation->set_rules('password_confirm', 'Confirmar Password', 'required');
    }
      
    if ($this->form_validation->run() === TRUE)
    {
      $data = [
        'name'  => $this->input->post('name'),
        'phone' => $this->input->post('phone'),
        'email' => $this->input->post('email'),
      ];

      if ($this->input->post('password'))
      {
        $data['password'] = $this->input->post('password');
      }

      if ($this->ion_auth->update($user_id, $data))
      {
        $msg['success'] = TRUE;
      }

    }else{
      $msg['errors'] = validation_errors();      
    }
    echo json_encode($msg);
  }

  public function editUserStatus()
  {
    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    } 
    
    $msg['success'] = FALSE;
    $user_id = $this->input->post('id');
    $user_status = $this->ion_auth->user($user_id)->result();
    $user_status = $user_status[0]->active;
    $logged_user = $this->ion_auth->user()->result();
    $logged_user = $logged_user[0]->id;

    // Users can't change their current status and admin's.

    if($logged_user == $user_id || $this->ion_auth->is_admin($user_id))
    {
      $msg['error'] = 'Não é possível alterar o estado deste utilizador';
    }
    else{
      if($user_status == 1)
      {
        // User is active -> Deactivate
        $activation = $this->ion_auth->deactivate($user_id);
        if($activation){
          $msg['success'] = TRUE;
          $msg['status']  = FALSE;
        }
      }elseif($user_status == 0)
      {
        // User is Deactivated -> Activate
        $activation = $this->ion_auth->activate($user_id);
        if($activation){
          $msg['success'] = TRUE;
          $msg['status']  = TRUE;
        }
      }
    }
    echo json_encode($msg);
  }

  public function userGroups(){

    $this->load->model('Group_Model');
    
    $msg['success'] = FALSE;

    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    }

    $user_id            = $this->input->get('id');
    $user_groups        = $this->ion_auth->get_users_groups($user_id)->result();
    $group_permissions  = $this->Group_Model->getGroupPermission();
    $user_types         = $this->Group_Model->getUserTypes();

    foreach($user_types as $type):
      if($this->ion_auth->in_group($type->name, $user_id)){
        $user_type = $type->name;
      }
    endforeach;
  
    if($group_permissions){
      $msg['success']           = TRUE;
      $msg['group_permissions'] = $group_permissions;
      $msg['user_groups']       = $user_groups; 
      $msg['user_types']        = $user_types;
      $msg['user_type']         = $user_type; 
    }    
    
    echo json_encode($msg);

  }

  public function updateUserGroups()
  {
    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    }

    $this->load->model('Group_Model');
    
    $msg['success'] = FALSE;

    $user_id = $this->input->post('user_id');

    $all_groups = $this->ion_auth->groups()->result();
    $user_groups = $this->ion_auth->get_users_groups($user_id)->result();
    $current_groups = [];
    foreach($user_groups as $group):
      array_push($current_groups, $group->name);  
    endforeach;
    $groups_add = [];
    $groups_remove = [];
    $post_groups = $this->input->post('group');

    foreach($post_groups as $group):
      if(!in_array($group, $current_groups))
      {
        array_push($groups_add, $group);
      }
    endforeach;

    foreach($all_groups as $group):
      if(!in_array($group->name, $post_groups) && in_array($group->name, $current_groups))
      {
        array_push($groups_remove, $group->name);  
      }
    endforeach;

    if(count($groups_add)>0){
      $groups_id_add = $this->Group_Model->getGroupId($groups_add);
      if($this->ion_auth->add_to_group($groups_id_add, $user_id)){
        $msg['add'] = $groups_id_add;
        $msg['success'] = TRUE;
      }
    }
    if(count($groups_remove)>0){
      $groups_id_remove = $this->Group_Model->getGroupId($groups_remove);
      if($this->ion_auth->remove_from_group($groups_id_remove, $user_id)){
        $msg['remove'] = $groups_id_remove;
        $msg['success'] = TRUE;
      }
    }
    echo json_encode($msg);
  }

  public function getUserTypes(){
    $msg['success'] = false;
    $this->load->model('Group_Model');
    $result = $this->Group_Model->getUserTypes();
    if($this->Group_Model->getUserTypes()){
      $msg['success'] = TRUE;
      $msg['user_types'] = $result;
    }
    echo json_encode($msg);    
  }

}