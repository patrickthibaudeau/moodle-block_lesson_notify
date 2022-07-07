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
 * The main location configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    NEWPLUGIN
 * @copyright  2013 Oohoo IT Services Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/lib/formslib.php");

/**
 * Module instance settings form
 */
class trigger_form extends moodleform {

    function definition() {

        global $CFG, $USER, $DB;
        $formdata = $this->_customdata['formdata'];
        $mform = & $this->_form;
        $context = context_course::instance($formdata->courseid);

        //Added by Karl Thibaudeau on 3/15/2022
        //Hide option 'Activity avaliable' option if the avaliability of an activity is not set on the activity.

        $formdata->cmid ? $mod = get_module_from_cmid($formdata->cmid) : $mod = null;

        $mod_has_avaliability_date = false;
        if($mod[0]->opendate > 0 || $mod[0]->timeopen > 0 ||  $mod[0]->allowsubmissionsfromdate > 0 || $mod[0]->available > 0){
            $mod_has_avaliability_date = true;
        }



        $TEMPLATES = new \block_lesson_notify\Templates($formdata->courseid);
        $TRIGGERS = new \block_lesson_notify\Triggers($formdata->cmid, $formdata->courseid);
        $specificDate = $TRIGGERS->getSpecificdate();
        $sendToArray = array();
        $sendToArray[\block_lesson_notify\Base::SENDTO_ALL] = get_string('send_to_all', 'block_lesson_notify');
        $sendToArray[\block_lesson_notify\Base::SENDTO_INDIVDUALS] = get_string('send_to_individuals', 'block_lesson_notify');

        $triggerType = array();
        $dayOptions = array();
        if ($formdata->cmid != 0) {
            
            if($mod_has_avaliability_date){
            $triggerType[\block_lesson_notify\Base::ACTIVITY_AVAILABLE] = get_string('trigger_activity_start', 'block_lesson_notify');
            }
            $triggerType[\block_lesson_notify\Base::ACTIVITY_NOT_COMPLETED] = get_string('trigger_not_completed', 'block_lesson_notify');
            
            //Remove 'Activity Start' option if it's an assignment or questionnaire - Dominik May 9, 2022
            if (strcmp($mod[1]->modname,'assign') != 0 && strcmp($mod[1]->modname,'questionnaire')) {
                $triggerType[\block_lesson_notify\Base::ACTIVITY_START] = get_string('trigger_activity_started', 'block_lesson_notify');
            }
            $triggerType[\block_lesson_notify\Base::ACTIVITY_COMPLETED] = get_string('trigger_completed', 'block_lesson_notify');
      
        } else {
            $triggerType[\block_lesson_notify\Base::COURSE_AFTER_ENROLLMENT] = get_string('trigger_after_enrollment', 'block_lesson_notify');

            $dayConfig0 = 0;
            $dayConfig1 = get_config('block_lesson_notify', 'lesson_notify_choose_days_1');
            $dayConfig2 = get_config('block_lesson_notify', 'lesson_notify_choose_days_2');
            $dayConfig3 = get_config('block_lesson_notify', 'lesson_notify_choose_days_3');
            $dayConfig4 = get_config('block_lesson_notify', 'lesson_notify_choose_days_4');
            $dayConfig5 = get_config('block_lesson_notify', 'lesson_notify_choose_days_5');

            $dayOptions[$dayConfig0] = $dayConfig0;
            $dayOptions[$dayConfig1] = $dayConfig1;
            $dayOptions[$dayConfig2] = $dayConfig2;
            $dayOptions[$dayConfig3] = $dayConfig3;
            $dayOptions[$dayConfig4] = $dayConfig4;
            $dayOptions[$dayConfig5] = $dayConfig5;
        }

        $triggerType[\block_lesson_notify\Base::ACTIVITY_SPECIFIC_DATE] = get_string('trigger_specificdate', 'block_lesson_notify');

        /**
         * Get course groups
         */
        $courseGroups = \block_lesson_notify\Base::getGroups($formdata->courseid);
        $groups = array(0 => get_string('select', 'block_lesson_notify'));
        foreach ($courseGroups as $cg) {
            $groups[$cg->id] = $cg->name;
        }

        /**
         * Get course groupings
         */
        $courseGroupings = \block_lesson_notify\Base::getGroupings($formdata->courseid);
        $groupings = array(0 => get_string('select', 'block_lesson_notify'));
        foreach ($courseGroupings as $cgs) {
            $groupings[$cgs->id] = $cgs->name;
        }

//-------------------------------------------------------------------------------
// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('trigger', 'block_lesson_notify'));
        $mform->addElement("hidden", "id");
        $mform->setType("id", PARAM_INT);
        $mform->addElement("hidden", "courseid");
        $mform->setType("courseid", PARAM_INT);
        $mform->addElement("hidden", "userid");
        $mform->setType("userid", PARAM_INT);
        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);
        /*
         * Name
         */
        $mform->addElement('text', 'name', get_string('name', 'block_lesson_notify'));
        $mform->addHelpButton('name', 'name', 'block_lesson_notify');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required', 'block_lesson_notify'), 'required');

