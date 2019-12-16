<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

  <div class="uk-container uk-container-xsmall uk-overflow-auto">

    <a class="uk-button uk-button-small uk-button-default uk-button-danger" href="<?php echo base_url('create_group') ?>" title="Criar novo grupo">Criar Grupo</a>

    <div class="uk-margin uk-align-right">
      <ul class="uk-iconnav">
        <li><a href="<?php echo base_url('users') ?>" uk-icon="icon: reply" title="Voltar"></a></li>
      </ul>
    </div>

    <table class="uk-table uk-table-striped uk-table-small uk-table-middle">
      
      <tr id="custom-table-header">
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Âmbito</th>
        <th>Editar</th>
      </tr>

      <?php 
      foreach($groups as $group):
      ?>
      <tr>
        <td><?php echo htmlspecialchars($group->id,ENT_QUOTES,'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($group->description,ENT_QUOTES,'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($group->label,ENT_QUOTES,'UTF-8'); ?></td>
        <td>
          <a href="<?php echo 'edit_group/'.$group->id ?>" class="uk-icon-button" uk-icon="pencil" title="Editar grupo"></a>
        </td>
      </tr>
      <?php  
      endforeach;
      ?>

    </table>

  </div>

