<div class="progressbar" data-value="55"></div>
<span class="progress-text">Step 1 of 5: Environment Check</span>

<p>First off lets check your environment.</p>

<?php
    $fail = false;
    foreach($checks as $key => $check):
        $fail = $fail | $check['pass'];
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
    <a class="button" data-icon-left="ui-icon-arrow-1-w">Back</a>
    <a class="button" data-icon-right="ui-icon-arrow-1-e">Next</a>
</div>