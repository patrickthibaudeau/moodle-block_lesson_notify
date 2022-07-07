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
 * Strings for component 'block_lesson_notify', language 'en'
 *
 * @package   block_lesson_notify
 * @copyright Daniel Neis <danielneis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['administration'] = 'Administration';
$string['blockstring'] = 'Block string';
$string['cancel'] = 'Annuler';
$string['close'] = 'Close';
$string['course_lessons'] = 'Course lessons';
$string['course_quizzes'] = 'Course quizzes';
$string['course_questionnaires'] = 'Course Questionnaires';
$string['course_messages'] = 'Message templates';
$string['course_messages_help'] = 'Select the email template message you would like to use.';
$string['course_templates'] = 'My course templates';
$string['course_triggers'] = 'Course triggers';
$string['delete'] = 'Delete';
$string['delete_template'] = 'Delete template!';
$string['delete_template_confirmation'] = 'Are you sure you want to delete this template? Deleting this template will also delete all triggered notifications linked'
        . ' to this template. This cannot be recovered.';
$string['delete_trigger'] = 'Delete trigger!';
$string['delete_trigger_confirmation'] = 'Are you sure you want to delete this trigger? This cannot be recovered.';
$string['descconfig'] = 'Description of the config section';
$string['descfoo'] = 'Config description';
$string['global'] = 'Global';
$string['global_help'] = 'Is this message a global message? Global messages are available to use in all courses throughout the site.';
$string['global_templates'] = 'Site message templates';
$string['group'] = 'Group';
$string['grouping'] = 'Grouping';
$string['headerconfig'] = 'Config section header';
$string['logs'] = 'Message logs';
$string['message'] = 'Message';
$string['message_template'] = 'Message template';
$string['modified_by'] = 'Modified by';
$string['module_affected'] = 'Module affected';
$string['module_affected_help'] = 'Select the course module that will affect this trigger';
$string['name'] = 'Name';
$string['name_help'] = 'Enter a name to identify this template';
$string['new'] = 'New';
$string['lesson_notify:addinstance'] = 'Add a lesson notification block';
$string['lesson_notify:myaddinstance'] = 'Add a lesson notification block to my moodle';
$string['lesson_notify:site'] = 'Create and edit global messages';
$string['options'] = 'Options';
$string['pluginname'] = 'Lesson notification system';
$string['required'] = 'This field is required';
$string['return_to_course'] = 'Return to course';
$string['select'] = 'Select';
$string['send_lesson_emails'] = 'Send lesson notifications';
$string['send_to'] = 'Send to';
$string['send_to_help'] = '<i><b>Send to all students<b></i> will send the message to all students in the course.<br>'
        . '<i><b>Send to individual students<b></i> will send the message to students that meet the criteria below (<i>Send on</i>.)';
$string['send_to_all'] = 'Send to all students';
$string['send_to_individuals'] = 'Send to individual students';
$string['student_starts_lesson'] = 'When the student starts';
$string['subject'] = 'Subject';
$string['subject_help'] = 'Enter an email subject for this message. No more than 255 characters';
$string['lang'] = 'Language';
$string['lang_help'] = 'Language of the message sent to the user, used in the textbox below';
$string['templates'] = 'Message templates';
$string['time_modified'] = 'Time modified';
$string['to_be_sent_on'] = 'To be sent on';
$string['trigger'] = 'Trigger';
$string['triggers'] = 'Message triggers';
$string['trigger_activity_start'] = 'Activity available';
$string['trigger_activity_started'] = 'Activity started';
$string['trigger_completed'] = 'Activity completed';
$string['trigger_not_completed'] = 'Activity not completed';
$string['trigger_specificdate'] = 'Custom date';
$string['trigger_specific_date'] = 'Send on this date';
$string['trigger_sendon'] = 'Send on';
$string['trigger_sendon_help'] = 'Select when you would like the message to be sent.<br>'
        . 'If you select <i>lesson not completed</i> or <i>Custom date</i> remember to select the date you want the message to be sent below.';

//Logs
$string['course_logs'] = 'Course logs';
$string['lesson_name'] = 'Lesson';
$string['senton'] = 'Sent on';
$string['sentto'] = 'Sent to';
$string['trigger_name'] = 'Trigger';
$string['check_email'] = 'Please check your {$a} account - a message has been sent to you.';
$string['generic_subject'] = 'You received a message';
$string['lesson_notify_settings_pretherapie'] = 'Pre therapie unique identifier';
$string['lesson_notify_settings_pretherapie_desc'] = 'Must be the same value as the idnumber in the pre-therapy quiz';
$string['pretherapie_submitted'] = 'Questionnaire Pré-Thérapie submitted';
$string['pretherapie_submitted_txt'] = 'Please check your {$a} account - The questionnaire Pré-Thérapie has been submitted.';

//settings

$string['lesson_notify_settings'] = 'Lesson notification system settings';
$string['lesson_notify_settings_question_1_title'] = 'Option 1 for days after sent';
$string['lesson_notify_settings_question_1_desc'] = 'This will show up in the dropdown list of avaliable days';
$string['lesson_notify_settings_question_2_title'] = 'Option 2 for days after sent';
$string['lesson_notify_settings_question_3_title'] = 'Option 3 for days after sent';
$string['lesson_notify_settings_question_4_title'] = 'Option 4 for days after sent';
$string['lesson_notify_settings_question_5_title'] = 'Option 5 for days after sent';
$string['lesson_notify_settings_question_6_title'] = 'Option 6 for days after sent';

//course trigger functionallity strings
$string['send_course_trigger_emails'] = 'Send course trigger notifications';
$string['trigger_after_enrollment'] = 'Send after enrollment';
$string['days_after_enrollment_select'] = 'How many days after enrollment?';
$string['lesson_notify_fakeurl'] = 'fakeurl';
$string['lesson_notify_fakeurldesc'] = 'Dummy url to be displayed in the email text';
$string['lesson_notify_mailer'] = 'mailer';
$string['lesson_notify_mailerdesc'] = 'ID of the user profile that will be sending the emails';
$string['settings_missing'] = 'Mailer and/or URL settings missing  - Please define them or contact your administrator';

$string['sendafterdays'] = '{$a} days after client enrolment in the course';

//Updated 2022
$string['create_message_template'] = 'Create Message Template';
$string['course_assignments'] = 'Course Assignments';