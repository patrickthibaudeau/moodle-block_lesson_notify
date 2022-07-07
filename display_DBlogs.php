<?php
require_once('config.php');

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
    global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $USER;

    require_login(1, FALSE);

    $courseId = required_param('courseid', PARAM_INT);

    //Set principal parameters
    $context = context_course::instance($courseId);

    if (!has_capability('block/lesson_notify:addinstance', $context)) {
        redirect($CFG->wwwroot);
    }

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/display_DBlogs.php?courseid=' . $courseId, get_string('pluginname', 'block_lesson_notify'), get_string('logs', 'block_lesson_notify'), $context);

    $LOGS = new \block_lesson_notify\DBLogs($courseId);
    $initjs = "$(document).ready(function() {
        initDBLogs();
    });";
    //--------------------------------------------------------------------------
    echo $OUTPUT->header();
    //**********************
    //*** DISPLAY HEADER ***
    echo html_writer::script($initjs);
    echo \block_lesson_notify\Base::navBar($courseId);
    ?>
    <div class="span12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-block">
                <h4 class="card-title"><?php echo get_string('course_logs', 'block_lesson_notify'); ?></h4>
                <p class="card-text">
                <div id="blocklesson_notifyLogsTable">
                    <?php echo $LOGS->getTable(); ?>
                </div>
                </p>
            </div>
        </div>
    </div>
    <?php
    //**********************
    //*** DISPLAY FOOTER ***
    //**********************
    echo $OUTPUT->footer();
}

display_page();
?>
