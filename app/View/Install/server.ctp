<div class="progressbar" data-startvalue="80"></div>
<span class="progress-text"><span class="blue">Step 5 of 5:</span> Add Managed Server</span>

<p>Finally, lets add a server to manage. You can add more later via the UI.</p>

<form id="config">
    <label for="host">Host:</label>
    <input type="text" id="host" name="host" />
    <br/>
    
    <label for="port">Port:</label>
    <input type="text" id="port" name="port" placeholder="9876" />
    <br/>
</form>

<div class="button-container">
    <a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e">Finish</a>
</div>

<script type="text/javascript">
    $(function() {
        //setup validation of form
        vapor.util.setupFormValidation('#config', {
            host: { required: true },
            port: { required: false, number: true }
        });
        
        $('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.submitAndCheck);
    });
</script>