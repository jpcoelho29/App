<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="uk-container uk-container-small uk-align-center">

  <?php

    $this->load->helper('form');

    foreach ($group_labels as $label):

    ?>  <div class="uk-margin"> <?php

      echo form_label($label->label, 'class="uk-text-bold');

      ?><span>:</span><?php
      
      foreach ($groups as $group):
        if ($group->label == $label->label)
        {

          if ($this->ion_auth->in_group($group->name ,$user->id))
          {
            $in_group = TRUE;
          }
          else 
          {
            $in_group = FALSE;
          }

          $data = [
            'name'    => $group->label,   
            'id'      => $group->name,
            'value'   => $group->name,
            'checked' => $in_group,
            'class'   => 'uk-radio',
          ];
          ?>
          <label class="uk-text-capitalize"><?php echo $group->description; ?>
          <?php
          echo form_radio($data);
          ?>
          </label>
          <?php
        }
      
      endforeach;

    endforeach;
  ?>
  </div>
</div>


