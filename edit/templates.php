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

    $courseId = optional_param('courseid', 0, PARAM_INT); //List id



    require_login(1, false); //Use course 1 because this has nothing to do with an actual course, just like course 1

    $context = context_course::instance($courseId);

    $pagetitle = get_string('pluginname', 'block_lesson_notify');
    $pageheading = get_string('templates', 'block_lesson_notify');

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php', $pagetitle, $pageheading, $context);

    $HTMLcontent = '';

    $TEMPLATES = new \block_lesson_notify\Templates($courseId);
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
    echo \block_lesson_notify\Base::navBar($courseId,  $CFG->wwwroot . '/blocks/lesson_notify/edit/template.php?courseid=' . $courseId);
    ?>

    <div class="span12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title"><?php echo get_string('course_templates', 'block_lesson_notify'); ?></h4>
                <p class="card-text">
                <div id="blocklesson_notifyCourseTable">
                    <?php echo $TEMPLATES->getCourseTable(); ?>
                </div>
                </p>
            </div>
        </div>
    </div>

    <div class="span12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title"><?php echo get_string('global_templates', 'block_lesson_notify'); ?></h4>
                <p class="card-text">
                <div id="blocklesson_notifyGlobalTable">
                    <?php echo $TEMPLATES->getGlobalTable(); ?>
                </div>
                </p>
            </div>
        </div>
    </div>

<div id="deleteTemplateConfirmationBox" title="<?php echo get_string('delete_template', 'block_lesson_notify');?>">
    <?php echo get_string('delete_template_confirmation', 'block_lesson_notify');?>
</div>
    <?php
    //**********************
    //*** DISPLAY FOOTER ***
    //**********************
    echo $OUTPUT->footer();
}

display_page();
?>