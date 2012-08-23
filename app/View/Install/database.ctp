<div class="progressbar" data-startvalue="20"></div>
<span class="progress-text"><span class="blue">Step 2 of 5:</span> Database Configuration</span>

<p>Next, let's connect up to your Database.</p>

<form id="config" action="<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'database')); ?>">
    <label for="datasource">Datasource:</label>
    <select id="datasource" name="datasource">
        <option value="Database/Mysql">MySQL</option>
        <option value="Database/Sqlite">SQLite</option>
    </select>
    <br/>

    <label for="host">Host:</label>
    <input type="text" id="host" name="host" placeholder="localhost" />
    <br/>

    <label for="port">Port:</label>
    <input type="text" id="port" name="port" placeholder="3306" />
    <br/>

    <label for="database">Database:</label>
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
        vapor.util.setupFormValidation('#config', 
            {
                datasource: { required: true },
                database: { required: true },
                host: { required: false },
                port: { required: false, number: true }
            },
            {
                datasource: 'Datasource is required.',
                database: 'Database name is required.',
                host: '',
                port: 'Port must be numeric.'
            }
        );
        
        //$('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.submitAndCheck);
        
        $('#datasource').on('change', function() {
        });
    });
</script>