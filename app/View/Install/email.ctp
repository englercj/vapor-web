<div class="progressbar" data-startvalue="57"></div>
<span class="progress-text"><span class="blue">Step 4 of 7:</span> Email Configuration</span>

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
    <a id="btnSkip" href="#" class="button" data-icon-right="ui-icon-arrow-1-e">Skip</a>
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        //setup validation of form
        vapor.util.setupFormValidation('#config', 
            {
                from: { required: true, email: true },
                host: { required: true },
                port: { required: false, number: true },
                username: { required: false },
                password: { required: false }
            },
            {
                from: 'You must enter a valid email address.',
                host: 'Hostname is required.',
                port: 'Port must be numeric.',
                username: '',
                password: ''
            }
        );
        
        $('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.submitAndCheck);
        
        $('#btnSkip').on('click', function(e) {
            //immediately remove these buttons so that you can't click 2 times
            $('#btnNext').hide();
            $('#btnSkip').hide();
            $('#btnBack').hide();
            vapor.ui.showLoader('.button-container');
            
            vapor.ajax.post('<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'email')); ?>', 
                { skip: true }, 
                function(result) {
                    vapor.install.nextStep();
                },
                function(jqXHR, textStatus, errorThrown) {}
            );
        });
    });
</script>