<div class="block">

    <div class="block_head">
        <h2>All Venues</h2>
        <ul>
            <li><a href="<?php echo site_url("venue/add") ?>">Add Venue</a></li>
        </ul>

    </div> <!--.block_head ends -->

    <div class="block_content">

        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th class="centered">Venue Name</th>
                <th class="action">Action</th>
            </tr>

            <?php if (empty ($venues)) : ?>

            <tr>
                <td colspan="4" class="nodatamsg">No venue has found.</td>
            </tr>

            <?php else : foreach($venues as $row) : ?>

            <tr>
                <td class="centered"><?php echo $row['title'] ?></td>

                <td class="action">
                    <a href="<?php echo site_url("venue/edit/{$row['venue_id']}") ?>">Edit</a> |
                    <a href="<?php echo site_url("venue/delete/id/{$row['venue_id']}") ?>" id='delete'>Delete</a>
                </td>
            </tr>

            <?php endforeach; endif ?>

        </table>

        <div class="pagination right">
            <?php echo $this->pagination->create_links() ?>
        </div> <!--.pagination ends-->

    </div> <!--.block_content ends-->

</div> <!--.block ends-->