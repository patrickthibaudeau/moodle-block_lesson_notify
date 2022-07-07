<?php

function block_lesson_notify_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $DB;

    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    require_login();
    
    if ($filearea !== 'message') {
       return false; 
    }

    $itemid = (int) array_shift($args);


    $fs = get_file_storage();
    $filename = array_pop($args);

    if (empty($args)) {
        $filepath = '/';
    } else {
        $filepath = '/' . implode('/', $args) . '/';
    }

    $file = $fs->get_file($context->id, 'block_lesson_notify', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}
