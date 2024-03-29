<div class="progressbar" data-startvalue="71"></div>
<span class="progress-text"><span class="blue">Step 5 of 7:</span> Create Superuser Account</span>

<p>At this point let's create a Super User account to manage the application.</p>

<form id="config" action="<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'superuser')); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" />
    <br/>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" />
    <br/>
</form>

<div class="button-container">
    <a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        //setup validation of form
        vapor.util.setupFormValidation('#config', 
            {
                username: { required: true },
                password: { required: true }
            },
            {
                username: 'Username is required.',
                password: 'Password is required.'
            }
        );
        
        $('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.submitAndCheck);
    });
</script>