        /*
         * Message templates
         */

 
        $mform->addElement('select', 'messagetemplateid', get_string('course_messages', 'block_lesson_notify'), $TEMPLATES->getTemplatesArray());
        $mform->setType('messagetemplateid', PARAM_INT);
        $mform->addHelpButton('messagetemplateid', 'course_messages', 'block_lesson_notify');
        $mform->addRule('messagetemplateid', get_string('required', 'block_lesson_notify'), 'required');
        $mform->addRule('messagetemplateid', get_string('required', 'block_lesson_notify'), 'nonzero');
        
     
        //Link to create new message template. Just added for a bit of UI enhancement.
        $template_link = '<a class="mb-4" href="'.$CFG->wwwroot . '/blocks/lesson_notify/edit/templates.php?courseid='. $formdata->courseid .'">' . get_string('create_message_template', 'block_lesson_notify') . '</a>';
        $mform->addElement('html',$template_link);
        
        /**
         * Send to groups
         */
        $mform->addElement('select', 'group', get_string('group', 'block_lesson_notify'), $groups);
        $mform->addElement('select', 'grouping', get_string('grouping', 'block_lesson_notify'), $groupings);
        $mform->disabledIf('grouping', 'group', 'eq', '0');
        /*
         * Because disabling the field keeps the value and I need to set the value to 0
         * I use js to fill in the actual fields that I do need. 
         */
        $mform->addElement('hidden', 'groupid');
        $mform->addElement('hidden', 'groupingid');
        $mform->setType('groupid', PARAM_INT);
        $mform->setType('groupingid', PARAM_INT);
        /*
         * End on
         */
        $mform->addElement('select', 'sendon', get_string('trigger_sendon', 'block_lesson_notify'), $triggerType);
        $mform->setType('sendon', PARAM_INT);
        $mform->addRule('sendon', get_string('required', 'block_lesson_notify'), 'required');
        $mform->addHelpButton('sendon', 'trigger_sendon', 'block_lesson_notify');


        //Add availability date in plain text
        //Group is used to workaround hideIf issue https://tracker.moodle.org/browse/MDL-66251
        $group = [];
        $group[] = & $mform->createElement('static', 'plaindate', '', date("F j, Y, g:i a", $specificDate));
        $mform->addGroup($group, 'formgroup', '', ' ', false);
        $mform->hideIf('formgroup', 'sendon', 'noteq', \block_lesson_notify\Base::ACTIVITY_AVAILABLE);

        if ($formdata->cmid == 0 ) { //For course level notification triggers. Allow user to select days defined in settings
            $select = $mform->addElement('select', 'sendafterdays', get_string('days_after_enrollment_select', 'block_lesson_notify'), $dayOptions);
            $select->setSelected($dayConfig1);
            $mform->setType('sendafterdays', PARAM_INT);
            $mform->addRule('sendafterdays', get_string('required', 'block_lesson_notify'), 'required');
            $mform->addHelpButton('sendafterdays', 'trigger_sendon', 'block_lesson_notify');
        }

        /*
         * specific date
         */
        $mform->addElement('date_selector', 'specificdate', get_string('trigger_specific_date', 'block_lesson_notify'));
        $mform->setType('specificdate', PARAM_INT);


        $mform->disabledIf('specificdate', 'sendon', 'eq', \block_lesson_notify\Base::ACTIVITY_AVAILABLE);
        $mform->disabledIf('specificdate', 'sendon', 'eq', \block_lesson_notify\Base::ACTIVITY_START);
        $mform->disabledIf('specificdate', 'sendon', 'eq', \block_lesson_notify\Base::ACTIVITY_COMPLETED);
        $mform->disabledIf('specificdate', 'sendon', 'eq', \block_lesson_notify\Base::COURSE_AFTER_ENROLLMENT);
        //$mform->disabledIf('specificdate', 'sendon', 'eq', \block_lesson_notify\Base::ACTIVITY_NOT_COMPLETED);
//-------------------------------------------------------------------------------
// add standard buttons, common to all modules
        $this->add_action_buttons();

// set the defaults
        $this->set_data($formdata);
    }

}
