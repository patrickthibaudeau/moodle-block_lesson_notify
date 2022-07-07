<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Notification
 *
 * @author patrick
 */
class Notification {

    private $triggerAvailable;
    private $triggerCustomDate;
    private $triggerNotCompleted;
    private $mailer;
    private $fakeURL;

    /**
     * 
     * @global \moodle_database $DB
     */
    public function __construct() {
        global $DB;

        $today = date('d-m-Y', time());
        $from = strtotime($today . ' 00:00:00');
        $to = strtotime($today . ' 23:59:59');

        $triggerAvailableSql = 'SELECT * FROM {lesson_notify_trigger} WHERE specificdate BETWEEN ? AND ? AND sendon = ' . Base::ACTIVITY_AVAILABLE;
        $triggerAvailable = $DB->get_records_sql($triggerAvailableSql, array($from, $to));

        $triggerCustomDateSql = 'SELECT * FROM {lesson_notify_trigger} WHERE specificdate BETWEEN ? AND ? AND sendon = ' . Base::ACTIVITY_SPECIFIC_DATE;
        $triggerCustomDate = $DB->get_records_sql($triggerCustomDateSql, array($from, $to));

        $triggerNotCompletedSql = 'SELECT * FROM {lesson_notify_trigger} WHERE specificdate BETWEEN ? AND ? AND sendon = ' . Base::ACTIVITY_NOT_COMPLETED;
        $triggerNotCompleted = $DB->get_records_sql($triggerNotCompletedSql, array($from, $to));

        $this->triggerAvailable = $triggerAvailable;
        $this->triggerCustomDate = $triggerCustomDate;
        $this->triggerNotCompleted = $triggerNotCompleted;
        $this->fakeURL = get_config('block_lesson_notify', 'lesson_notify_fakeurl');
        //get the mailer profile
        $this->mailer = $DB->get_record('user', ['id' => get_config('block_lesson_notify', 'lesson_notify_mailer')]);
    }

