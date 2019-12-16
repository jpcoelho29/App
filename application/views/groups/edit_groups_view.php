<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="uk-container uk-container-xsmall uk-align-center">
<div id="infoMessage"><?php echo $message;?></div>
  
  <?php
      echo form_open('edit_group/' . $group->id, 'class="uk-form-horizontal"');
  ?>
    <fieldset class="uk-fieldset">

      <div class="uk-margin uk-align-right">
        <ul class="uk-iconnav">
          <li><a href="<?php echo base_url('groups') ?>" uk-icon="icon: reply" title="Voltar"></a></li>
          <li><a href="<?php echo base_url('delete_group/' . $group->id ) ?>" uk-icon="icon: trash" title="Apagar Grupo"></a></li>
        </ul>
      </div>

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

    <p><?php echo form_submit('submit','Guardar','class="uk-button uk-button-danger uk-align-center"');?></p>

    <?php echo form_close();?>
</div>