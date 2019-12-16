<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="uk-container uk-container-xsmall uk-align-center">
<div id="infoMessage"><?php echo $message;?></div>
  
  <?php
      echo form_open('create_group', 'class="uk-form-horizontal"');
  ?>
    <fieldset class="uk-fieldset">

      <div class="uk-margin">
        <?php echo form_input($group_name);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($group_description);?>  
      </div>

      <div class="uk-margin">
        <?php echo form_input($group_label);?>  
      </div>
    
    
    </fieldset>

    <p><?php echo form_submit('submit','Criar Grupo','class="uk-button uk-button-danger uk-align-center"');?></p>

    <?php echo form_close();?>
</div>