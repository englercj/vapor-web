<div class="progressbar" data-startvalue="14"></div>
<span class="progress-text"><span class="blue">Step 1 of 7:</span> Environment Check</span>

<p>Before we start, let's ensure your environment is setup correctly.</p>

<?php
    $fail = false;
    foreach($checks as $key => $check):
        $fail = $fail || ($check['pass'] != 'success');
?>
<div class="ui-corner-all ui-state-<?php echo ($check['pass'] ? 'success' : 'error'); ?>">
    <p>
        <span class="ui-icon ui-icon-<?php echo ($check['pass'] ? 'check' : 'alert'); ?>"
              style="float: left; margin-right: .3em;"></span>
              
        <strong><?php echo $check['title']; ?></strong>
        
        <?php
            if($check['pass']) {
                echo $check['successText'];
            } else {
                echo $check['failText'];
            }
        ?>
    </p>
</div>
<?php endforeach; ?>

<div class="button-container">
    <!--<a id="btnBack" href="#" class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>-->
    <a id="btnNext" href="#" class="button ui-button-primary" 
        data-icon-right="ui-icon-arrow-1-e" data-disabled="<?php echo ($fail ? 'true' : 'false'); ?>">Next</a>
</div>

<script type="text/javascript">
    $(function() {
        $('#btnNext').on('click', vapor.install.nextStep);
    });
</script>