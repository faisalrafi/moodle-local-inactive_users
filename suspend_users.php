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
 * Suspend Users file for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $DB, $OUTPUT, $PAGE, $USER;

require_once(__DIR__ . '/../../config.php');
require_login();

if (!is_siteadmin($USER)) {
    throw new moodle_exception('Unauthorized access');
}

$PAGE->set_url('/local/inactive_users/suspend_users.php');
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('suspenduserstitle', 'local_inactive_users'));
$PAGE->set_heading(get_string('suspenduserstitle', 'local_inactive_users'));
$PAGE->requires->css(new moodle_url('/local/inactive_users/local_inactive_users_style.css'));

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 10, PARAM_INT);

echo $OUTPUT->header();

$searchform = html_writer::empty_tag('input', [
    'type' => 'text',
    'id' => 'inactive_users-myInput',
    'placeholder' => get_string('searchsuspendusers', 'local_inactive_users'),
]);

echo $searchform;

$resumebtn = html_writer::tag('button',
    get_string('resumeselected', 'local_inactive_users'),
    [
        'id' => 'resumeSelected',
        'class' => 'btn btn-success ml-5'
    ]
);

$deletebtn = html_writer::tag('button',
    get_string('deleteselected', 'local_inactive_users'),
    [
        'id' => 'deleteSelected',
        'class' => 'btn btn-danger ml-1',
        'data-toggle' => 'modal',
        'data-target' => '#confirmDeleteSelected'
    ]
);

echo $resumebtn;
echo $deletebtn;

$offset = $page * $perpage;
$suspend_users = $DB->get_records('user', ['suspended' => 1], '', '*', $offset, $perpage);

$total_users = $DB->count_records('user', ['suspended' => 1]);

if (empty($suspend_users)) {
    echo html_writer::tag('p', get_string('nouserssuspended', 'local_inactive_users'), ['class' => 'alert alert-info']);
} else {
    $table = new html_table();
    $table->id = 'myTable';
    $table->head = [
        html_writer::checkbox('', '', false, '', ['id' => 'checkAll', 'onclick' => 'toggleCheckAll(this)']),
        get_string('firstname', 'local_inactive_users'),
        get_string('lastname', 'local_inactive_users'),
        get_string('email', 'local_inactive_users'),
        get_string('resume', 'local_inactive_users'),
    ];

    foreach ($suspend_users as $user) {
        $resume_url = new moodle_url('/local/inactive_users/resume_user.php', ['userid' => $user->id]);
        $resume_button = html_writer::link(
            $resume_url,
            get_string('resume', 'local_inactive_users'),
            ['class' => 'btn btn-success']
        );

        $checkbox = html_writer::checkbox('', '', false, '', ['class' => 'user-checkbox', 'data-userid' => $user->id]);

        $table->data[] = [
            $checkbox,
            $user->firstname,
            $user->lastname,
            $user->email,
            $resume_button
        ];
    }

    echo html_writer::table($table);
}

$back_url = new moodle_url('/local/inactive_users/view.php');
$back_button = html_writer::link(
    $back_url,
    get_string('back', 'local_inactive_users'),
    ['class' => 'btn btn-secondary mt-3']
);

echo $back_button;

$baseurl = new moodle_url('/local/inactive_users/suspend_users.php', ['page' => $page, 'perpage' => $perpage]);

$PAGE->requires->js_call_amd('local_inactive_users/tablefilter', 'init');
echo $OUTPUT->paging_bar($total_users, $page, $perpage, $baseurl);
echo $OUTPUT->footer();
