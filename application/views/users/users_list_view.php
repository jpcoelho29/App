<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container">
    
    <div class="uk-container uk-container-expand">

        <div class="uk-overflow-auto">

        <!-- uk-toggle="target: #newUserModal -->

        <a class="uk-button uk-button-small uk-button-default uk-button-danger" id="btnAddUser">Criar Utilizador</a>
        <a class="uk-button uk-button-small uk-button-default uk-button-danger" href="<?php echo base_url('groups') ?>">Gerir Grupos</a>
        
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
                <form id="userForm" action="" method="POST" class="uk-form uk-form-horizontal">
                    <fieldset class="uk-fieldset">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="username"><span uk-icon="icon: user"></span> Username</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="username" name="username">
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
                                <input class="uk-input" id="password" name="password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="password_confirm"><span uk-icon="icon: lock"></span> Confirmar Password</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="password_confirm" name="passord_confirm">
                            </div>
                        </div>                              
                    </fieldset>
                </form>    
            </div>
            <div class="uk-modal-footer">
                <button id="btnSaveUser" class="uk-button uk-button-primary uk-align-center" type="button"><span uk-icon="icon: add-user"></span> Adicionar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

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
                            $userStatus = '<span class="uk-label    uk-label-success">Ativo</span>'
                               
                        }
                        else{
                            $userStatus = '<span class="uk-label    uk-label-danger">Inativo</span>'
                        }
                        html += '<tr>' +
                                    '<td>'+data[i].name+'</td>' +
                                    '<td>'+data[i].username+'</td>' +
                                    '<td>'+data[i].email+'</td>' +
                                    '<td>'+data[i].phone+'</td>' +
                                    '<td>'+data[i].id+'</td>' + 
                                    '<td>'+$userStatus+'</td>' + 
                                    '<td>'+data[i].id+'</td>' + 
                                '</tr>';    
                    }
                    $('#showData').html(html);
                },
                error: function(){
                    alert('Error showing all users');
                }
            });
        }

        // Function to open create user modal
        $('#btnAddUser').click(function(){
            UIkit.modal('#userModal').show();
            $('#userModal').find('.uk-modal-title').text('Criar novo utilizador');
            $('#userForm').attr('action', '<?php echo base_url() ?>user/addNewUser');
        })

        // Function to save new user
        $('#btnSaveUser').click(function(){
            var url = $('#userForm').attr('action');
            var data = $('#userForm').serialize();
            console.log(url);
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
                        alert('Error');
                    }

                },
                error: function(){
                    alert('Utilizador não foi criado!');
                }
            })
        })

    })

</script>