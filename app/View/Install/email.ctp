<div class="progressbar" data-startvalue="40"></div>
<span class="progress-text"><span class="blue">Step 3 of 5:</span> Email Configuration</span>

<p>Now lets setup your SMTP servers; SMTP is currently the only supported method.</p>

<form id="config" action="<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'email')); ?>">    
    <label for="from">From:</label>
    <input type="text" id="from" name="from" placeholder="email@example.com" />
    <br/>
    
    <label for="database">Host:</label>
    <input type="text" id="host" name="host" placeholder="localhost" />
    <br/>
    
    <label for="port">Port:</label>
    <input type="text" id="port" name="port" placeholder="25" />
    <br/>
    
    <label for="login">Username:</label>
    <input type="text" id="login" name="username" placeholder="username" />
    <br/>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="password" />
    <br/>
</form>

<div class="button-container">
    <a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        $('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.nextStep);
    });
</script>