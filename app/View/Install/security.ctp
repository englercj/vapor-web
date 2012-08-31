<div class="progressbar" data-startvalue="29"></div>
<span class="progress-text"><span class="blue">Step 2 of 7:</span> Security Configuration</span>

<p>Now, we must configure your security settings. Please select the button below to generate random security codes.</p>

<form id="config" action="<?php echo $this->Html->url(array('controller' => 'install', 'action' => 'security')); ?>">
    <a id="btnGenerate" href="#" class="button" data-icon-left="ui-icon-key">Generate Security Codes</a>
    <br/><br/>

    <div id="secure">
        
    </div>
    <br/>
</form>

<div class="button-container">
    <!--<a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>-->
    <a id="btnSkip" href="#" class="button" data-icon-right="ui-icon-arrow-1-e">Skip</a>
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e" data-disabled="true">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        //$('#btnBack').on('click', vapor.install.previousStep);
        $('#btnNext').on('click', vapor.install.nextStep);
        $('#btnSkip').on('click', vapor.install.nextStep);
        
        $('#btnGenerate').on('click', function(e) {
            $('#btnGenerate').button('disable');
            
            vapor.ajax.post(
                $('#config').prop('action'),
                {},
                function(data, textStatus, jqXHR) {
                    var $div = $('#secure').empty();
                    
                    if(data.success) {
                        $div.append(vapor.html.createBubble(data.codes.salt, 'success', 'Security Salt', 'check'));
                        $div.append(vapor.html.createBubble(data.codes.seed, 'success', 'Cipher Seed', 'check'));
                        $('#btnNext').button('enable');
                    } else {
                        $div.append(vapor.html.createBubble('There was an error trying to generate a Security Salt.', 'error', 'Security Salt', 'alert'));
                        $div.append(vapor.html.createBubble('There was an error trying to generate a Cipher Seed.', 'error', 'Cipher Seed', 'alert'));
                        $('#btnGenerate').button('enable');
                    }
                },
                function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                    alert(textStatus + '; Check your console for more details');
                }
            );
        });
    });
</script>