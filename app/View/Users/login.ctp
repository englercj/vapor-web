<h2><span class="blue">VaporCP</span> Login</h2>
<?php echo $this->element('flash/messages'); ?>
<br class="clear"/>

<img src="/img/icons/128/lock.png" alt="Login" id="login-lock" />
<div id="login-box">
    <br/>
    <?php 
        echo $this->Form->create('User', array('action' => 'login'));
        echo $this->Form->input('username', array(
            'label' => false,
            'between' => '<img src="/img/icons/22/user.png" class="input-icon" />',
            'placeholder' => 'username',
            'class' => 'input-icon'
        ));
    ?>
    <br/>
    <?php
        echo $this->Form->input('password', array(
            'label' => false,
            'between' => '<img src="/img/icons/22/key.png" class="input-icon" />',
            'placeholder' => 'password',
            'class' => 'input-icon'
        ));
    ?>
    <br/>
    <?php
        echo $this->Form->end(array('label' =>  'Login', 'class' => 'button ui-button-primary floatR'));
    ?>
</div>

<script>
    $(function() {
        //setup validation of form
        vapor.util.setupFormValidation('#UserLoginForm', 
            {
                'data[User][username]': { required: true },
                'data[User][password]': { required: true }
            },
            {
                'data[User][username]': 'Your username is required.',
                'data[User][password]': 'Your password is required.'
            }
        );
    });
</script>