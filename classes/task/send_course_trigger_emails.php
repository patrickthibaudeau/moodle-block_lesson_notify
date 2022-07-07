<?php

/**
 * *************************************************************************
 * *                        lesson notify                                 **
 * *************************************************************************
 * @package     block                                                     **
 * @subpackage  lesson_notify                                             **
 * @name        lesson_notify                                             **
 * @copyright   Oohoo IT Services INc.                                    **
 * @link        http://oohoo.biz                                          **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */

namespace block_lesson_notify\task;

class send_course_trigger_emails extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('send_course_trigger_emails', 'block_lesson_notify');
    }

    /**
     * Run  cron.
     */
    public function execute() {
        global $CFG, $DB;
        require_once("$CFG->dirroot/blocks/lesson_notify/config.php");
        $notification = new \block_lesson_notify\Notification();
        $notification->SendMessageAfterEnrollment();
    }

}