    /**
     * 
     * @global \moodle_database $DB
     */
    public function sendMessageOnLessonAvailable() {
        global $DB, $CFG;
        $LOG = new LessonLog();
        $triggers = $this->triggerAvailable;
        foreach ($triggers as $t) {
            $cm = get_coursemodule_from_id(null, $t->cmid);
            $lesson = $DB->get_record($cm->modname, array('id' => $cm->instance));
            //Get students
            if ($t->groupid != 0) {
                $students = $this->getGroupStudents($t->groupid);
            } else if ($t->groupingid != 0) {
                $students = $this->getGroupingStudents($t->groupingid);
            } else {
                $students = $this->getEnrolledStudents($t->courseid);
            }
            //Get message template
            $MESSAGE = new Template($t->messagetemplateid);
            $subject = $MESSAGE->getSubject();
            $messageHtml = $MESSAGE->getMessage();
            //Person who created the trigger
            $from = $DB->get_record('user', array('id' => $t->userid));
            //Send to students
            foreach ($students as $student) {
                $data = array();
                $data['cmid'] = $t->cmid;
                $data['courseid'] = $t->courseid;
                $data['messagetemplateid'] = $t->messagetemplateid;
                $data['triggerid'] = $t->id;
                $data['sentto'] = $student->id;
                //Only send if it has not been sent previously

                $subject = get_string('generic_subject', 'block_lesson_notify');
                if (!$logged = $DB->get_record('lesson_notify_logs', $data)) {
                    if (self::sendInternalMessage($subject, $messageHtml, $from, $student)) {
                        $LOG->insert($data);
                        $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $student, $this->mailer);
                    }
                }
            }
        }
    }

    /**
     * 
     * @global \moodle_database $DB
     */
    public function sendMessageOnCustomDate() {
        global $DB, $CFG;
        $LOG = new LessonLog();
        $triggers = $this->triggerCustomDate;

        foreach ($triggers as $t) {
            $cm = get_coursemodule_from_id(null, $t->cmid);
            $lesson = $DB->get_record($cm->modname, array('id' => $cm->instance));

            //Get students
            if ($t->groupid != 0) {
                $students = $this->getGroupStudents($t->groupid);
            } else if ($t->groupingid != 0) {
                $students = $this->getGroupingStudents($t->groupingid);
            } else {
                $students = $this->getEnrolledStudents($t->courseid);
            }

            //Get message template
            $MESSAGE = new Template($t->messagetemplateid);
            $subject = $MESSAGE->getSubject();
            $messageHtml = $MESSAGE->getMessage();
            //Person who created the trigger
            $from = $DB->get_record('user', array('id' => $t->userid));
            //Send to students
            foreach ($students as $student) {
                $data = array();
                $data['cmid'] = $t->cmid;
                $data['courseid'] = $t->courseid;
                $data['messagetemplateid'] = $t->messagetemplateid;
                $data['triggerid'] = $t->id;
                $data['sentto'] = $student->id;
                //Only send if it has not been sent previously
                $subject = get_string('generic_subject', 'block_lesson_notify');
                if (!$logged = $DB->get_record('lesson_notify_logs', $data)) {
                    if (self::sendInternalMessage($subject, $messageHtml, $from, $student)) {
                        $LOG->insert($data);
                       $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $student, $this->mailer);
                    }
                }
            }
        }
    }

    /**
     * 
     * @global \moodle_database $DB
     */
    public function sendMessageOnNotComplete() {
        global $DB;
        $LOG = new LessonLog();
        $triggers = $this->triggerNotCompleted;
        foreach ($triggers as $t) {

//            $lesson = $DB->get_record($cm->modname, array('id' => $cm->instance));
            //Get students
            if ($t->groupid != 0) {
                $students = $this->getGroupStudents($t->groupid);
            } else if ($t->groupingid != 0) {
                $students = $this->getGroupingStudents($t->groupingid);
            } else {
                $students = $this->getEnrolledStudents($t->courseid);
            }
            $completed = false;
            //Get message template
            $MESSAGE = new Template($t->messagetemplateid);
            $subject = $MESSAGE->getSubject();
            $messageHtml = $MESSAGE->getMessage();
            //Person who created the trigger
            $from = $DB->get_record('user', array('id' => $t->userid));
            //Send to students


            foreach ($students as $student) {
//                $lessonTimer = $DB->get_record('lesson_timer', array('lessonid' => $cm->instance, 'userid' => $student->id));
                $completed = $DB->record_exists('course_modules_completion', array('coursemoduleid' => $t->cmid, 'userid' => $student->id, 'completionstate' => 1));
                //Only send if activity is not completed
                if ($completed == false) {
                    $data = array();
                    $data['cmid'] = $t->cmid;
                    $data['courseid'] = $t->courseid;
                    $data['messagetemplateid'] = $t->messagetemplateid;
                    $data['triggerid'] = $t->id;
                    $data['sentto'] = $student->id;
                    //Only send if it has not been sent previously
                    $subject = get_string('generic_subject', 'block_lesson_notify');

                    if (!$logged = $DB->get_record('lesson_notify_logs', $data)) {
                        if (self::sendInternalMessage($subject, $messageHtml, $from, $student)) {
                            $LOG->insert($data);
                            $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $student, $this->mailer);
                        }
                    }
                }
            }
        }
    }

    /**
     * 
     * @global \stdClass $CFG
     * @param int $courseId
     * @return array
     */

    protected function getEnrolledStudents($courseId) {
        global $CFG;
        $courseContext = \context_course::instance($courseId);
        $enrolledUsers = get_enrolled_users($courseContext, 'moodle/grade:view'); //Get students only

        return $enrolledUsers;
    }

    /**
     * Get students in group
     * @global \stdClass $CFG
     * @param int $groupId
     * @return array
     */
    protected function getGroupStudents($groupId) {
        global $CFG;
        $members = groups_get_members($groupId);

        return $members;
    }

    /**
     * Get students in grouping
     * @global \stdClass $CFG
     * @param int $groupId
     * @return array
     */
    protected function getGroupingStudents($groupingId) {
        global $CFG;
        $members = groups_get_grouping_members($groupingId);

        return $members;
    }

    /**
     * 
     * @param int $messageTemplateId
     * @param \stdClass $student USER object
     * @param \stdClass $from USER object
     */

    protected function sendMessage($subject, $messageHtml, $student, $from) {
        $messageHtml = str_replace('[firstname]', $student->firstname, $messageHtml);
        $messageHtml = str_replace('[prénom]', $student->firstname, $messageHtml);
        $messageHtml = str_replace('[lastname]', $student->lastname, $messageHtml);
        $messageHtml = str_replace('[nom]', $student->lastname, $messageHtml);
        $messageHtml = str_replace('[surname]', $student->lastname, $messageHtml);
        $messageHtml = str_replace('[username]', $student->username, $messageHtml);

      if (email_to_user($student, $from, $subject, $messageHtml, $messageHtml)) {
          return true;
      }
      return false;
    }

    protected static function sendInternalMessage($subject, $messageHtml, $fromUser, $toUser) {
        global $CFG;
        require_once($CFG->dirroot . '/message/externallib.php');
        $messageHtml = str_replace('[firstname]', $toUser->firstname, $messageHtml);
        $messageHtml = str_replace('[prénom]', $toUser->firstname, $messageHtml);
        $messageHtml = str_replace('[lastname]', $toUser->lastname, $messageHtml);
        $messageHtml = str_replace('[nom]', $toUser->lastname, $messageHtml);
        $messageHtml = str_replace('[surname]', $toUser->lastname, $messageHtml);
        $messageHtml = str_replace('[username]', $toUser->username, $messageHtml);

        message_update_processors('popup');
        $messageID = message_post_message($fromUser, $toUser, $messageHtml, FORMAT_HTML);
        return $messageID;
    }

    /**
     * 
     * @global \moodle_database $DB
     * @param int $cmId
     * @param int $userId
     */

    public function sendMessageLessonStart($cmId, $userId) {
        global $DB, $CFG;
        $LOG = new LessonLog();
        //In case they create more than one message, find them all. Will prevent error.
        if ($triggers = $DB->get_records('lesson_notify_trigger', array('cmid' => $cmId, 'sendon' => Base::ACTIVITY_START))) {
            foreach ($triggers as $t) {
                $MESSAGE = new Template($t->messagetemplateid);
                $subject = $MESSAGE->getSubject();
                $user = $DB->get_record('user', array('id' => $userId));
                $from = $DB->get_record('user', array('id' => $t->userid)); //Person who created the trigger

                $messageHtml = $MESSAGE->getMessage();
                $messageHtml = str_replace('[firstname]', $user->firstname, $messageHtml);
                $messageHtml = str_replace('[prénom]', $user->firstname, $messageHtml);
                $messageHtml = str_replace('[lastname]', $user->lastname, $messageHtml);
                $messageHtml = str_replace('[nom]', $user->lastname, $messageHtml);
                $messageHtml = str_replace('[surname]', $user->lastname, $messageHtml);

                $messageID = self::sendInternalMessage($subject, $messageHtml, $from, $user);

                if ($messageID) {
                    $data = array();
                    $data['cmid'] = $cmId;
                    $data['courseid'] = $t->courseid;
                    $data['messagetemplateid'] = $t->messagetemplateid;
                    $data['triggerid'] = $t->id;
                    $data['sentto'] = $userId;
                    $LOG->insert($data);

                    $subject = get_string('generic_subject', 'block_lesson_notify');
                   email_to_user($user, $this->mailer, $subject, '', get_string('check_email', 'block_lesson_notify', $this->fakeURL));
                }
            }
            return true;
        }

        return false;
    }

    /**
     * 
     * @global \moodle_database $DB
     * @param int $cmId
     * @param int $userId
     */
    public function sendMessageLessonCompleted($cmId, $userId) {
        global $DB, $CFG;



        $LOG = new LessonLog();
        //In case they create more than one message, find them all. Will prevent error.
        if ($triggers = $DB->get_records('lesson_notify_trigger', array('cmid' => $cmId, 'sendon' => Base::ACTIVITY_COMPLETED))) {
            foreach ($triggers as $t) {
                $MESSAGE = new Template($t->messagetemplateid);
                $subject = $MESSAGE->getSubject();
                $user = $DB->get_record('user', array('id' => $userId));
                $from = $DB->get_record('user', array('id' => $t->userid)); //Person who created the trigger

                $messageHtml = $MESSAGE->getMessage();
                $messageHtml = str_replace('[firstname]', $user->firstname, $messageHtml);
                $messageHtml = str_replace('[prénom]', $user->firstname, $messageHtml);
                $messageHtml = str_replace('[lastname]', $user->lastname, $messageHtml);
                $messageHtml = str_replace('[nom]', $user->lastname, $messageHtml);
                $messageHtml = str_replace('[surname]', $user->lastname, $messageHtml);



                $data = array();
                $data['cmid'] = $cmId;
                $data['courseid'] = $t->courseid;
                $data['messagetemplateid'] = $t->messagetemplateid;
                $data['triggerid'] = $t->id;
                $data['sentto'] = $userId;

                $subject = get_string('generic_subject', 'block_lesson_notify');
                if (self::sendInternalMessage($subject, $messageHtml, $from, $user)) {
                    $LOG->insert($data);
                    $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $user, $this->mailer);
                }
            }
            return true;
        }

        return false;
    }

    public function sendEmailMessageReceived($fromid, $toid) {
        // global $DB;
        // $LOG = new LessonLog();
        // $from = $DB->get_record('user', array('id' => $fromid));
        // $to = $DB->get_record('user', array('id' => $toid));

        // $generic_subject = 'You received a message';
        // $check_email = 'Please check your ' . $this->fakeURL . ' account - a message has been sent to you.';
        
        
        // //If block_oqanalyst table exists
        // $oq_exists = $DB->table_exists('block_oqanalyst');
        // if ($oq_exists) {
        //     $oq_record = $DB->get_record('block_oqanalyst', ['user_id' => $toid]);
        //     //If they have fr as language.
        //     if ($oq_record->lang == 'fr') {
        //         $generic_subject = 'Vous avez reçu un message';
        //         $check_email = 'Prière de vérifier votre compte ' . $this->fakeURL . ' - un message vous a été envoyé.';
        //     }
        // }

        // $data = array();
        // $data['sentto'] = $to->id;
        // if (email_to_user($to, $this->mailer, $generic_subject, $check_email)) {
        //     $LOG->insert($data);
        // }
    }

    public function sendQuestionnaireSubmitted($courseid, $contextinstanceid, $userid) {
        global $DB, $CFG;
        $idnumber = strtolower(trim(get_config('block_lesson_notify', 'lesson_notify_pretherapie')));
        $submittedquizcm = $DB->get_record('course_modules', ['id' => $contextinstanceid]);

        if (strtolower(trim($submittedquizcm->idnumber)) == $idnumber) {
            //This means we're in the right quiz. Proceed.    
            //Get the Teacher/Clinician
            $context = \context_course::instance($courseid);
            $participants = get_enrolled_users($context);
            foreach ($participants as $p) {
                $role = get_user_roles($context, $p->id);
                $role = array_pop($role);
                if ($role->roleid == 3 || $role->shortname == 'editingteacher') {
                    $teacher = $p;
                    continue;
                }
            }
            $LOG = new LessonLog();
            $teacher = $DB->get_record('user', array('id' => $teacher->id));
//            print_object($teacher);
            $data = array();
            $data['sentto'] = $teacher->id;
            $data['cmid'] = $contextinstanceid;
            $data['courseid'] = $courseid;
            // if (email_to_user($teacher, $this->mailer, get_string('pretherapie_submitted', 'block_lesson_notify'), get_string('pretherapie_submitted_txt', 'block_lesson_notify', $this->fakeURL))) {
            //     //$LOG->insert($data);
            // }
            $subject = get_string('generic_subject', 'block_lesson_notify');
            if (self::sendInternalMessage($subject, $messageHtml, $from, $user)) {
                $LOG->insert($data);
                $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $user, $this->mailer);
            }
        } else {
            self::sendMessageLessonCompleted($contextinstanceid, $userid);
        }
    }

    public function sendQuizSubmitted($courseid, $contextinstanceid, $userid) {
        global $DB, $CFG;

        $idnumber = strtolower(trim(get_config('block_lesson_notify', 'lesson_notify_pretherapie')));
        $submittedquizcm = $DB->get_record('course_modules', ['id' => $contextinstanceid]);

        if (strtolower(trim($submittedquizcm->idnumber)) == $idnumber) {
            //This means we're in the right quiz. Proceed.    
            //Get the Teacher/Clinician
            $context = \context_course::instance($courseid);
            $participants = get_enrolled_users($context);
            foreach ($participants as $p) {
                $role = get_user_roles($context, $p->id);
                $role = array_pop($role);
                if ($role->roleid == 3 || $role->shortname == 'editingteacher') {
                    $teacher = $p;
                    continue;
                }
            }
            $LOG = new LessonLog();
            $teacher = $DB->get_record('user', array('id' => $teacher->id));
//            print_object($teacher);
            $data = array();
            $data['sentto'] = $teacher->id;
            $data['cmid'] = $contextinstanceid;
            $data['courseid'] = $courseid;

            

            if (email_to_user($teacher, $this->mailer, get_string('pretherapie_submitted', 'block_lesson_notify'), get_string('pretherapie_submitted_txt', 'block_lesson_notify', $this->fakeURL))) {
                $LOG->insert($data);
            }
        } else {
            self::sendMessageLessonCompleted($contextinstanceid, $userid);
        }
    }

    public function SendMessageAfterEnrollment() {
        global $DB, $CFG;

        $LOG = new LessonLog();
        
        //In case they create more than one message, find them all. Will prevent error.
        if ($triggers = $DB->get_records('lesson_notify_trigger', array('cmid' => 0, 'sendon' => Base::COURSE_AFTER_ENROLLMENT))) {

            foreach ($triggers as $t) {
                //for each trigger, find the participants of the corresponding course and depending on their enrollment date and the triggers sendafterdays field, send the notification 
                $courseId = $t->courseid;
                //get all user enrollments for that course
                $userEnrollments = $DB->get_records('lesson_notify_enrolment', array('courseid' => $courseId));
                $sendAfterDate = $t->sendafterdays;

                foreach ($userEnrollments as $userEnrollment) {

                    $userId = $userEnrollment->userid;

                    //Find users enrollment date
                    $dateDiff = time() - $userEnrollment->timecreated;
                    $daysDiff = floor($dateDiff / (60 * 60 * 24 ));

                    if ($daysDiff == $sendAfterDate) {

                        $MESSAGE = new Template($t->messagetemplateid);
                        $subject = $MESSAGE->getSubject();
                        $user = $DB->get_record('user', array('id' => $userId));
                        $from = $DB->get_record('user', array('id' => $t->userid)); //Person who created the trigger

                        $messageHtml = $MESSAGE->getMessage();
                        $messageHtml = str_replace('[firstname]', $user->firstname, $messageHtml);
                        $messageHtml = str_replace('[prénom]', $user->firstname, $messageHtml);
                        $messageHtml = str_replace('[lastname]', $user->lastname, $messageHtml);
                        $messageHtml = str_replace('[nom]', $user->lastname, $messageHtml);
                        $messageHtml = str_replace('[surname]', $user->lastname, $messageHtml);


                        $data = array();
                        $data['cmid'] = $cmId ?? 0;
                        $data['courseid'] = $t->courseid;
                        $data['messagetemplateid'] = $t->messagetemplateid;
                        $data['triggerid'] = $t->id;
                        $data['sentto'] = $userId;
                        
                        //Only send if it has not been sent previously
                        if (self::sendInternalMessage($subject, $messageHtml, $from, $user)) {
                            $LOG->insert($data);
                            $this->sendMessage($subject, get_string('check_email', 'block_lesson_notify', $this->fakeURL), $user, $this->mailer);

                            // Delete enrolment user
                            $DB->delete_records('lesson_notify_enrolment', ['id' => $userEnrollment->id]);

                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

}
