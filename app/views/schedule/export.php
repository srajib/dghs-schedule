<div class="block">
    <div class="block_head">
        <h2>Export AS EXCEL</h2>
    </div>

    <div class="block_content">

        <form action="<?php echo site_url('schedule/finalExport') ?>" method="POST">

            <p>
                <label for="date" style="display: inline-block">
                    From Date :
                </label>

                <input id="date" type="text" name="starting_date" class="text date_picker" value= "" />

                <label for="date" style="display: inline-block">
                    To Date :
                </label>

                <input id="date" type="text" name="to_date" class="text date_picker" value= "" />
            </p>

            <p >

                <?php if ($userType == SUPER_ADMIN): ?>
                    <label for="group_id">
                        Group:
                    </label>
                        <?php if (!empty ($groups)): ?>
                        <select name="group_id" id = 'group_id' class="styled">
                            <option value="0">- Search By Group -</option>
                            <?php foreach ($groups as $group): ?>

                            <?php if($group['group_id'] != 1): ?>
                            <option value="<?php echo $group['group_id'] ?>">
                                <?php echo $group['associated_name'] ?>
                            </option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    <?php endif ?>
            </p>

            <p style="text-align: center">
                <input type="submit" value="Download" id="submit-event" class="submit medium" />
                <input type="button" value="Cancel" id="submit-cancel" class="submit small" />
            </p>

        </form>

    </div>		<!-- .block_content ends -->
</div>		<!-- .block ends -->

<script type="text/javascript">

    $(function(){
        $('#submit-cancel').click(function(){
            window.location = '/schedule/';
        });
    });
</script>