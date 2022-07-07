<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace block_lesson_notify;

/**
 * Description of Templates
 *
 * @author patrick
 */
class Templates {

    private $courseTemplates;
    private $globalTemplates;
    private $courseTable;
    private $globalTable;
    private $courseTemplatesArray;
    private $globalTemplatesArray;
    private $templatesArray;

    /**
     * 
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $courseId
     */
    public function __construct($courseId) {
        global $CFG, $DB;
        $courseTemplates = $DB->get_records('lesson_notify_templates', array('courseid' => $courseId), 'name');
        $globalTemplates = $DB->get_records('lesson_notify_templates', array('global' => 1), 'name');
        
        $this->courseTemplates = $courseTemplates;
        $this->globalTemplates = $globalTemplates;

        $courseTemplatesArray = array();
        $courseTemplatesArray[0] = get_string('select', 'block_lesson_notify');
        foreach ($courseTemplates as $ct) {
            $courseTemplatesArray[$ct->id] = $ct->name;
        }
        $this->courseTemplatesArray = $courseTemplatesArray;

        $globalTemplatesArray = array();
        foreach ($globalTemplates as $gt) {
            $globalTemplatesArray[$gt->id] = $gt->name;
        }
        $this->globalTemplatesArray = $globalTemplatesArray;

        $templatesArray = $courseTemplatesArray + $globalTemplatesArray;
        $this->templatesArray = $templatesArray;
    }

    public function getCourseTemplates() {
        return $this->courseTemplates;
    }

    public function getGlobalTemplates() {
        return $this->globalTemplates;
    }

    public function getCourseTemplatesArray() {
        return $this->courseTemplatesArray;
    }

    public function getGlobalTemplatesArray() {
        return $this->globalTemplatesArray;
    }

    public function getTemplatesArray() {
        return $this->templatesArray;
    }

    public function getCourseTable() {
        global $CFG;
        $templates = $this->courseTemplates;

        $html = '<table id="blocklesson_notifyCourseTemplatesTable" class="table">';
        $html .= '   <thead>';
        $html .= '       <tr>';
        $html .= '           <th>';
        $html .= '               ' . get_string('name', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('subject', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('modified_by', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('time_modified', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '       </tr>';
        $html .= '   </thead>';
        $html .= '   <tbody>';
        foreach ($templates as $t) {
            if (isset($TEMPLATE)) {
                unset($TEMPLATE);
            }
            $TEMPLATE = new Template($t->id);
            $html .= '       <tr>';
            $html .= '           <td>';
            $html .= '               <a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/template.php?id=' . $t->id . '&courseid=' . $TEMPLATE->getCourseId() . '">' . $TEMPLATE->getName() . '</a> ';
            $html .= '               <span class="pull-right"><a href="#" onclick="deleteTemplate(' . $t->id . ')"><i class="fa fa-trash"></i></a> ';
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getSubject();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getEditorFirstName() . ' ' . $TEMPLATE->getEditorLastName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getTimeModifiedHr();
            $html .= '           </td>';
            $html .= '       </tr>';
        }
        $html .= '   </body>';
        $html .= '</table>';

        return $html;
    }

    public function getGlobalTable() {
        global $CFG;

        $templates = $this->globalTemplates;
        $context = \context_course::instance(1);

        $html = '<table id="blocklesson_notifyGlobalTemplatesTable" class="table">';
        $html .= '   <thead>';
        $html .= '       <tr>';
        $html .= '           <th>';
        $html .= '               ' . get_string('name', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('subject', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('modified_by', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '           <th>';
        $html .= '               ' . get_string('time_modified', 'block_lesson_notify');
        $html .= '           </th>';
        $html .= '       </tr>';
        $html .= '   </thead>';
        $html .= '   <tbody>';
        foreach ($templates as $t) {
            if (isset($TEMPLATE)) {
                unset($TEMPLATE);
            }
            $TEMPLATE = new Template($t->id);
            $html .= '       <tr>';
            $html .= '           <td>';
            if (has_capability('block/lesson_notify:site', $context)) {
                $html .= '               <a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/template.php?id=' . $t->id . '&courseid=' . $TEMPLATE->getCourseId() . '">' . $TEMPLATE->getName() . '</a> ';
                $html .= '               <span class="pull-right"><a href="#" onclick="deleteTemplate(' . $t->id . ')"><i class="fa fa-trash"></i></a> ';
            } else {
                $TEMPLATE->getName();
            }
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getSubject();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getEditorFirstName() . ' ' . $TEMPLATE->getEditorLastName();
            $html .= '           </td>';
            $html .= '           <td>';
            $html .= '               ' . $TEMPLATE->getTimeModifiedHr();
            $html .= '           </td>';
            $html .= '       </tr>';
        }
        $html .= '   </body>';
        $html .= '</table>';

        return $html;
    }

}
