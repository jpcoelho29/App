<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="uk-container uk-container-xsmall uk-align-center">
<div id="infoMessage"><?php echo $message;?></div>
 
  <?php
      echo form_open('edit_user/' . $user->id , 'class="uk-form-stacked" id="frm"');
  ?>
       
    <fieldset class="uk-fieldset">

      <div class="uk-margin uk-align-right">
        <ul class="uk-iconnav">
          <li><a href="<?php echo base_url('users') ?>" uk-icon="icon: reply" title="Voltar"></a></li>
          <li><a id="btnSubmit" uk-icon="icon: floppy; ratio: 0.4" title="Guardar"></a></li>
          <li><a href="<?php echo base_url('user_groups/' . $user->id) ?>" uk-icon="icon: users" title="Editar Permissões"></a></li>
          <li><a href="<?php echo base_url('delete_user/' . $user->id) ?>" uk-icon="icon: trash" title="Apagar Utilizador"></a></li>
        </ul>
      </div>

      <div class="uk-margin">
        <?php echo form_input($username);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($name);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($email);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($phone);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($password);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($password_confirm);?>  
      </div>
    
    </fieldset>

    <?php echo form_hidden('id', $user->id);?>
    <?php echo form_hidden($csrf); ?>

    <?php echo form_close();?>

</div>

<script>
    // Script to submit forms
    document.getElementById("btnSubmit").onclick = function() {
        document.getElementById("frm").submit();
    }
</script>