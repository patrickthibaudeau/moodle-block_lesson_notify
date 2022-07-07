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
class template_form extends moodleform {

    function definition() {

        global $CFG, $USER, $DB;
        $formdata = $this->_customdata['formdata'];
        $mform = & $this->_form;

        $context = context_course::instance($formdata->courseid);
//-------------------------------------------------------------------------------
// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('message', 'block_lesson_notify'));
        $mform->addElement("hidden", "id");
        $mform->setType("id", PARAM_INT);
        $mform->addElement("hidden", "courseid");
        $mform->setType("courseid", PARAM_INT);
        $mform->addElement("hidden", "userid");
        $mform->setType("userid", PARAM_INT);
        /*
         * Name
         */
        $mform->addElement('text', 'name', get_string('name', 'block_lesson_notify'));
        $mform->addHelpButton('name', 'name', 'block_lesson_notify');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required', 'block_lesson_notify'), 'required');
        /*
         * Subject
         */
        $mform->addElement('text', 'subject', get_string('subject', 'block_lesson_notify'));
        $mform->addHelpButton('subject', 'subject', 'block_lesson_notify');
        $mform->setType('subject', PARAM_TEXT);
        $mform->addRule('subject', get_string('required', 'block_lesson_notify'), 'required');
//        /*
//         * Lang
//         */
//        $mform->addElement('select', 'lang', get_string('lang', 'block_lesson_notify'),['fr'=>'Français', 'en'=>'English']);
//        $mform->addHelpButton('lang', 'lang', 'block_lesson_notify');
//        $mform->addRule('lang', get_string('required', 'block_lesson_notify'), 'required');
        /*
         * Message
         */
        $mform->addElement('editor', 'message_box', get_string('message', 'block_lesson_notify'),'', null, \block_lesson_notify\Base::getEditorOptions($context));
        $mform->setType('message_box', PARAM_RAW);
        $mform->addRule('message_box', get_string('required', 'block_lesson_notify'), 'required');
        /*
         * Global Only available to those that have the persmission
         */
        if (has_capability('block/lesson_notify:site', $context)) {
            $mform->addElement('header', 'options', get_string('options', 'block_lesson_notify'));
            $mform->addElement('selectyesno', 'global', get_string('global', 'block_lesson_notify'));
            $mform->addHelpButton('global', 'global', 'block_lesson_notify');
        } else {
            $mform->addElement('hidden', 'global');
        }
        $mform->setType('global', PARAM_INT);
//-------------------------------------------------------------------------------
// add standard buttons, common to all modules
        $this->add_action_buttons();

// set the defaults
        $this->set_data($formdata);
    }

}
