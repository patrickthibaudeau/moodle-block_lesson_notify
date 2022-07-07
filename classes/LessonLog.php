<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Logs
 *
 * @author patrick
 */
class LessonLog {

    private $id;
    private $cmId;
    private $cmName;
    private $courseId;
    private $courseName;
    private $messageTemplateId;
    private $messageTemplateName;
    private $triggerId;
    private $triggerName;
    private $sentToId;
    private $sentToName;
    private $timeCreated;
    private $timeCreatedHr;

    /**
     * 
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $id
     */
    public function __construct($id = 0) {
        global $CFG, $DB;

        require_once($CFG->dirroot . '/question/editlib.php');

        if ($id > 0) {
            $log = $DB->get_record('lesson_notify_logs', array('id' => $id));

            $mod = get_module_from_cmid($log->cmid);
            $course = get_course($log->courseid);
            $messageTemplate = new Template($log->messagetemplateid);
            $trigger = new Trigger($log->triggerId);
            $sentTo = user_get_user_details($log->senttoid);

            $this->id = $id;
            $this->cmId = $log->cmid;
            $this->cmName = $mod[0]->name;
            $this->courseId = $log->courseid;
            $this->courseName = $course->fullname;
            $this->messageTemplateId = $messageTemplate->getId();
            $this->messageTemplateName = $messageTemplate->getName();
            $this->triggerId = $trigger->getId();
            $this->triggerName = $trigger->getName();
            $this->sentToId = $log->senttoid;
            $this->sentToName = fullname($sentTo);
            $this->timeCreated = $log->timecreated;
            $this->timeCreatedHr = strftime(get_string('strftimedaydate'), $log->timecreated);
        } else {
            $this->id = '';
            $this->cmId = '';
            $this->cmName = '';
            $this->courseId = '';
            $this->courseName = '';
            $this->messageTemplateId = '';
            $this->messageTemplateName = '';
            $this->triggerId = '';
            $this->triggerName = '';
            $this->sentToId = '';
            $this->sentToName = '';
            $this->timeCreated = '';
            $this->timeCreatedHr = '';
        }
    }
    
    /**
     * Inserts into logs table
     * @global \moodle_database $DB
     * @param array $data with the following keys: cmid, courseid,messagetemplateid, triggerid,senttoid
     */
    public function insert($data) {
        global $DB;
        $data['timecreated'] = time();
        $DB->insert_record('lesson_notify_logs', $data);
    }

    public function getId() {
        return $this->id;
    }

    public function getCmId() {
        return $this->cmId;
    }

    public function getCmName() {
        return $this->cmName;
    }

    public function getCourseId() {
        return $this->courseId;
    }

    public function getCourseName() {
        return $this->courseName;
    }

    public function getMessageTemplateId() {
        return $this->messageTemplateId;
    }

    public function getMessageTemplateName() {
        return $this->messageTemplateName;
    }

    public function getTriggerId() {
        return $this->triggerId;
    }

    public function getTriggerName() {
        return $this->triggerName;
    }

    public function getSentToId() {
        return $this->sentToId;
    }

    public function getSentToName() {
        return $this->sentToName;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getTimeCreatedHr() {
        return $this->timeCreatedHr;
    }

}
