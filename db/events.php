<?php

$observers = array(
 
    array(
        'eventname'   => '\mod_lesson\event\lesson_started',
        'callback'    => 'sendNotification',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\mod_lesson\event\lesson_restarted',
        'callback'    => 'sendNotification',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\mod_lesson\event\lesson_ended',
        'callback'    => 'sendLessonCompleted',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\core\event\message_sent',
        'callback'    => 'sendMessageReceived',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\mod_quiz\event\attempt_submitted',
        'callback'    => 'sendQuizSubmitted',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\mod_questionnaire\event\attempt_submitted',
        'callback'    => 'sendQuestionnaireSubmitted',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    array(
        'eventname'   => '\mod_assign\event\assessable_submitted',
        'callback'    => 'sendLessonCompleted',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    
    //Dominik May 5, 2022
    //Quiz started
    array(
        'eventname'   => 'mod_quiz\event\attempt_started',
        'callback'    => 'sendNotification',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),

    //Dominik May 9, 2022
    //User enroled
    array(
        'eventname'   => '\core\event\user_enrolment_created',
        'callback'    => 'updateEnrolmentTable',
        'includefile' => '/blocks/lesson_notify/event_triggers.php'
    ),
    
);

