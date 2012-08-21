<div class="progressbar" data-startvalue="60"></div>
<span class="progress-text"><span class="blue">Step 4 of 5:</span> Create Superuser Account</span>

<p>At this point let's create a Super User account to manage the application.</p>

<form id="config">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="username" />
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