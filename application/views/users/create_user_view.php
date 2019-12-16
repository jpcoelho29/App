<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="uk-container uk-container-xsmall uk-align-center">
<div id="infoMessage"><?php echo $message;?></div>
  
  <?php
      echo form_open('create_user', 'class="uk-form-horizontal"');
  ?>
    <fieldset class="uk-fieldset">

      <div class="uk-margin">
        <?php echo form_input($name);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($username);?>  
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

    <p><?php echo form_submit('submit','Criar Utilizador','class="uk-button uk-button-danger uk-align-center"');?></p>

    <?php echo form_close();?>
</div>