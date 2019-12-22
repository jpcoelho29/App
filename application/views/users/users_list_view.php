<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container">
    
    <div class="uk-container uk-container-expand">

        <div class="uk-overflow-auto">

        <div>    
            <a class="uk-button uk-button-small uk-button-default uk-button-danger" id="btnAddUser">Criar Utilizador</a>
            <a class="uk-button uk-button-small uk-button-default uk-button-danger" href="#">Gerir Grupos</a>
        </div>

        <table class="uk-table uk-table-striped uk-table-small uk-table-middle">
            <tr id="custom-table-header">
                <th>Nome</th>
                <th>Utilizador</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Grupos</th>
                <th>Estado</th>
                <th>Editar</th>
            </tr>
            <tbody id="showData">
                  
            </tbody>  
        </table>
        </div>
    </div>
    
    <div id="userModal" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h3 class="uk-modal-title uk-text-capitalize uk-text-center"></h3>
            </div>
            <div class="uk-modal-body">
                <div id="errorAlert">
                </div>
                <form id="userForm" action="" method="POST" class="uk-form uk-form-horizontal">
                    <fieldset class="uk-fieldset">
                    <div class="uk-margin-small">
                        <div id="userTypeSection">
                            <label class="uk-form-label" for='group'>
                                <span uk-icon="icon: users"></span> Tipo de Utilizador
                            </label>
                            <div class="uk-margin-small uk-form-controls">
                                <div class="userTypeSection"> 
                                </div>    
                            </div>
                        </div>   
                    </div>
                        <input type="hidden" id="userId" name="userId" value="0">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="username"><span uk-icon="icon: user"></span> Username</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="username" name="username" >
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="name"><span uk-icon="icon: user"></span> Nome</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="name" name="name">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="email"><span uk-icon="icon: mail"></span> E-mail</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="email" name="email">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="phone"><span uk-icon="icon: phone"></span> Telefone</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password"><span uk-icon="icon: lock"></span> Password</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="password" name="password" type="password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password_confirm"><span uk-icon="icon: lock"></span> Confirmar Password</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="password_confirm" name="password_confirm" type="password">
                            </div>
                        </div>                              
                    </fieldset>
                </form>    
            </div>
            <div class="uk-modal-footer">
                <button id="btnSaveUser" class="uk-button uk-button-primary uk-align-center" type="button"><span uk-icon="icon: add-user"></span> Guardar</button>
            </div>
        </div>
    </div>

    <div id="userGroupsModal" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h3 class="uk-modal-title uk-text-capitalize uk-text-center">
                Permissões do Utilizador
                </h3>
            </div>
            <div class="uk-modal-body">
                <form id="userGroupsForm" action="" method="POST" class="uk-form uk-form-horizontal">
                    <fieldset class="uk-fieldset">

                        <input type="hidden" name="user_id" value="" id="user_id"></input>

                        <div class="uk-margin-small">
                            <label class="uk-form-label" for='group'>
                                <span uk-icon="icon: users"></span> Tipo de Utilizador
                            </label>
                            <div class="uk-margin-small uk-form-controls">
                                <div class="userTypeSection"> 
                                </div>
                            </div>
                        </div>
                    <hr>
                    <div id="permissionsSection">
                    </div>

                    </fieldset>
                </form>
            </div>        
            <div class="uk-modal-footer">
                <button id="btnSaveGroups" class="uk-button uk-button-primary uk-align-center" type="button"><span uk-icon="icon: save"></span> Guardar</button>
            </div>
        </div>
    <div>
</div>

