<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" href="<?php echo site_url('assets/css/print.css'); ?>" media="print" />
    <script type="text/javascript" src="<?php echo site_url('assets/js/jquery.js') ?>"></script>
</head>

<body>

    <div class="block">

        <div id = 'logo'><img src="<?php echo site_url('assets/images/ministry.gif') ?>" alt=""/> </div>
        <div id ='health'>
            <span class="title">Government of the People's Republic of Bangladesh</span>
            <span class="description">Ministry of Health and Family Welfare, Bangladesh</span>
            <span class="heading">Meeting Place</span>
        </div>

        <div class="block_content">

            <table id='print-table' cellpadding="3" cellspacing="0" width="100%" border="1px">

                <thead>

                    <tr>
                        <th class="centered" width="20%">Starting Date</th>
                        <th class="centered" width="20%">Ending Date</th>
                        <th class="centered" width="80%">Venue & Topic</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty ($meetings)) : ?>

                <tr>
                    <td colspan="3" class="nodatamsg">No event is available.</td>
                </tr>

                <?php else : foreach($meetings AS $meeting) :
                    
                    $starting = explode(" ", $meeting['starting_date_time']);
                    $Ending = explode(" ", $meeting['ending_date_time']);
                    $startingDate = DateHelper::mysqlToHuman($starting[0]);
                    $startingTime = date("g:i a", STRTOTIME($starting[1]));
                    $endingDate = DateHelper::mysqlToHuman($Ending[0]);
                    $endingTime = date("g:i a", STRTOTIME($Ending[1])); ?>

                <tr>
                    <td class="centered" align="center"> <?php echo $startingDate.", ".date('l', strtotime($startingDate)) ?>
                        <br/><?php echo $startingTime ?> </td>
                     <td class="centered" align="center"><?php echo $endingDate.", ".date('l', strtotime($endingDate)) ?>
                         <br/><?php echo $endingTime ?> </td>
                    <td >
                        Venue: <?php echo $meeting['title'] ?>. Topic: <?php echo $meeting['topic']; ?>
                        <?php echo empty ($meeting['description']) ? '' : '. Description: '.($meeting['description'].'.') ?>
                    </td>
                </tr>

                <?php endforeach; endif ?>

                </tbody>

            </table>

            <div id = 'go-back' style="float: right; padding-top: 30px">
                <a href="javascript:history.go(-1)" style="padding: 0 30px;">Click here to go back</a>
            </div>

        </div> <!--.block_content ends-->

    </div> <!--.block ends-->

</body>
</html>

<style type="text/css">

    #logo {
        float: left;
        width: 100px;
    }

    #logo img {
        height: 75px;
        width: 75px;
    }

    #health {
        text-align:center;
    }

    #health h2 {
        line-height: 0.10em;
        font-size: 18px;
    }

    #health span.title, span.description {
        line-height: 0.5em;
        display: block;
        padding-top: 12px;
    }

    #health span.title {
        font-size: 18px;
    }

    #health span.description {
        font-size: 16px;
    }

    #health span.heading {
        display: block;
        font-size: 22px;
        font-weight: bold;
        padding-top: 12px;
    }

    /*body {*/
        /*margin: 0 auto;*/
        /*width:960px;*/
    /*}*/

    .block_content {
        clear: both;
    }

    #print-table {
        margin-top: 10px;
    }

</style>

<script language="JavaScript" type="text/javascript">

    $(document).ready(function(){
        window.print();
    });

</script>

