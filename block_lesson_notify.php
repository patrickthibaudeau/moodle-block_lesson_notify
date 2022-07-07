<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Newblock block caps.
 *
 * @package    block_lesson_notify
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class block_lesson_notify extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_lesson_notify');
    }

    function get_content() {
        global $CFG, $OUTPUT,$USER, $COURSE;
        
        $mods = \block_lesson_notify\Base::getCourseModules($COURSE->id);

        
         if (empty(get_config('block_lesson_notify', 'lesson_notify_mailer')) || (empty(get_config('block_lesson_notify', 'lesson_notify_fakeurl')))) {
           $this->content = new stdClass();  
            return $this->content->text = get_string('settings_missing', 'block_lesson_notify');
        }

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);
        //Added by Karl Thibaudeau on 6/4/2020
        //Allow user to manage course triggers, such as to send on * day after course enrollment. 
        $html = '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?cmid=' . 0 . '&courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . get_string('course_triggers', 'block_lesson_notify') . '</a><br>'; 
        $html .= '<div style="margin-top:10px;"><b>'. get_string('course_lessons', 'block_lesson_notify') . '</b><div>';
        foreach ($mods as $cmid => $mod) {
           
            if($mod->type == 'lesson'){
           $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?cmid=' . $cmid . '&courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . $mod->name . '</a><br>'; 
            }
        }
        //Added by Karl Thibaudeau on may 3rd 2022 to allow Assignments to have triggers
        $html .= '<div style="margin-top:10px;"><b>'. get_string('course_assignments', 'block_lesson_notify') . '</b></div>';
        foreach ($mods as $cmid => $mod) {
            if($mod->type == 'assign'){
                $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?cmid=' . $cmid . '&courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . $mod->name . '</a><br>';
            }
        }
        //Quizes
         $html .= '<div style="margin-top:10px;"><b>'. get_string('course_quizzes', 'block_lesson_notify') . '</b></div>';
        foreach ($mods as $cmid => $mod) {
            if($mod->type == 'quiz'){
           $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?cmid=' . $cmid . '&courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . $mod->name . '</a><br>'; 
            }
        }
        //$has_questionnaries = ['type'->]
        //Added by Karl Thibaudeau 3/15/2022 to allow questionnaire plugin type activities to be listed and notifications to be sent
        $html .= '<div style="margin-top:10px;"><b>'. get_string('course_questionnaires', 'block_lesson_notify') . '</b></div>';
        foreach ($mods as $cmid => $mod) {
            if($mod->type == 'questionnaire'){
           $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/triggers.php?cmid=' . $cmid . '&courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . $mod->name . '</a><br>'; 
            }
        }
        //End of Karl's 3/15/2022 edits
        $html .= '<br><b>'. get_string('administration', 'block_lesson_notify') . '</b><br>';
        $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php?courseid=' . $currentcontext->instanceid . '"><i class="fa fa-envelope"></i> ' . get_string('templates', 'block_lesson_notify') . '</a><br>';
        $html .= '<a href="' . $CFG->wwwroot . '/blocks/lesson_notify/logs.php?courseid=' . $currentcontext->instanceid . '"><i class="fa fa-list"></i> ' . get_string('logs', 'block_lesson_notify') . '</a><br>';

        $this->content->text = $html;

        //Only teachers or administrators can view this block
//        if (has_capability('block/lesson_notify:addinstance', $currentcontext)) {
            if(is_siteadmin($USER)){
            return $this->content;
        } else {
            $this->content = '';
            return $this->content;
        }
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
            'site' => false,
            'site-index' => false,
            'course-view' => true,
            'course-view-social' => true,
            'mod' => true,
            'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
        return false;
    }

    function has_config() {
        return true;
    } 

    public function cron() {
        return true;
    }

}
