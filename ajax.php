<?php

/**
 * *************************************************************************
 * *                              webapp                                  **
 * *************************************************************************
 * @package     local                                                     **
 * @subpackage  webapp                                                    **
 * @name        webapp                                                    **
 * @copyright   Glendon ITS                                               **
 * @link        http://www.glendon.yorku.ca                               **
 * @author                                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
require_once('config.php');

$action = required_param('action', PARAM_TEXT);

global $USER;

switch ($action) {
    case 'deleteTemplate':
        $id = required_param('id', PARAM_INT);
        $TEMPLATE = new \block_lesson_notify\Template($id);

        if ($courseId = $TEMPLATE->delete($id)) {
            $TEMPLATES = new \block_lesson_notify\Templates($courseId);
            $results = array();
            $results['courseTemplates'] = $TEMPLATES->getCourseTable();
            $results['globalTemplates'] = $TEMPLATES->getGlobalTable();
            
            echo json_encode($results);
        } else {
            echo 'err';
        }
        break;
    case 'deleteTrigger':
        $id = required_param('id', PARAM_INT);
        $cmId = required_param('cmid', PARAM_INT);
        $courseId = required_param('courseid', PARAM_INT);
        
        $TRIGGER = new \block_lesson_notify\Trigger($id);

        if ($trigger = $TRIGGER->delete()) {
            $TRIGGERS = new \block_lesson_notify\Triggers($cmId,$courseId);
            echo $TRIGGERS->getTable();
        } else {
            echo 'err';
        }
        break;
}

