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
 * Delete file for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $DB;

require_once(__DIR__ . '/../../config.php');
require_login();

$userid = optional_param('userid', 0, PARAM_INT);
$action = optional_param('action', 0, PARAM_INT);

if ($action == 1) {
    // Suspend the user.
    $updateObject = new stdClass();
    $updateObject->id = $userid;
    $updateObject->suspended = 1;
    $DB->update_record('user', $updateObject);
    redirect(new moodle_url('/local/inactive_users/view.php'), 'User Suspended', null, \core\output\notification::NOTIFY_SUCCESS);
} else {
    // Delete the user.
    $DB->delete_records('user', ['id' => $userid]);
    redirect(new moodle_url('/local/inactive_users/view.php'), 'User Deleted', null, \core\output\notification::NOTIFY_SUCCESS);
}
