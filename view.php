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
 * View file for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $USER, $PAGE, $OUTPUT, $CFG;

require('../../config.php');
require_once($CFG->dirroot.'/local/inactive_users/lib.php');

if(!is_siteadmin($USER)) {
    return redirect(new moodle_url('/'), 'Unauthorized', null, \core\output\notification::NOTIFY_ERROR);
}

$PAGE->set_url('/local/inactive_users/view.php');
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('pagetitle', 'local_inactive_users'));
$PAGE->set_heading(get_string('pageheading', 'local_inactive_users'));
$PAGE->requires->css(new moodle_url('/local/inactive_users/local_inactive_users_style.css'));

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 50, PARAM_INT);

echo $OUTPUT->header();
$suspend = get_string('suspend', 'local_inactive_users');
$delete = get_string('delete', 'local_inactive_users');

$inactive_users = inactive_users_get_inactive_users($page, $perpage);

$total_users = inactive_users_count();

$baseurl = new moodle_url('/local/inactive_users/view.php', ['page' => $page, 'perpage' => $perpage]);

$templatecontext = [
    'inactive_users' => array_values($inactive_users),
    'feature_url' => new moodle_url('/local/inactive_users/delete.php'),
    'suspend_url' => new moodle_url('/local/inactive_users/suspend_users.php'),
    'suspend' => $suspend,
    'delete' => $delete,
];

echo $OUTPUT->render_from_template('local_inactive_users/view', $templatecontext);
$PAGE->requires->js_call_amd('local_inactive_users/tablefilter', 'init');

echo $OUTPUT->paging_bar($total_users, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
