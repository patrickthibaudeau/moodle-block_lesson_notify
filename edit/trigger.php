<?php

//Required to use get mod from cmid

require_once('../config.php');
include("trigger_form.php");

/**
 * Display the content of the page
 * @global stdobject $CFG
 * @global moodle_database $DB
 * @global core_renderer $OUTPUT
 * @global moodle_page $PAGE
 * @global stdobject $SESSION
 * @global stdobject $USER
 */
function display_page()
{
    // CHECK And PREPARE DATA
    global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $USER;

    require_once($CFG->dirroot . '/question/editlib.php');

    require_login(1, FALSE);

    $id = optional_param('id', 0, PARAM_INT);
    $cmId = optional_param('cmid', 0, PARAM_INT);
    $courseId = required_param('courseid', PARAM_INT);



    //Set principal parameters
    $context = context_course::instance($courseId);

    if (!has_capability('block/lesson_notify:addinstance', $context)) {
        redirect($CFG->wwwroot);
    }

    if ($id) {
        $formdata = $DB->get_record('lesson_notify_trigger', array('id' => $id), '*', MUST_EXIST);
        $formdata->userid = $USER->id;
        $formdata->group = $formdata->groupid;
        $formdata->grouping = $formdata->groupingid;
    }
    else {
        $formdata = new stdClass();
        $formdata->id = 0;
        $formdata->courseid = $courseId;
        $formdata->cmid = $cmId;
        $formdata->messagetemplateid = 0;
        $formdata->userid = $USER->id;
        $formdata->iscomplete = 1;
    }

    $headerTitle = '';
    if ($cmId != 0) {
        $mod = get_module_from_cmid($cmId);
        $headerTitle = $mod[0]->name;
    }
    else {
        $headerTitle = get_course($courseId)->fullname;
    }

    $header = get_string('trigger', 'block_lesson_notify') . ' - ' . $headerTitle;

    echo \block_lesson_notify\Base::page($CFG->wwwroot . '/blocks/lesson_notify/edit/trigger.php?id=' . $id . '&courseid=' . $courseId, get_string('pluginname', 'block_lesson_notify'), $header, $context);

    $mform = new trigger_form(null, array('formdata' => $formdata));
    // If data submitted, then process and store.
    if ($mform->is_cancelled()) {
        redirect($CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?courseid=' . $courseId . '&cmid=' . $cmId);
    }
    //If form not cancelled
    else if ($data = $mform->get_data()) {
        //Prepare data
        //Remove fileds that are not required
        unset($data->group);
        unset($data->grouping);
        if ($data->id) {
            $data->timemodified = time();
            $TRIGGER = new \block_lesson_notify\Trigger($data->id);
            $TRIGGER->update($data);
            $recordId = $data->id;
        }
        else {
            $data->timecreated = time();
            $data->timemodified = time();
            $TRIGGER = new \block_lesson_notify\Trigger();
            if ($data->sendon == 1) {
                $mod = get_module_from_cmid($data->cmid);
                switch ($mod[1]->modname) {
                    case 'lesson':
                        $data->specificdate = $mod[0]->available;
                        break;
                    case 'assign':
                        $data->specificdate = $mod[0]->allowsubmissionsfromdate;
                        break;
                    case 'quiz':
                        $data->specificdate = $mod[0]->timeopen;
                        break; 
                        //Added by Karl Thibaudeau 3/15/2022
                        //Allow questionnaire type questions to send messages on avaliablility date/time
                    case 'questionnaire':
                        $data->specificdate = $mod[0]->opendate;
                        break;
                }
            }

            $recordId = $TRIGGER->insert($data);
        }
        //Update message to include the editor files
        if (isset($TEMPLATE)) {
            unset($TEMPLATE);
        }

        redirect($CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?courseid=' . $data->courseid . '&cmid=' . $data->cmid);
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
