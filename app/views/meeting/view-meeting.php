<div class="block">

    <div class="block_head">
        <h2>View Meeting - <?php echo $meeting['topic'] ?></h2>
    </div>

    <div class="block_content event-details">

        <span class="title">Starting Date: </span>
        <span class="description"> <?php echo ($meeting['startingDate']." (".date('l', strtotime($meeting['startingDate'])).")" ) ?></span><br /><br />
        <span class="title">Starting Time: </span>
        <span class="description"> <?php echo $meeting['startingTime'] ?></span><br /><br />


        <span class="title">Ending Date: </span>
        <span class="description"> <?php echo ($meeting['endingDate']." (".date('l', strtotime($meeting['endingDate'])).")" ) ?></span><br /><br />
        <span class="title">Ending Time: </span>
        <span class="description"> <?php echo $meeting['endingTime'] ?></span><br /><br />



        <span class="title">Venue: </span>
        <span class="description"> <?php echo $meeting['title'] ?></span><br /><br />

        <span class="title">Associated Group: </span>
        <span class="description"><?php echo $meeting['associated_name'] ?></span><br /><br />



        <span class="title">Meeting Description: </span>
        <span class="description"><?php echo $meeting['description'] ?></span><br /><br />

        <span class="title">Conatact Person: </span>
        <span class="description"><?php echo $meeting['contact_person'] ?></span><br /><br />

        <div style="float: right">
            <a href="javascript:history.go(-1)" style="padding: 0 30px;">Click here to go back</a>
        </div>

    </div>		<!-- .block_content ends -->

</div>		<!-- .block ends -->