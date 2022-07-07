/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('#deleteTemplateConfirmationBox').hide();
    $('#deleteTriggerConfirmationBox').hide();

    $('#id_group').change(function () {
        var groupid = $('#id_group').val();
        $('input[name="groupid"]').val(groupid);
    });
    $('#id_grouping').change(function () {
        var groupingid = $('#id_grouping').val();
        $('input[name="groupingid"]').val(groupingid);
    });
});

function deleteTemplate(templateId) {
    var wwwroot = M.cfg.wwwroot;
    var deleteBtn = M.util.get_string('delete', 'block_lesson_notify');
    var cancelBtn = M.util.get_string('cancel', 'block_lesson_notify');
    jQuery.noConflict();
    $("#deleteTemplateConfirmationBox").dialog({
        resizable: false,
        height: 'auto',
        modal: true,
        buttons: [{
                text: deleteBtn,
                click: function () {
                    $.ajax({
                        type: "POST",
                        url: wwwroot + "/blocks/lesson_notify/ajax.php?action=deleteTemplate&id=" + templateId,
                        dataType: "json",
                        success: function (resultData) {
                            $('#blocklesson_notifyCourseTable').html(resultData.courseTemplates);
                            $('#blocklesson_notifyGlobalTable').html(resultData.globalTemplates);
                        }
                    });
                    $(this).dialog("close");
                }
            },
            {
                text: cancelBtn,
                click: function () {
                    $(this).dialog("close");
                }
            }]
    });
}

function deleteTrigger(triggerId, cmid, courseid) {
    var wwwroot = M.cfg.wwwroot;
    var deleteBtn = M.util.get_string('delete', 'block_lesson_notify');
    var cancelBtn = M.util.get_string('cancel', 'block_lesson_notify');
    jQuery.noConflict();
    $("#deleteTriggerConfirmationBox").dialog({
        resizable: false,
        height: 'auto',
        modal: true,
        buttons: [{
                text: deleteBtn,
                click: function () {
                    $.ajax({
                        type: "POST",
                        url: wwwroot + "/blocks/lesson_notify/ajax.php?action=deleteTrigger&id=" + triggerId,
                        data: '&cmid=' + cmid + '&courseid=' + courseid,
                        dataType: "html",
                        success: function (resultData) {
                            $('#blocklesson_notifyTriggersTable').html(resultData);
                        }
                    });
                    $(this).dialog("close");
                }
            },
            {
                text: cancelBtn,
                click: function () {
                    $(this).dialog("close");
                }
            }]
    });

}

function initLogs() {
    $('#blockLessonNotifyLogsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
}

function initDBLogs() {
    $('#blockLessonNotifyDBLogsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
}