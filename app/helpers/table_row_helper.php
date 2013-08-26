<?php

if (!function_exists('makeTableRow')) {
    
    function makeTableRow($date, $schedules)
    {
        $output = "<tr>
                       <td class='centered' align='center' rowspan='"
                       . count($schedules) . "'>" . $date;
                       ;

        foreach($schedules AS $schedule) :

            $output .= "<td align='center' >" . date("g:i a", strtotime($schedule['time'])) . ((!empty($schedule['to_time']) AND ($schedule['time'] != $schedule['to_time']))?'   To   '. date("g:i a", STRTOTIME($schedule['to_time'])):'') . '</td>';
            $output .= '<td>' . $schedule['title'] . ' To grace as: ' . $schedule['grace'] . '.';
            $output .= empty ($schedule['description']) ? '' : ($schedule['description'] . '.');
            $output .= '<span style="font-weight:bold">';
            if ($schedule['is_date_not_confirmed'] == 1) {
                if ($schedule['is_time_not_confirmed'] == 1) {
                    $output .= "(Date and Time are not confirmed)";
                } else {
                    $output .= "(Date is not confirmed)";
                }
            } else if ($schedule['is_time_not_confirmed'] == 1){
                $output .= "(Time is not confirmed)";
            }

            $output .= "</span>
                        </td>
                        <td>" . $schedule['venue'] ."</td>
                </tr>
                <tr>";

        endforeach;

        $output = substr($output, 0, strlen($output) - 4);

        return $output;
    }
}