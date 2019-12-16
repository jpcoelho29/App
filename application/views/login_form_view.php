<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
  <div class="uk-width-1-1">
    <div class="uk-container">
          <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
          <div class="uk-width-1-1@m">
            <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large" id="login-card">
            
            <?php 
            if($this->session->flashdata('error_message'))
            {
              echo '<div class="uk-alert-danger" uk-alert>';
              echo '<a class="uk-alert-close" uk-close></a><p><span uk-icon="warning"></span> ';
              echo ($this->session->flashdata('error_message'));
              echo '</p></div>'; 
            }
            elseif($this->session->flashdata('logout_message'))
            {
              echo '<div class="uk-alert-success" uk-alert>';
              echo '<a class="uk-alert-close" uk-close></a><p><span uk-icon="check"></span> ';
              echo ($this->session->flashdata('logout_message'));
              echo '</p></div>'; 
            }
            ?>

              <div class="uk-margin" style="text-align: center">
                  <img src="<?php echo base_url('assets/img/logo_login.png') ?>">
              </div>
              <?php echo form_open('login') ?>
                <div class="uk-margin">
                  <div class="uk-inline uk-width-1-1">
                      <span class="uk-form-icon" uk-icon="icon: user"></span>
                      <input class="uk-input uk-form" type="text" placeholder="Utilizador" name="username" id="username">
                  </div>
                </div>
                <div class="uk-margin">
                  <div class="uk-inline uk-width-1-1">
                      <span class="uk-form-icon" uk-icon="icon: lock"></span>
                      <input class="uk-input uk-form" type="password" placeholder="Senha" name="password" id="password">  
                  </div>
                </div>
                <div class="uk-margin">
                  <button class="uk-button uk-button-danger uk-width-1-1" name="login_submit_btn",type="submit">
                      Login
                  </button>
                </div>
              <?php form_close(); ?>  
            </div>
          </div>
        </div>
    </div>
  </div>
</div>