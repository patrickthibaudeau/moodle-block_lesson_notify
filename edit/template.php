<?php

require_once('../config.php');
include("template_form.php");

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

    $id = optional_param('id', 0, PARAM_INT);
    $courseId = required_param('courseid', PARAM_INT);
   


    //Set principal parameters
    $context = context_course::instance($courseId);

    if (!has_capability('block/lesson_notify:addinstance', $context)) {
        redirect($CFG->wwwroot);
    }

    if ($id) {
        $formdata = $DB->get_record('lesson_notify_templates', array('id' => $id), '*', MUST_EXIST);

        //Loading text into editor
        $draftid_message = file_get_submitted_draft_itemid('message_box');
        $messageText = file_prepare_draft_area($draftid_message, $context->id, 'block_lesson_notify', 'message', $id, \block_lesson_notify\Base::getEditorOptions($context), $formdata->message);
        $formdata->message_box = array('text' => $messageText, 'format' => FORMAT_HTML, 'itemid' => $draftid_message);
    } else {
        $formdata = new stdClass();
        $formdata->id = 0;
        $formdata->courseid = $courseId;
        $formdata->global = 0;
        $formdata->userid = $USER->id;
    }

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/edit/template.php?id=' . $id . '&courseid=' . $courseId, get_string('pluginname', 'block_lesson_notify'), get_string('message_template', 'block_lesson_notify'), $context);

    $mform = new template_form(null, array('formdata' => $formdata));

// If data submitted, then process and store.
    if ($mform->is_cancelled()) {
        redirect($CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php?courseid=' . $courseId);
    } else if ($data = $mform->get_data()) {
        //Prepare data
        $data->message = $data->message_box['text'];

        $originalCourseId = $data->courseid;
        if ($data->global == 1) {
            $data->courseid = 1;
        }

        if ($data->id) {
            $data->timemodified = time();
            $TEMPLATE = new \block_lesson_notify\Template($data->id);
            $TEMPLATE->update($data);
            $recordId = $data->id;
        } else {
            $data->timecreated = time();
            $data->timemodified = time();
            $TEMPLATE = new \block_lesson_notify\Template();
            $recordId = $TEMPLATE->insert($data);
        }

        //Saving editor text and files
        $draftid_message = file_get_submitted_draft_itemid('message_box');
        $messageText = file_save_draft_area_files($draftid_message, $context->id, 'block_lesson_notify', 'message', $recordId, \block_lesson_notify\Base::getEditorOptions($context), $data->message_box['text']);

        //Update message to include the editor files
        if (isset($TEMPLATE)) {
            unset($TEMPLATE);
        }

        $TEMPLATE = new \block_lesson_notify\Template($recordId);
        $updateData = new stdClass();
        $updateData->id = $recordId;
        $updateData->message = $messageText;

        $TEMPLATE->update($updateData);

        redirect($CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php?courseid=' . $originalCourseId);
    }


    //--------------------------------------------------------------------------
    echo $OUTPUT->header();
    //**********************
    //*** DISPLAY HEADER ***


    $mform->display();
    //**********************
    //*** DISPLAY FOOTER ***
    //**********************
    echo $OUTPUT->footer();
}

display_page();
?>
