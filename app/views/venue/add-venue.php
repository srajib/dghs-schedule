<div class="block">

    <?php if (!empty($errorMessage)) : ?>
    <div class="message errormsg">
        <?php echo $errorMessage ?>
    </div>
    <?php endif ?>

    <div class="block_head">
        <h2>Add Venue</h2>
    </div>

    <div class="block_content">
        
        <form action="" method="POST">

            <p>
                <label for="title">
                    Venue Name: <span class="required">*</span>
                </label>

                <input id="title" type="text" name="title" class="text small"
                       value= "<?php echo set_value('title') ?>" /><br />
                <span class='note error'>
                    <?php echo form_error('title') ?>
                </span>
            </p>

            <p>
                <input type="submit" value="Save" class="submit small" />
                <input type="button" value ="Exit" class="submit small"
                       onClick = "window.location='<?php echo site_url('venue') ?>'" />
            </p>

        </form>

    </div>		<!-- .block_content ends -->
</div>		<!-- .block ends -->