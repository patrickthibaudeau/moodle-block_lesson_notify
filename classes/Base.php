<?php

/**
 * *************************************************************************
 * *                       room_reservations                              **
 * *************************************************************************
 * @package     local                                                     **
 * @subpackage  room_reservations                                         **
 * @name        room_reservations                                         **
 * @copyright   Glendon ITS                                               **
 * @link        http://www.glendon.yorku.ca                               **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */

namespace block_lesson_notify;

class Base {

    const SENDTO_ALL = 1;
    const SENDTO_INDIVDUALS = 2;
    const ACTIVITY_AVAILABLE = 1;
    const ACTIVITY_START = 2; //Tiggered by event\mod_lesson\event\lesson_started 
    const ACTIVITY_COMPLETED = 3;
    const ACTIVITY_NOT_COMPLETED = 4;
    const ACTIVITY_SPECIFIC_DATE = 5;
    const COURSE_AFTER_ENROLLMENT = 6;

    /**
     * Creates the Moodle page header
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @global \moodle_page $PAGE
     * @global \stdClass $SITE
     * @param string $url Current page url
     * @param string $pagetitle  Page title
     * @param string $pageheading Page heading (Note hard coded to site fullname)
     * @param array $context The page context (SYSTEM, COURSE, MODULE etc)
     * @return HTML Contains page information and loads all Javascript and CSS
     */
    static function page($url, $pagetitle, $pageheading, $context, $pagelayout = 'incourse') {
        global $CFG, $PAGE, $SITE;

        $stringman = get_string_manager();
        $strings = $stringman->load_component_strings('block_lesson_notify', current_language());

        $PAGE->set_url($url);
        $PAGE->set_title($pagetitle);
        $PAGE->set_heading($pageheading);
        $PAGE->set_pagelayout($pagelayout);
        $PAGE->set_context($context);
        $PAGE->requires->jquery();
        $PAGE->requires->jquery_plugin('ui');
        $PAGE->requires->jquery_plugin('ui-css');
        $PAGE->requires->jquery_plugin('fontawesome', 'block_lesson_notify');
        $PAGE->requires->jquery_plugin('datatables', 'block_lesson_notify');
        $PAGE->requires->jquery_plugin('lesson_notify', 'block_lesson_notify');
        $PAGE->requires->strings_for_js(array_keys($strings), 'block_lesson_notify');
        self::loadJSDefaults();
    }

    /**
     * This function provides the javascript console.log function to print out php data to the console for debugging.
     * @param string $object
     */
    static function consoleLog($object) {
        $html = '<script>';
        $html .= 'console.log("' . $object . '")';
        $html .= '</script>';

        echo $html;
    }
    
    public static function DBLog($page, $action, $description, $result) {
        global $DB, $USER;

        $data = array(
            'userid' => $USER->id,
            'page' => $page,
            'action' => $action,
            'description' => $description,
            'result' => $result,
            'timecreated' => time()
        );

        $DB->insert_record('lesson_notify_logs', $data);
    }

    /**
     * 
     * @global \stdClass $CFG
     * @param object $context
     * @return type
     */
    static function getEditorOptions($context) {
        global $CFG;
        return array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => 0);
    }

    public static function getFileManagerOptions($context) {
        global $CFG;
        return array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => 2);
    }

    /**
     * @global \session $USER
     * @param string $jsFunction Name of JS Function to load
     */
    static function loadJSDefaults($jsFunction = '') {
        global $USER;

        $initjs = "$(document).ready(function() {
    " . $jsFunction . "();
});";

        return $initjs;
    }

    /**
     * 
     * @global \stdClass $CFG
     */
    public static function navBar($courseId, $url = '') {
        global $CFG;
        $html = '<div class="span12 col-md-12 col-sm-12">';
        $html .= '   <div class="blocklesson_notifyNavBar alert alert-info">';
        $html .= '      <a href="' . $CFG->wwwroot . '/course/view.php?id=' . $courseId . '" class="btn btn-primary">' . get_string('return_to_course', 'block_lesson_notify') . '</a>';
        if ($url != '') {
            $html .= '      <a href="' . $url . '" class="btn btn-primary">' . get_string('new', 'block_lesson_notify') . '</a>';
        }
        $html .= '   </div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * 
     * @global \moodle_database $DB
     * @param string $term
     * @return json
     */
    public static function searchUsers($term) {
        global $DB;
        $sql = "SELECT id,lastname,firstname,email,username FROM {user} WHERE ";

        if (is_numeric($term)) {
            $sql = $sql . "id LIKE '%$term%' ";
        } elseif (strstr($term, ' ')) {
            $terms = explode(' ', $term);
            $sql = $sql . "firstname LIKE '%$terms[0]%' AND lastname LIKE '%$terms[1]%'";
        } else {
            $sql = $sql . "firstname LIKE '%$term%' OR lastname LIKE '%$term%'";
        }
        $sql = $sql . " ORDER BY lastname";
        $results = $DB->get_records_sql($sql);
        foreach ($results as $result) {
            $users[] = array('id' => $result->id, 'text' => $result->lastname . ' ' . $result->firstname . ' - ' . $result->username . ' - ' . $result->email);
        }
        return json_encode($users);
    }

    /**
     * Returns an array of all course modules. This is perfect to use with select input fields
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @return array
     */
    public static function getCourseModules($courseId) {
        global $CFG, $DB;

        //Get course sections
        $sections = $DB->get_records('course_sections', array('course' => $courseId), 'section');
        //Get mod information for this course
        $mods = get_fast_modinfo($courseId);
        //Prepare course modules array
        $courseModules = array();
        foreach ($sections as $s) {
            //Get section information
            $sectionInfo = $mods->get_section_info($s->section);
            $thisSection = convert_to_array($sectionInfo->getIterator());
            //Get course modules for this section and convert into an array
            $sectionModules = explode(',', $thisSection['sequence']);

            foreach ($sectionModules as $key => $value) {
                if (isset($mods->cms[$value])) {
                    $thisModule = $mods->cms[$value];
                    //Do not include labels
                    //modified by Karl Thibaudeau 3/15/2022
                    //Added ability for Questionaires to be listed
                    if ($thisModule->modname == 'lesson' || $thisModule->modname == 'assign' || $thisModule->modname == 'quiz' || $thisModule->modname=='questionnaire') {
                    $courseModules[$thisModule->id] = (object)[ 'name'=> $thisModule->name, 'type'=> $thisModule->modname];
                    }
                }
            }
        }
        return $courseModules;
    }
    
    /**
     * Get all groups within the course
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $courseId
     */
    public static function getGroups($courseId) {
        global $CFG, $DB;
        
        $groups = groups_get_all_groups($courseId);
        
        return $groups;
    }
    
    /**
     * Get all groupings within the course
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @param int $courseId
     */
    public static function getGroupings($courseId) {
        global $CFG, $DB;
        
        $groups = groups_get_all_groupings($courseId);
        
        return $groups;
    }

}
