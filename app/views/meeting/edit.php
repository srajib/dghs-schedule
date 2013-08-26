<div class="block">

    <?php if (!empty($errorMessage)) : ?>
        <div class="message errormsg">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif ?>

    <div class="block_head">
        <h2>Edit Meeting Venue</h2>
    </div>

    <div class="block_content">

        <form action="" method="POST">
            <p class="adjacent same-row">
                <label for="startingDate">
                    Starting date: <span class="required">*</span>
                </label>

                <input id="startingDate" type="text" name="startingDate" class="text date_picker" value= "<?php echo set_value('startingDate', $meetings['startingDate'])?>" />
                <span class='note error'>
                    <?php echo form_error('startingDate') ?>
                </span>
            </p>
            <p class="adjacent right">
                <label for="startingTime">
                  Starting Time: <span class="required">*</span>
                </label>

                <input id="startingTime" type="text" name="startingTime" class="text small" value= "<?php echo set_value('startingTime', $meetings['startingTime'])?>" />
                <span class='note error'>
                    <?php echo form_error('startingTime') ?>
                </span>
            </p>
            <p class="adjacent same-row">
                <label for="endingDate">
                    Ending Date: <span class="required">*</span>
                </label>

                <input id="endingDate" type="text" name="endingDate" class="text date_picker" value= "<?php echo set_value('endingDate', $meetings['endingDate'])?>" />
                <span class='note error'>
                    <?php echo form_error('endingDate') ?>
                </span>
            </p>
            <p class="adjacent right">
                <label for="endingTime">
                  Ending Time: <span class="required">*</span>
                </label>

                <input id="endingTime" type="text" name="endingTime" class="text small" value= "<?php echo set_value('endingTime', $meetings['endingTime'])?>" />
                <span class='note error'>
                    <?php echo form_error('endingTime') ?>
                </span>
            </p>
            <p>
                <label for="topic">
                  Topic: <span class="required">*</span>
                </label>

                <input id="topic" type="text" name="topic" class="text large" value= "<?php echo set_value('topic', $meetings['topic'])?>" />
                <span class='note error'>
                    <?php echo form_error('topic') ?>
                </span>
            </p>
             <p>
                <label for="description">
                  Description:
                </label>
                <textarea id="description" name="description" class="textarea"><?php echo set_value('description', $meetings['description'])?></textarea>
            </p>
            <p class="adjacent same-row">
                <label for="venue_id">
                    Venue: <span class="required">*</span>
                </label>

                <select id="venue_id" name="venue_id" class="styled">
                    <option value=''>- Select -</option>

                    <?php foreach ($venues as $venue) : ?>
                    <option value="<?php echo $venue['venue_id'] ?>" <?php echo (($meetings['venue_id'] == $venue['venue_id'])? "selected='selected'":"" )?>><?php echo $venue['title'] ?></option>
                    <?php endforeach ?>

                </select>
                <span class='note error'>
                    <?php echo form_error('venue_id') ?>
                </span>
            </p>
            <p class="adjacent right">
                <label for="group_id">
                    Group: <span class="required">*</span>
                </label>

                <select id="group_id" name="group_id" class="styled">
                    <option value=''>- Select -</option>

                    <?php foreach ($groups as $group) : ?>
                    <option value="<?php echo $group['group_id'] ?>" <?php echo (($meetings['group_id'] == $group['group_id'])? "selected='selected'":"" )?>><?php echo $group['associated_name'] ?></option>
                    <?php endforeach ?>

                </select>
                <span class='note error'>
                    <?php echo form_error('group_id') ?>
                </span>
            </p>

             <p>
                <label for="contact_person">
                  Contact Person's Name and Contact No.: <span class="required">*</span>
                </label>

                <input id="contact_person" type="text" name="contact_person" class="text large" value= "<?php echo set_value('contact_person', $meetings['contact_person'])?>" />
                <span class='note error'>
                    <?php echo form_error('contact_person') ?>
                </span>
            </p>
            <p>
                <input type="submit" value="Update" id="submit-event" class="submit small" />
                <input type="button" value="Cancel" id="submit-cancel" class="submit small" />
            </p>
        </form>
    </div>		<!-- .block_content ends -->
</div>		<!-- .block ends -->

<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/time/css/jquery-ui-1.8.14.custom.css') ?>"  />
<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/time/css/jquery-ui-timepicker.css') ?>"/>

<script type="text/javascript" src="<?php echo site_url('assets/time/js/jquery.ui.core.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/time/js/jquery.ui.timepicker.js') ?>"></script>
<script type="text/javascript">

    $(function(){

        $('#startingTime, #endingTime').timepicker({
            showPeriod: true,
            showLeadingZero: true
        });

        $('#submit-cancel').click(function(){
            window.location = '<?php echo site_url('meeting') ?>';

        });

    });

</script>