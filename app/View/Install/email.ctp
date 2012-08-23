<div class="progressbar" data-startvalue="40"></div>
<span class="progress-text"><span class="blue">Step 3 of 5:</span> Email Configuration</span>

<p>Now lets setup your SMTP servers; SMTP is currently the only supported method.</p>

<form id="config" action="<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'email')); ?>">    
    <label for="from">From Email:</label>
    <input type="text" id="from" name="from" />
    <br/>
    
    <label for="host">Host:</label>
    <input type="text" id="host" name="host" />
    <br/>
    
    <label for="port">Port:</label>
    <input type="text" id="port" name="port" placeholder="25" />
    <br/>
    
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
        vapor.util.setupFormValidation('#config', {
            from: { required: true, email: true },
            host: { required: true },
            port: { required: false, number: true },
            username: { required: false },
            password: {
                required: function(element) {
                    return ($('#username').val() !== '');
                }
            }
        });
        
        $('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.submitAndCheck);
    });
</script>