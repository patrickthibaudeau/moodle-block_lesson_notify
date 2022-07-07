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
class DBLogs {

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
            $logs = $DB->get_records('lesson_notify_db_logs');
        } else {
            $logs = new \stdClass();
        }
        $this->logs = $logs;
    }
    
    public function getTable(){
        global $DB;
        $logs = $this->logs;
        
        $html = '<table id="blockLessonNotifyDBLogsTable" class="table">';
        $html .= '   <thead>';
        $html .= '       <tr>';
        $html .= '           <th>';
        $html .= '               ' . 'page';
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . 'function';
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . 'description';
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . 'result';
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . 'Time sent';
        $html .= '           </th>';
        $html .= '       </tr>';
        $html .= '   </thead>';
        $html .= '   <tbody>';
        foreach ($logs as $l) {
            $html .= '       <tr>';
            $html .= '           <td>';
            $html .= '               ' . $l->page;
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $l->action;
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $l->description;
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $l->result;
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
