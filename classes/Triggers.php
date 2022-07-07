<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Triggers
 *
 * @author patrick
 */
class Triggers {

    private $cmId;
    private $courseId;
    private $course;
    private $triggers;
    private $specificdate;

    /**
     * 
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @global \stdClass $USER
     * @param int $courseId
     */
    public function __construct($cmId = null, $courseId = null) {
        global $CFG, $DB, $USER;
        $this->courseId = $courseId;
        $this->cmId = $cmId;
        $this->course = $DB->get_record('course', array('id' => $courseId));
        $this->triggers = $DB->get_records('lesson_notify_trigger', array('cmid' => $cmId));
        if ($cmId) { //This won't apply if we're dealing with course triggers
            $mod = get_module_from_cmid($cmId);
            //Dev Note: This is where the name of the 
            switch ($mod[1]->modname) {
                case 'lesson':
                    $this->specificdate = $mod[0]->available;
                    break;
                case 'assign':
                    $this->specificdate = $mod[0]->allowsubmissionsfromdate;
                    break;
                case 'quiz':
                    $this->specificdate = $mod[0]->timeopen;
                    break;
                case 'questionnaire':
                    $questionaire_date = $DB->get_record('questionnaire', array('id'=>$mod[1]->instance), 'opendate', IGNORE_MISSING);
                    $this->specificdate = $questionaire_date->opendate;
                    break;

            }
        }
    }

    public function getCmId() {
        return $this->cmId;
    }

    function getSpecificdate() {
        return $this->specificdate;
    }

    public function getCourse() {
        return $this->course;
    }

    public function getCourseId() {
        return $this->courseId;
    }

    public function getTriggers() {
        return $this->triggers;
    }

    public function getTable() {
        global $CFG;
        $triggers = $this->triggers;

        $html = '<table id="blocklesson_notifyCourseTriggersTable" class="table">';
        $html .= '   <thead>';
        $html .= '       <tr>';
        $html .= '           <th>';
        $html .= '               ' . get_string('name', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('message_template', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('modified_by', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('to_be_sent_on', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('time_modified', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '       </tr>';
        $html .= '   </thead>';
        $html .= '   <tbody>';
     //  print_object($triggers);
      //  die();
        foreach ($triggers as $t) {
            if (isset($TRIGGER)) {
                unset($TRIGGER);
            }

            $TRIGGER = new Trigger($t->id);

            if (isset($TEMPLATE)) {
                unset($TEMPLATE);
            }
            $TEMPLATE = new Template($t->messagetemplateid);
            $html .= '       <tr>';
            $html .= '           <td>';
            $html .= '               <a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/trigger.php?id=' . $t->id . '&cmid=' . $TRIGGER->getCmid() . '&courseid=' . $TRIGGER->getCourseId() . '">' . $TRIGGER->getName() . '</a> ';
            $html .= '               <span class="pull-right"><a href="#" onclick="deleteTrigger(' . $t->id . ',' . $t->cmid . ',' . $t->courseid . ')"><i class="fa fa-trash"></i></a> ';
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TRIGGER->getEditorFirstName() . ' ' . $TRIGGER->getEditorLastName();
            $html .= '           </td>';
            if ($this->cmId > 0) {
                if ($TRIGGER->getSendOn() == 0) {
                    $sendOnDate = get_string('trigger_completed', 'block_lesson_notify');
                } else if ($TRIGGER->getSendOn() == 1) {
                    $sendOnDate = get_string('trigger_activity_start', 'block_lesson_notify') . ' (' . date("F j, Y, g:i a", $this->specificdate) . ')';
                } else if ($TRIGGER->getSendOn() == 2) {
                    $sendOnDate = get_string('trigger_activity_started', 'block_lesson_notify');
                } else if ($TRIGGER->getSendOn() == 3) {
                    $sendOnDate = get_string('trigger_completed', 'block_lesson_notify');
                } else {

                    $sendOnDate = strftime(get_string('strftimedaydate'), $TRIGGER->getSpecificDate());
                }
            } else {
                if ($TRIGGER->getSendOn() == 5) {
                $sendOnDate =  strftime(get_string('strftimedaydate'), $TRIGGER->getSpecificDate());
                }
                if ($TRIGGER->getSendOn() == 6) {
                $sendOnDate = get_string('sendafterdays', 'block_lesson_notify', $TRIGGER->getSendAfterDays());
                }
            }

            $html .= '           <td>';
            $html .= '               ' . $sendOnDate;
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . strftime(get_string('strftimedaydate'), $t->timemodified);
            $html .= '           </td>';
            $html .= '       </tr>';
        }
        $html .= '   </body>';
        $html .= '</table>';

        return $html;
    }

}
