<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
  <nav class="uk-navbar-container .uk-background-primary" uk-navbar>
    <div class="uk-navbar-right">
      <img src='<?php echo base_url('assets/img/brand_logo.png') ?>'>
    </div>
    <div class="uk-navbar-right">
      <a uk-navbar-toggle-icon="" href="#offcanvas" uk-toggle="target: #offcanvas-nav" class="uk-navbar-toggle uk-icon uk-navbar-toggle-icon" id="hamburguer"></a>
    </div>
  </nav>
</div>

<div id="offcanvas-nav" uk-offcanvas="overlay: true">
  <div class="uk-offcanvas-bar">
    <ul class="uk-nav uk-nav-default">
      <li id="sidebar_logo"> 
        <img src='<?php echo base_url('assets/img/brand_logo.png') ?>'>
      </li>
      <hr> 
      <li>
        <a href="<?php echo base_url('dashboard') ?>">
          <span class="uk-margin-small-right" uk-icon="icon: grid"></span> 
          Menu Principal
        </a>
      </li>
      <li>
        <a href="#">
          <span class="uk-margin-small-right" uk-icon="icon: future"></span> 
          Planeamento
        </a>
      </li>
      <li>
        <a href="#">
          <span class="uk-margin-small-right" uk-icon="icon: git-fork"></span> 
          Configuração de Produto
        </a>
      </li>
      <li>
        <a href="#">
          <span class="uk-margin-small-right" uk-icon="icon: cog"></span> 
          Manutenção
        </a>
      </li>
      <li>
        <a href="<?php echo base_url('users') ?>">
          <span class="uk-margin-small-right" uk-icon="icon: users"></span> 
          Gestão Utilizadores
        </a>
      </li>
      <li class="uk-nav-divider"></li>
      <li><a href="<?php echo base_url('logout') ?>"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span> Terminar Sessão</a></li>
    </ul>

  </div>
</div>
<p>

<?php
if(isset($title)){
?>
  <div class="uk-container uk-container-expand uk-overflow-auto">
    <h1 class="uk-text-lead uk-text-uppercase uk-text-center">
      <?php
        echo($title);
      ?>
    </h1>
  </div>  
<?php } ?>

<p>

<?php
  if($this->session->flashdata('message'))
  {
?>
<script>
UIkit.notification({
    message: '<?php echo ('<span uk-icon="info"></span> '); echo $this->session->flashdata('message');?>',
    status: 'primary',
    pos: 'bottom-right',
    timeout: 2500
});
</script>
<?php
  }
?>