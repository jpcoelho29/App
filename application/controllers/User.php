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

  public function create_user()
  {
    
    $this->data['title'] = 'Novo utilizador';

    if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
    }
    elseif (!$this->ion_auth->is_admin())
    {
      redirect('dashboard', 'refresh');
    }

    $tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
    $this->data['identity_column'] = $identity_column;
    
    // Validate form input
    $this->form_validation->set_rules('username', 'username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
    $this->form_validation->set_rules('name', 'name', 'trim');
    $this->form_validation->set_rules('email', 'email', 'trim|valid_email|is_unique[' . $tables['users'] . '.email]');
    $this->form_validation->set_rules('phone', 'phone', 'trim');
    $this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
    $this->form_validation->set_rules('password_confirm', 'password_confirm', 'required');
    
    if ($this->form_validation->run() === TRUE)
    {
      $email = strtolower($this->input->post('email'));
      $username =  $this->input->post('username');
      $password =  $this->input->post('password');
      
      $additional_data = [
				'name' => $this->input->post('name'),
				'phone' => $this->input->post('phone'),
      ];
    }
    if ($this->form_validation->run() === TRUE && $this->ion_auth->register($username, $password, $email, $additional_data))
		{
      // Check if we are creating a new user
      // Redirect back to users list
      $this->session->set_flashdata('message', 'Utilizador Criado');
			redirect('users', 'refresh');
    }
    else
    {
      // Display the create user form
      // Display error message if there is one
      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

      $this->data['name'] = [
				'name' => 'name',
				'id' => 'name',
        'type' => 'text',
        'class' => 'uk-input',
        'placeholder' => 'Nome',
				'value' => $this->form_validation->set_value('name'),
			];
			$this->data['username'] = [
				'name' => 'username',
				'id' => 'username',
        'type' => 'text',
        'class' => 'uk-input',
        'placeholder' => 'Nome de Utilizador',
        'value' => $this->form_validation->set_value('username'),
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
        'type' => 'text',
        'class' => 'uk-input',
        'placeholder' => 'Email',
				'value' => $this->form_validation->set_value('email'),
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
        'type' => 'text',
        'class' => 'uk-input',
        'placeholder' => 'Telefone',
				'value' => $this->form_validation->set_value('phone'),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
        'type' => 'password',
        'class' => 'uk-input',
        'placeholder' => 'Password',
				'value' => $this->form_validation->set_value('password'),
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
        'type' => 'password',
        'class' => 'uk-input',
        'placeholder' => 'Confirmar Password',
				'value' => $this->form_validation->set_value('password_confirm'),
			];
			$this->render('users/create_user_view');
    }
  }

  /**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('users', 'refresh');
		}
		redirect('/', 'refresh');
  }
  
  public function edit_user($id)
  {
    $this->data['title'] = 'Editar dados de utilizador';

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
    }

    $user = $this->ion_auth->user($id)->row();

    // Validate form input
    $this->form_validation->set_rules('name', 'name', 'trim|required');
    $this->form_validation->set_rules('phone', 'phone', 'trim');
    $this->form_validation->set_rules('email', 'email', 'trim|valid_email');

    if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'password_confirm', 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = [
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'phone' => $this->input->post('phone'),
				];

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', 'Utilizador actualizado');
					redirect('users', 'refresh');

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					$this->redirectUser();

				}

			}
    }
      
    // display the edit user form
    $this->data['csrf'] = $this->_get_csrf_nonce();
    
    // set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

    // pass the user to the view
    $this->data['user'] = $user;

    $this->data['username'] = [
      'name' => 'username',
      'id' => 'username',
      'type' => 'text',
      'class' => 'uk-input uk-disabled',
      'placeholder' => 'Utilizador',
      'value' => $this->form_validation->set_value('name', $user->username),
    ];
    $this->data['name'] = [
      'name' => 'name',
      'id' => 'name',
      'type' => 'text',
      'class' => 'uk-input',
      'placeholder' => 'Nome',
      'value' => $this->form_validation->set_value('name', $user->name),
    ];
    $this->data['email'] = [
      'name' => 'email',
      'id' => 'email',
      'type' => 'text',
      'class' => 'uk-input',
      'placeholder' => 'Email',
      'value' => $this->form_validation->set_value('email', $user->email),
    ];
    $this->data['phone'] = [
      'name' => 'phone',
      'id' => 'phone',
      'type' => 'text',
      'class' => 'uk-input',
      'placeholder' => 'Telefone',
      'value' => $this->form_validation->set_value('phone', $user->phone),
    ];
    $this->data['password'] = [
      'name' => 'password',
      'id' => 'password',
      'type' => 'password',
      'class' => 'uk-input',
      'placeholder' => 'Password',
      'value' => $this->form_validation->set_value('password'),
    ];
    $this->data['password_confirm'] = [
      'name' => 'password_confirm',
      'id' => 'password_confirm',
      'type' => 'password',
      'class' => 'uk-input',
      'placeholder' => 'Confirmar Password',
      'value' => $this->form_validation->set_value('password_confirm'),
    ];

    $this->render('users/edit_user_view');
    
  }

  public function delete_user($id){

    $user = $this->ion_auth->user();

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || $user->id == $id ||$this->ion_auth->is_admin($id))
		{
      // redirect them to the home page because they must be an administrator to view this
      // user can't delete himself and admin users, can't be deleted
      $this->session->set_flashdata('message', 'Sem acesso!');
      print_r($user->id);
      //redirect('users', 'refresh');
    }
    
    $this->ion_auth->delete_user($id);
    redirect('users', 'refresh');
  }

  	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;

		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			redirect("users", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			redirect("users", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
      // redirect them to the home page because they must be an administrator to view this
      $this->session->set_flashdata('message', 'Sem acesso!');
			redirect('users', 'refresh');
		}

		$this->ion_auth->deactivate($id);
      // redirect them back to the auth page
			redirect('users', 'refresh');
  }

  public function groups()
  {

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
    }

    $this->data['title'] = 'Listagem de Grupos';

    $groups = $this->ion_auth->groups()->result();

    $this->data['groups'] = $groups;

    $this->render('groups/groups_list_view');

  }

  public function user_groups($id)
  {
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('edit_user/' . $id, 'refresh');
    }

    $this->load->model('user_model');

    $this->data['title'] = 'Permissões de Utilizador';

    $user = $this->ion_auth->user($id)->row();
    $groups = $this->ion_auth->groups()->result();
    $group_labels = $this->user_model->group_labels();
    $currentGroups = $this->ion_auth->get_users_groups($id)->result();
    $array = [];
    
    foreach ($group_labels as $group_label):
      
      $x = [
        $group_label->label => json_decode(json_encode($this->user_model->group_permissions($group_label->label), TRUE)),
      ];  
      
      array_push($array, $x);
      
    endforeach;  
        
    // show edit user groups form
    $this->data['user'] = $user;
    $this->data['groups'] = $groups;
    $this->data['currentGroups'] = $currentGroups;
    $this->data['group_labels'] = $group_labels;
    $this->data['array'] = $array;

    $this->render('users/user_groups_view');

  }

  public function create_group()
  {
    $this->data['title'] = 'Novo Grupo';

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('groups', 'refresh');
    }
    
    // validate form input
    $this->form_validation->set_rules('group_name','group_name','trim|required|alpha_dash');

    if($this->form_validation->run() === TRUE)
    {
      $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('group_description'));

      if ($new_group_id)
      {
        // check if we are creating a new group
        // redirect back to groups list
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('groups','refresh');
      }
    }

      // display the create group form
      // set flash data if the ir as error

      $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

      $this->data['group_name'] = [
        'name'        => 'group_name',
				'id'          => 'group_name',
        'type'        => 'text',
        'class'       => 'uk-input',
        'placeholder' => 'Nome',
				'value'       => $this->form_validation->set_value('group_name'),
      ];
      $this->data['group_description'] = [
        'name'        => 'group_description',
				'id'          => 'group_description',
        'type'        => 'text',
        'class'       => 'uk-input',
        'placeholder' => 'Descrição',
				'value'       => $this->form_validation->set_value('group_description'),  
      ];
      $this->data['group_label'] = [
        'name'        => 'group_label',
				'id'          => 'group_label',
        'type'        => 'text',
        'class'       => 'uk-input',
        'placeholder' => 'Âmbito',
				'value'       => $this->form_validation->set_value('group_label'),  
      ];

      $this->render('groups/create_group_view');
  
  }

  public function edit_group($id)
  {
    // bail if no group id given
		if (!$id || empty($id) || !$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('groups', 'refresh');
		}

    $this->data['title'] = 'Editar Grupo';

    $group = $this->ion_auth->group($id)->row();

    // validate form input
    $this->form_validation->set_rules('group_name', 'group_name', 'trim|required|alpha_dash');

    if (isset($_POST) && !empty($_POST))
    {
      if ($this->form_validation->run() === TRUE)
      {
        $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
          'description' => $_POST['group_description'],
          'label'       => $_POST['group_label'],
        ));
        if ($group_update)
        {
          $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
          redirect('groups', 'refresh');
        }
        else
        {
          $this->session->set_flashdata('message', $this->ion_auth->errors());
        }
        redirect('groups', 'refresh');
      }
    }

    // set the flash data error message if there is one
    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
    
    // pass the user data to the view~
    $this->data['group'] = $group;

    $this->data['csrf'] = $this->_get_csrf_nonce();

    $this->data['group_name'] = [
      'name'        => 'group_name',
      'id'          => 'group_name',
      'type'        => 'text',
      'class'       => 'uk-input',
      'placeholder' => 'Nome',
      'value'       => $this->form_validation->set_value('group_name', $group->name),
    ];
    $this->data['group_description'] = [
      'name'        => 'group_description',
      'id'          => 'group_description',
      'type'        => 'text',
      'class'       => 'uk-input',
      'placeholder' => 'Descrição',
      'value'       => $this->form_validation->set_value('group_description', $group->description),  
    ];
    $this->data['group_label'] = [
      'name'        => 'group_label',
      'id'          => 'group_label',
      'type'        => 'text',
      'class'       => 'uk-input',
      'placeholder' => 'Âmbito',
      'value'       => $this->form_validation->set_value('group_description', $group->label),  
    ];


    $this->render('groups/edit_groups_view');  

  }

  public function delete_group($id){
    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
      // redirect them to the home page because they must be an administrator to view this
      $this->session->set_flashdata('message', 'Sem acesso!');
			redirect('edit_group' . $id, 'refresh');
    }
    
    $this->ion_auth->delete_group($id);
    redirect('groups', 'refresh');
  }
  
  	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
  }
  
  public function getAllUsers()
  {
    $result = $this->User_Model->getAllUsers();
    echo json_encode($result);
  }

  public function addNewUser()
  {

    $mgs['success'] = FALSE;  

    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $email    = $this->input->post('email');

    $data = [
      'name'      => $this->input->post('name'),
      'phone'     => $this->input->post('phone'),
    ];

    $result = $this->ion_auth->register($username, $password, $email, $data);

    if ($result)
    {
      $msg['success'] = TRUE;
    } 
    echo json_encode($msg);
    
  }

}