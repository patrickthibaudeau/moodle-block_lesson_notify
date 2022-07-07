<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Trigger
 *
 * @author patrick
 */
class Trigger implements crud
{

    private $id;
    private $name;
    private $cmid;
    private $cmName;
    private $courseId;
    private $messageTemplateId;
    private $messageTemplateName;
    private $sendOn;
    private $sendOnDate;
    private $userId;
    private $editorFirstName;
    private $editorLastName;
    private $specificDate;
    private $isComplete;
    private $timeCreated;
    private $timeCreatedHr;
    private $timeModified;
    private $timeModifiedHr;
    private $groupId;
    private $groupingId;
    private $sendafterdays;

    /**
     * 
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $id 
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB;        //Required to use get mod from cmid
        require_once($CFG->dirroot . '/question/editlib.php');

        if ($id > 0) {
            //Get the record
            $trigger = $DB->get_record('lesson_notify_trigger', array('id' => $id));
            $TEMPLATE = new Template($trigger->messagetemplateid);
            if ($trigger->cmid != 0) {

                $mod = get_module_from_cmid($trigger->cmid);
                //print_object($mod);

                $this->cmName = $mod[0]->name;
                $this->sendOn = $trigger->sendon;
                switch ($trigger->sendon) {
                    case Base::ACTIVITY_AVAILABLE:
                        switch ($mod[1]->modname) {
                            case 'lesson':
                                $this->sendOnDate = $mod[0]->available;
                                break;
                            case 'assign':
                                $this->sendOnDate = $mod[0]->allowsubmissionsfromdate;
                                break;
                            case 'quiz':
                                $this->sendOnDate = $mod[0]->timeopen;
                                break;
                            case 'questionnaire':
                                $this->sendOnDate = $mod[0]->opendate;
                                break;
                        }
                        //Update the specific date
                        //I am adding this here because no event is thrown when updating the lesson settings.
                        //This way, the specific date will always be that of the current lesson. 
                        $DB->update_record('lesson_notify_trigger', array('id' => $id, 'specificdate' => $this->sendOnDate));
                        break;
                    case Base::ACTIVITY_START:
                        switch ($mod[1]->modname) {
                            case 'lesson':
                                //Will be triggered by event lesson_started
                                $this->sendOnDate = 1;
                                break;
                            case 'assign':
                                //Assignment does not have a start event. 
                                $this->sendOnDate = $mod[0]->allowsubmissionsfromdate;
                                break;
                        }
                        break;
                    case Base::ACTIVITY_SPECIFIC_DATE:
                        $this->sendOnDate = $trigger->specificdate;
                        break;
                    case Base::ACTIVITY_NOT_COMPLETED:
                        $this->sendOnDate = $trigger->specificdate;
                        break;
                    case Base::ACTIVITY_COMPLETED:
                        $this->sendOnDate = 0;
                        break;
                }
            }
            //This else block is for course triggers (triggers not associated to an activity)
            else {
                $this->cmName = '';
                $this->sendOn = $trigger->sendon;

                switch ($trigger->sendon) {
                    case Base::COURSE_AFTER_ENROLLMENT:
                        $this->sendOnDate = 0;
                        break;
                    case Base::ACTIVITY_SPECIFIC_DATE:
                        $this->sendOnDate = $trigger->specificdate;
                        break;
                }
            }



            $this->id = $id;
            $this->name = $trigger->name;
            $this->cmid = $trigger->cmid;

            $this->courseId = $trigger->courseid;
            $this->messageTemplateId = $trigger->messagetemplateid;
            $this->messageTemplateName = $TEMPLATE->getName();
            $this->userId = $trigger->userid;
            //get User
            $user = $DB->get_record('user', array('id' => $trigger->userid));
            $this->editorFirstName = $user->firstname;
            $this->editorLastName = $user->lastname;

            $this->specificDate = $trigger->specificdate;
            $this->isComplete = $trigger->iscomplete;
            $this->timeCreated = $trigger->timecreated;
            $this->timeCreatedHr = date('d-m-Y H:i', $trigger->timecreated);
            $this->timeModified = $trigger->timemodified;
            $this->timeModifiedHr = date('d-m-Y H:i', $trigger->timemodified);
            $this->groupId = $trigger->groupid;
            $this->groupingId = $trigger->groupingid;
            $this->sendafterdays = $trigger->sendafterdays;
        }
        else {
            $this->id = '';
            $this->name = '';
            $this->cmid = '';
            $this->cmName = '';
            $this->courseId = '';
            $this->messageTemplateId = '';
            $this->userId = '';
            $this->editorFirstName = '';
            $this->editorLastName = '';
            $this->value = '';
            $this->timeCreated = '';
            $this->timeCreatedHr = '';
            $this->timeModified = '';
            $this->timeModifiedHr = '';
            $this->groupId = 0;
            $this->groupingId = 0;
            $this->sendafterdays = 0;
        }
    }

    public function insert($data)
    {
        global $DB;
        $id = $DB->insert_record('lesson_notify_trigger', $data);
        return $id;
    }

    public function update($data)
    {
        global $DB;
        $DB->update_record('lesson_notify_trigger', $data);
    }

    public function delete()
    {
        global $DB;
        $DB->delete_records('lesson_notify_trigger', array('id' => $this->id));
        return true;
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getCmid()
    {
        return $this->cmid;
    }

    function getCmName()
    {
        return $this->cmName;
    }

    function getCourseId()
    {
        return $this->courseId;
    }

    function getMessageTemplateId()
    {
        return $this->messageTemplateId;
    }

    public function getMessageTemplateName()
    {
        return $this->messageTemplateName;
    }

    function getSendOn()
    {
        return $this->sendOn;
    }

    function getSendOnDate()
    {
        return $this->sendOnDate;
    }

    function getUserId()
    {
        return $this->userId;
    }

    function getEditorFirstName()
    {
        return $this->editorFirstName;
    }

    function getEditorLastName()
    {
        return $this->editorLastName;
    }

    function getSpecificDate()
    {
        return $this->specificDate;
    }

    function getIsComplete()
    {
        return $this->isComplete;
    }

    function getTimeCreated()
    {
        return $this->timeCreated;
    }

    function getTimeCreatedHr()
    {
        return $this->timeCreatedHr;
    }

    function getTimeModified()
    {
        return $this->timeModified;
    }

    function getTimeModifiedHr()
    {
        return $this->timeModifiedHr;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getGroupingId()
    {
        return $this->groupingId;
    }

    public function getSendAfterDays()
    {
        return $this->sendafterdays;
    }

}
