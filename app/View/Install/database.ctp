<div class="progressbar" data-startvalue="40"></div>
<span class="progress-text"><span class="blue">Step 2 of 5:</span> Database Configuration</span>

<p>Next, let's connect up to your Database.</p>

<form id="database">
    <label for="datasource">Datasource:</label>
    <select id="datasource" name="datasource">
        <option value="Database/Mysql">MYSQL</option>
    </select>
    <br/>
    
    <label for="host">Host:</label>
    <input type="text" id="host" name="host" />
    <br/>
    
    <label for="database">Database:</lael>
    <input type="text" id="database" name="database" />
    <br/>
    
    <label for="login">Username:</label>
    <input type="text" id="login" name="login" />
    <br/>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" />
    <br/>
</form>

<div class="button-container">
    <!--<a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>-->
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        $('#btnNext').on('click', vapor.nextStep);
    });
</script>