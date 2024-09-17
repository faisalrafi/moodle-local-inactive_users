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
 * Library functions for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function inactive_users_get_inactive_users($page, $perpage) {
    global $DB;
    $offset = ($page - 1) * $perpage;

    $sql = "SELECT * 
                FROM {user} 
              WHERE (lastlogin < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 5 YEAR))  OR lastlogin IS NULL)
              AND id != 1
              LIMIT $perpage OFFSET " . $offset;

    $result = $DB->get_records_sql($sql);
    return $result;
}

function inactive_users_count() {
    global $DB;

    $sql = "SELECT COUNT(*) FROM {user}
            WHERE lastlogin < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 5 YEAR))
              OR lastlogin IS NULL
              AND id != 1";

    return $DB->count_records_sql($sql);
}
