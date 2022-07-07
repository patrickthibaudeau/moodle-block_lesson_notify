<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Template
 *
 * @author patrick
 */
class Template implements crud {
    
    private $id;
    private $name;
    private $subject;
    private $message;
    private $global;
    private $courseId;
    private $userId;
    private $editorFirstName;
    private $editorLastName;
    private $timeCreated;
    private $timeCreatedHr;
    private $timeModified;
    private $timeModifiedHr;
    
    /**
     * 
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $id
     */
    public function __construct($id = 0) {
        global $CFG, $DB;
        if ($id > 0) {
            //Get the record
            $template = $DB->get_record('lesson_notify_templates', array('id' => $id));
            
            $this->id = $id;
            $this->name = $template->name;
            $this->subject = $template->subject;
            $this->message = $template->message;
            $this->global = $template->global;
            $this->courseId = $template->courseid;
            $this->userId = $template->userid;
            //get User
            $user = $DB->get_record('user', array('id' => $template->userid));
            
            $this->editorFirstName = $user->firstname;
            $this->editorLastName = $user->lastname;
            $this->timeCreated = $template->timecreated;
            $this->timeCreatedHr = date('d-m-Y H:i',$template->timecreated);
            $this->timeModified = $template->timemodified;
            $this->timeModifiedHr = date('d-m-Y H:i',$template->timemodified);
        } else {
            $this->id = $id;
            $this->name = '';
            $this->subject = '';
            $this->message = '';
            $this->global = '';
            $this->courseId = '';
            $this->userId = '';
            $this->editorFirstName = '';
            $this->editorLastName = '';
            $this->timeCreated = '';
            $this->timeCreatedHr = '';
            $this->timeModified = '';
            $this->timeModifiedHr = '';
        }
    } 
    /**
     * 
     * @global \moodle_database $DB
     * @param \stdClass $data
     * @return int
     */
    public function insert($data) {
        global $DB;
        $id = $DB->insert_record('lesson_notify_templates', $data);
        return $id;
    }
    
    /**
     * 
     * @global \moodle_database $DB
     * @param \stdClass $data
     */
    public function update($data) {
        global $DB;
        $DB->update_record('lesson_notify_templates', $data);;
    }
    
    /**
     * 
     * @global \moodle_database $DB
     * @param \stdClass $data
     */
    public function delete() {
        global $DB;
        $DB->delete_records('lesson_notify_templates', array('id' => $this->id));
        $DB->delete_records('lesson_notify_trigger', array('messagetemplateid' => $this->id));
        return $this->courseId;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getGlobal() {
        return $this->global;
    }

    public function getCourseId() {
        return $this->courseId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getEditorFirstName() {
        return $this->editorFirstName;
    }

    public function getEditorLastName() {
        return $this->editorLastName;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getTimeCreatedHr() {
        return $this->timeCreatedHr;
    }

    public function getTimeModified() {
        return $this->timeModified;
    }

    public function getTimeModifiedHr() {
        return $this->timeModifiedHr;
    }


}
