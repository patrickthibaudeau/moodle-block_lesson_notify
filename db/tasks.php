<?php

/**
 * *************************************************************************
 * *                       shadow_program                                 **
 * *************************************************************************
 * @package     local                                                     **
 * @subpackage  shadow_program                                            **
 * @name        shadow_program                                            **
 * @copyright   Glendon ITS                                               **
 * @link        http://www.glendon.yorku.ca/its                           **
 * @author      Patrick Thibaudeau                                        **
 * @author      Glendon ITS                                               **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => 'block_lesson_notify\task\send_lesson_emails',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'
    ),
     array(
        'classname' => 'block_lesson_notify\task\send_course_trigger_emails',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'
    ),
);