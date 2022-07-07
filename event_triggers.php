<?php

defined('MOODLE_INTERNAL') || die();

require_once('config.php');

function sendNotification($event) {
    global $CFG, $DB;

    $data = $event->get_data();

    $NOTIFICATION = new \block_lesson_notify\Notification();

    $NOTIFICATION->sendMessageLessonStart($data['contextinstanceid'], $data['userid']);
    \block_lesson_notify\Base::DBLog('event_triggers.php', 'sendNotification', $data, $NOTIFICATION);
}

function sendLessonCompleted($event) {
    global $CFG, $DB;

    $data = $event->get_data();

    $NOTIFICATION = new \block_lesson_notify\Notification();

    $NOTIFICATION->sendMessageLessonCompleted($data['contextinstanceid'], $data['userid']);
    \block_lesson_notify\Base::DBLog('event_triggers.php', 'sendLessonCompleted', $data, $NOTIFICATION);
}

function sendMessageReceived($event) {
    $data = $event->get_data();
    $NOTIFICATION = new \block_lesson_notify\Notification();

    $NOTIFICATION->sendEmailMessageReceived($data['userid'], $data['relateduserid']);
}

//karl thibaudeau 7/23/2020
//Commented to resume production
function sendQuizSubmitted($event) {
//    global $CFG;
//    file_put_contents($CFG->dataroot . '/event2.txt', json_encode($event->get_data()));
    $data = $event->get_data();
//    print_object($data);


    $NOTIFICATION = new \block_lesson_notify\Notification();

    $NOTIFICATION->sendQuizSubmitted($data['courseid'], $data['contextinstanceid'], $data['userid']);
}


function sendQuestionnaireSubmitted($event) {
    //    global $CFG;
    //    file_put_contents($CFG->dataroot . '/event2.txt', json_encode($event->get_data()));
        $data = $event->get_data();
    //    print_object($data);
        $NOTIFICATION = new \block_lesson_notify\Notification();
    
        $NOTIFICATION->sendQuestionnaireSubmitted($data['courseid'], $data['contextinstanceid'], $data['userid']);
}
    

function updateEnrolmentTable($event) {
       global $CFG, $DB;
       file_put_contents('/var/www/moodledata/temp/enrol.json', json_encode($event->get_data()));
        $data = $event->get_data();
       print_object($data);
       $params = [
           'userid' => $data['relateduserid'],
           'courseid' => $data['courseid'],
           'timecreated' => time()
       ];

       $DB->insert_record('lesson_notify_enrolment', $params);

}