<?php declare(strict_types=1);

/**
 * Global variables and constants will be defined in this page
 * These variables and constants may be used in multiple pages.
 * Below we start a database connection.
 * Since PHP in moving to PDO and MySQLi, we no longer use MySQL.
 * PHP version 7+
 * 
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
session_start();
ob_start();

require_once 'site_configuration/site_info.php';
require_once 'includes/private_header.php';

if (Authenticate::isLoggedIn()) {
    /* date settings */
    $month = (int) (isset($_GET['month']) ? $_GET['month'] : date('m'));
    $year = (int)  (isset($_GET['year']) ? $_GET['year'] : date('Y'));
    $month_array = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

    /* select month control */
    $select_month_control = '<select name="month" id="month" class="form-control">';
    for ($x = 1; $x <= 12; $x++) {
        $select_month_control .= '<option value="' . $x . '"' . ($x != $month ? '' : ' selected="selected"') . '>' . date('F', mktime(0, 0, 0, $x, 1, $year)) . '</option>';
    }
    $select_month_control .= '</select>';

    /* select year control */
    $year_range = 7;
    $select_year_control = '<select name="year" id="year" class="form-control">';
    for ($x = ($year - floor($year_range / 2)); $x <= ($year + floor($year_range / 2)); $x++) {
        $select_year_control .= '<option value="' . $x . '"' . ($x != $year ? '' : ' selected="selected"') . '>' . $x . '</option>';
    }
    $select_year_control .= '</select>';

    /* "next month" control */
    $next_month_link = '<a href="?month=' . ($month != 12 ? $month + 1 : 1) . '&year=' . ($month != 12 ? $year : $year + 1) . '" class="control btn btn-default">Next Month >></a>';

    /* "previous month" control */
    $previous_month_link = '<a href="?month=' . ($month != 1 ? $month - 1 : 12) . '&year=' . ($month != 1 ? $year : $year - 1) . '" class="control btn btn-default"><< Previous Month</a>';

    /* bringing the controls together */
    $controls = '<form method="get" class="form-inline">' . $previous_month_link . ' ' . $select_month_control . $select_year_control . ' <button type="submit" class="btn btn-success">Go</button> ' . $next_month_link . ' </form>';

    echo $controls;

    /**
     *  Draws a calendar
     *
     * @param int $month Include a value for month
     * @param int $year  Include a value for year
     * 
     * @return void
     */
    function draw_calendar($month, $year)
    {
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar .= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++) {
            $calendar .= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        }

        /* keep going with days.... */
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++) {
            if ($list_day == date("d") && $month == date("n") && $year == date("Y")) {
                $calendar .= '<td class="calendar-day today">';
            } else {
                $calendar .= '<td class="calendar-day">';
            }
                /* add in the day number */
                $calendar .= '<div class="day-number">' . $list_day . '</div>';

                // QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                $calendar.= str_repeat('<p> </p>', 2);

            $calendar.= '</td>';
            if ($running_day == 6) {
                $calendar .= '</tr>';
                if (($day_counter + 1) != $days_in_month) {
                    $calendar .= '<tr class="calendar-row">';
                }
                $running_day = -1;
                $days_in_this_week = 0;
            }
            $days_in_this_week++; $running_day++; $day_counter++;
        }

        /* finish the rest of the days in the week */
        if ($days_in_this_week < 8) {
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++) {
                $calendar .= '<td class="calendar-day-np"> </td>';
            }
        }

        /* final row */
        $calendar .= '</tr>';

        /* end the table */
        $calendar .= '</table>';

        /* all done, return result */
        return $calendar;
    }

    /* sample usages */
    echo '<h2>' . $month_array[$month] . ' ' . $year . '</h2>';
    echo draw_calendar($month, $year);

} else {
    Authenticate::notLoggedIn();
}
require_once 'includes/private_footer.php';
$contents = ob_get_contents();
ob_end_flush();
echo $contents;
?>
