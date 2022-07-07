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
require_once('../config.php');

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

    //Karl Thibaudeau June 9, 2020
    //Changed cmId from a required param to an optional param to allow course specific triggers (not associated to lessons)
    $cmId = optional_param('cmid', 0, PARAM_INT); //List id
    //Changed courseId from an optoional param to an required param for error handling.
    $courseId = required_param('courseid', PARAM_INT); //List id

    require_once($CFG->dirroot . '/question/editlib.php');

    require_login(1, false); //Use course 1 because this has nothing to do with an actual course, just like course 1

    $context = context_course::instance($courseId);

    $page_context_name = '';
    //Karl Thibaudeau June 9, 2020
    //If the cmId is 0, then it is a course wide trigger. This handles whether the header of the page will show the activity name or the course name.
    if ($cmId != 0) {
        $mod = get_module_from_cmid($cmId);
        $page_context_name = $mod[0]->name;
    } else {
        $course = get_course($courseId);
        $page_context_name = $course->fullname;
    }
    $pagetitle = get_string('pluginname', 'block_lesson_notify');
    $pageheading = get_string('triggers', 'block_lesson_notify') . ' - ' . $page_context_name;
    $card_title = $cmId == 0 ? get_string('course_triggers', 'block_lesson_notify') : 'Activity triggers ';

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php', $pagetitle, $pageheading, $context);

    $HTMLcontent = '';

    $TRIGGERS = new \block_lesson_notify\Triggers($cmId, $courseId);
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


    echo \block_lesson_notify\Base::navBar($courseId, $CFG->wwwroot . '/blocks/lesson_notify/edit/trigger.php?cmid=' . $cmId . '&courseid=' . $courseId);
    ?>

    <div class="span12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title"><?php echo $card_title; ?></h4>
                <p class="card-text">
                <div id="blocklesson_notifyTriggersTable">
                    <?php echo $TRIGGERS->getTable(); ?>
                </div>
                </p>
            </div>
        </div>
    </div>

    <div id="deleteTriggerConfirmationBox" title="<?php echo get_string('delete_trigger', 'block_lesson_notify'); ?>">
        <?php echo get_string('delete_trigger_confirmation', 'block_lesson_notify'); ?>
    </div>
    <?php
    //**********************
    //*** DISPLAY FOOTER ***
    //**********************
    echo $OUTPUT->footer();
}

display_page();
?>