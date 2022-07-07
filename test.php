<?php

/**
 * *************************************************************************
 * *                          block_lesson_notify                                **
 * *************************************************************************
 * @package     block                                                     **
 * @subpackage  lesson_notify                                                    **
 * @name        lesson_notify                                                    **
 * @copyright   Oohoo IT Services Inc                                     **
 * @link        http://oohoo.biz                                          **
 * @author      Patrick Thibaudeau, Kais Abid, Maher El  Aissi            **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
require_once('config.php');
require_once($CFG->dirroot . '/question/editlib.php');

/**
 * Display the content of the page
 * @global stdobject $CFG
 * @global moodle_database $DB
 * @global core_renderer $OUTPUT
 * @global moodle_page $PAGE
 * @global stdobject $SESSION
 * @global stdobject $USER
 */
function display_page() {
    // CHECK And PREPARE DATA
    global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

     $courseId = optional_param('courseid', 1, PARAM_INT); //List id

    require_login(1, false); //Use course 1 because this has nothing to do with an actual course, just like course 1

    $context = context_course::instance($courseId);

    $pagetitle = get_string('pluginname', 'block_lesson_notify');
    $pageheading = get_string('templates', 'block_lesson_notify');

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php', $pagetitle, $pageheading, $context);

    $HTMLcontent = '';


    //**********************
    //*** DISPLAY HEADER ***
    //**********************
    echo $OUTPUT->header();

//    $initjs = "$(document).ready(function() {
//                   initPages();
//               });";
//
//    echo html_writer::script($initjs);
    //**********************
    //*** DISPLAY CONTENT **
    //**********************
    $N = new \block_lesson_notify\Notification();
    //$N->SendMessageAfterEnrollment();
    $N->sendMessageOnNotComplete();

    // email_to_user($USER, $USER, 'Hello world', 'Hello again', '<b>Hello  again</b>');
    //**********************
    //*** DISPLAY FOOTER ***
    //**********************
    echo $OUTPUT->footer();
}

display_page();
?>