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
class LessonLogs {

    private $courseId;
    private $logs;
    

    /**
     * 
     * @global \moodle_database $DB
     * @param int $courseId
     */
    public function __construct($courseId = 0) {
        global $DB;

        if ($courseId > 0) {
            $logs = $DB->get_records('lesson_notify_logs', array('courseid' => $courseId));
        } else {
            $logs = new \stdClass();
        }
        $this->logs = $logs;
    }
    
    public function getTable(){
        global $DB;
        $logs = $this->logs;
        
        $html = '<table id="blockLessonNotifyLogsTable" class="table">';
        $html .= '   <thead>';
        $html .= '       <tr>';
        $html .= '           <th>';
        $html .= '               ' . get_string('lesson_name', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('message_template', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('trigger_name', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('sentto', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('senton', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '       </tr>';
        $html .= '   </thead>';
        $html .= '   <tbody>';
        foreach ($logs as $l) {
            if (isset($TRIGGER)) {
                unset($TRIGGER);
            }
            $TRIGGER = new Trigger($l->triggerid);
            $student = $DB->get_record('user', array('id' => $l->sentto));
            $html .= '       <tr>';
            $html .= '           <td>';
            $html .= '               ' .$TRIGGER->getCmName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TRIGGER->getMessageTemplateName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TRIGGER->getName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . fullname($student);
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . strftime(get_string('strftimedaydate'), $l->timecreated);
            $html .= '           </td>';
            $html .= '       </tr>';
        }
        $html .= '   </body>';
        $html .= '</table>';

        return $html;
    }

}