<script>

    $(document).ready(function(){

        showAllUsers();
        
        // Function to show all the users
        function showAllUsers(){
            $.ajax({
                type: 'ajax',
                url: '<?php base_url() ?>user/getAllUsers',
                dataType: 'json',
                success: function(data){
                    var html = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        // Check if user is acive
                        var userStatus;
                        if(data[i].active == 1)
                        {
                            $userStatus = '<a class="uk-button uk-button-primary uk-button-small uk-text-bold" id="userStatus" data="' + data[i].id +'" uk-tooltip="Inativar Utilizador">Ativo</a>'         
                        }
                        else{
                            $userStatus = '<a class="uk-button uk-button-danger uk-button-small uk-text-bold" id="userStatus" data="' + data[i].id +'" uk-tooltip="Ativar Utilizador">Inativo</a>'
                        }
                        html += '<tr>' +
                                    '<td>'+data[i].name+'</td>' +
                                    '<td>'+data[i].username+'</td>' +
                                    '<td>'+data[i].email+'</td>' +
                                    '<td>'+data[i].phone+'</td>' +
                                    '<td><a class="uk-icon-button" uk-icon="users" id="btnUserGroups" data="'+data[i].id+'" uk-tooltip="Editar Permissões"></a></td>' + 
                                    '<td>'+$userStatus+'</td>' + 
                                    '<td><a class="uk-icon-button" uk-icon="pencil" id="btnEditUser" data="'+data[i].id+'" uk-tooltip="Editar Utilizador"></a></td>' + 
                                '</tr>';    
                    }
                    $('#showData').html(html);
                },
                error: function(){
                    alert('Não foi possível mostrar lista de utilizadores');
                }
            });
        }

        // Function to Set Create User Modal
        $('#btnAddUser').click(function(){
            url = '<?php base_url() ?>user/getUserTypes';
            $.ajax({
                type: 'ajax',
                method: 'get',
                url: url,
                dataType: 'json',
                success: function(response){
                    var user_types = response.user_types;
                    var html = '<select class="uk-select" name="group[]">';
                    for(i=0; i<user_types.length; i++){
                        html += '<option ' +
                                'value="' + user_types[i]['name'] + '"' +
                                '>' +
                                user_types[i]['name'] +
                                '</option>';    
                        }      
                        html += '</select name>';
                        $('.userTypeSection').html(html); 
                },
                error: function(){
                    alert('Couldnt get user types');
                }
            })         
            UIkit.modal('#userModal').show();
            $('#userModal').find('.uk-modal-title').text('Criar novo utilizador');
            $('#userForm').attr('action', '<?php echo base_url() ?>user/addNewUser');
            $('#username').prop('disabled', false);
            $('#userType').show();
            $('#userForm')[0].reset();           
            
            
        });

        // Function to Set Edit User Modal
        $('#showData').on('click', '#btnEditUser', function(){
            var userId = $(this).attr('data');
            var url = '<?php echo base_url() ?>user/editUserData';
            UIkit.modal('#userModal').show();
            $('#userModal').find('.uk-modal-title').text('Editar utilizador');
            $('#userForm').attr('action', '<?php echo base_url() ?>user/updateUser');
            $('#userTypeSection').hide();
            $.ajax({
                type: 'ajax',
                method:'get',
                url: url,
                data: {id: userId},
                dataType: 'json',
                success: function(data){
                    var user = data.user[0];
                    $('#userId').val(user['id']);
                    $('#username').val(user['username']);
                    $('#username').prop('disabled', true);
                    $('#name').val(user['name']);
                    $('#email').val(user['email']);
                    $('#phone').val(user['phone']);
                },
                error: function(){
                    alert('Não foi possível obter dados do utilizador!');
                }
            })
        })
        // Function to save new user OR update user data
        $('#userModal').on('click', '#btnSaveUser' ,function(){
            var url = $('#userForm').attr('action');
            var data = $('#userForm').serialize();
            $.ajax({
                type: 'ajax',
                method: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        UIkit.modal('#userModal').hide();
                        $('#userForm')[0].reset();
                        showAllUsers();
                    }else{
                        var html = '';
                        html = '<div class="uk-alert-danger" uk-alert>' +
                                    '<a class="uk-alert-close" uk-close></a>' +
                                    response.errors + 
                                '</div>';
                        $('#errorAlert').html(html).fadeIn('fast').delay(10000).fadeOut('slow');
                    }
                },
                error: function(){
                    alert('Os dados de utilizador não foram guardados!');
                }
            })
        })

        // Function to change user status
        $('#showData').on('click', '#userStatus', function(){
            var btn = $(this);
            var userId = btn.attr('data');
            var url = '<?php echo base_url() ?>user/editUserStatus';
            $.ajax({
                type: 'ajax',
                method: 'post',
                url: url,
                data: {id: userId},
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        if(response.status){
                            // User has ben activated
                            btn.removeClass('uk-button-danger');
                            btn.addClass('uk-button-primary');
                            btn.text('Ativo');
                            btn.attr('uk-tooltip', 'Inativar Utilizador');
                        }else{
                            // User has been deactivated
                            btn.removeClass('uk-button-primary');
                            btn.addClass('uk-button-danger');
                            btn.text('Inativo');
                            btn.attr('uk-tooltip', 'Ativar Utilizador');
                        }
                    }else{
                        alert(response.error);
                    }
                },
                error: function(){
                    alert('Não foi possível alterar o estado do utilizador!')
                }
            })
            
        })

        // Function to edit user groups
        $('#showData').on('click', '#btnUserGroups', function(){
            var btn = $(this);
            $('#userForm').attr('action', '<?php echo base_url() ?>user/updateUserGroups');
            var url = '<?php base_url() ?>user/userGroups';
            var user_id = btn.attr('data');
            $.ajax({
                type: 'ajax',
                method: 'get',
                url: url,
                data: {id: user_id},
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        var permissions = response.group_permissions;
                        var user_groups = response.user_groups;
                        var user_types = response.user_types;
                        var user_type = response.user_type;
                        var i;
                        var k;
                        var html = '<select class="uk-select" name="group[]">';
                        var selected;
                        var checked;
                        var actions = [['view','Ver'], ['write','Escrever'], ['edit','Editar']]
                        var in_groups = [];

                        $('#user_id').attr('value', user_id);

                        for(i=0; i<user_groups.length; i++){
                            in_groups.push(user_groups[i]['name']);
                        }

                        for(i=0; i<user_types.length; i++){

                            if(user_types[i]['name'] == user_type){
                                selected = 'selected';
                            }else{
                                selected = '';
                            }
                            
                            html += '<option ' +
                                    'value="' + user_types[i]['name'] + '"' +
                                    selected +
                                    '>' +
                                    user_types[i]['name'] +
                                    '</option>';    
                        }      
                        
                        html += '</select name>';

                        $('.userTypeSection').html(html);
                        
                        html = '';

                        for(i=0; i<permissions.length; i++){

                            html += '<div class="uk-margin-small">' +
                                        '<label class="uk-form-label uk-text-bold" for="'+ permissions[i]['description'] +'">'+permissions[i]['description'] +
                                        ':</label>'+
                                        '<div class="uk-margin-small uk-kit-grid-small uk-child-width-auto uk-grid">';

                                            for(k=0; k<actions.length; k++){

                                                group_to_check = permissions[i]['description'].toLowerCase() + '_' + actions[k][0];
                                                
                                                if(in_groups.includes(group_to_check)){
                                                    checked = 'checked';    
                                                }
                                                else{
                                                    checked = '';
                                                }
                                                
                                                html +='<label>' +
                                                        '<input name="group[]" class="uk-checkbox" ' + 'type="checkbox" ' + 
                                                        checked +
                                                        ' value="' +
                                                        group_to_check +
                                                        '"> ' + 
                                                        actions[k][1] +
                                                        '</label>';
                                            }
                                                                                                               
                            html+=      '</div>' +
                                    '</div>' +
                                    '<hr>';

                           $('#permissionsSection').html(html);        

                           UIkit.modal('#userGroupsModal').show();

                        }
                    }
                },
                error: function(){
                    alert('Could not get user groups');
                }
            }) 
        })

        $('#btnSaveGroups').click(function(){
            url     = $('#userForm').attr('action');
            data    = $('#userGroupsForm').serialize()
            $.ajax({
                type: 'ajax',
                method: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(response){
                    UIkit.modal('#userGroupsModal').hide();
                    console.log(response);
                },
                error: function(){
                    alert("Couldn't update user groups")
                }
            })
        })

    })

</script>