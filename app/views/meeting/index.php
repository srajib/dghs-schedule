<div class="block">

    <div class="block_head">
        <h2>All Meetings</h2>
        <ul>

        <?php if ($this->session->userdata('userType') != USER): ?>
                <li><a href="<?php echo site_url("meeting/add") ?>">Add Meeting</a></li> |
        <?php endif; ?>
                 <li><a href="<?php echo site_url("meeting/printMeeting") ?>">Print</a></li>
            </ul>


        <form id="reportForm" method="POST" action="<?php echo site_url('meeting/index')?>">
            <?php if (!empty ($venues)): ?>

            <select name="venue_id" id = 'venue_id'>
                <option value="">- Search By Venue -</option>
                <?php foreach ($venues as $venue): ?>


                <option value="<?php echo $venue['venue_id'] ?>" <?php echo ($this->input->post('venue_id') == $venue['venue_id']) ? "selected = 'selected'" : '' ?> >
                    <?php echo $venue['title'] ?>
                </option>


                <?php endforeach; ?>
            </select>

            <?php endif; ?>
        </form>

    </div> <!--.block_head ends -->

    <div class="block_content">

        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th class="centered">Topic</th>
                <th class="centered">Venue Name</th>
                <th class="centered">Starting Time</th>
                <th class="centered">Ending Time</th>
                <th class="action">Action</th>
            </tr>

            <?php if (empty ($meetings)) : ?>

            <tr>
                <td colspan="5" class="nodatamsg">No meeting has found.</td>
            </tr>

            <?php else : foreach($meetings as $row) : ?>

            <tr>
                <td class="centered"><a href="<?php echo site_url("meeting/viewMeeting/id/{$row['venue_meeting_id']}") ?>"><?php echo $row['topic'] ?></a></td>
                <td class="centered"><?php echo $row['title'] ?></td>
                <td class="centered"><?php echo $row['starting_date_time'] ?></td>
                <td class="centered"><?php echo $row['ending_date_time'] ?></td>


                <td class="action">
                    <a href="<?php echo site_url("meeting/edit/{$row['venue_meeting_id']}") ?>">Edit</a> |
                    <a href="<?php echo site_url("meeting/delete/id/{$row['venue_meeting_id']}") ?>" id='delete'>Delete</a>
                </td>
            </tr>

            <?php endforeach; endif ?>

        </table>

        <div class="pagination right">
            <?php echo $this->pagination->create_links() ?>
        </div> <!--.pagination ends-->

    </div> <!--.block_content ends-->

</div> <!--.block ends-->

<script type="text/javascript">
    $(function(){
        $('#venue_id').live('change', function(){
            $('#reportForm').submit();
        });
    });
</script>