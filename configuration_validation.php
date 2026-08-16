<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.3                                                    |
// +---------------------------------------------------------------------------+
// | configuration_validation.php                                              |
// |                                                                           |
// | List of validation rules for the analytics plugin configurations          |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2026 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs, Trinity Bays, Geeklog Community                      |
// |          Ben              - hostellerie.org AT gmail DOT com              |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//
if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
    die('This file can not be used on its own!');
}

global $_CONF_VALIDATE;

// Runtime normalization in functions.inc performs the strict GA4-specific
// validation. These rules prevent non-scalar configuration values while
// remaining compatible with Geeklog 2.1.1.
$_CONF_VALIDATE['analytics']['ga_code'] = array('rule' => 'stringOrEmpty');
$_CONF_VALIDATE['analytics']['property_id'] = array('rule' => 'stringOrEmpty');
$_CONF_VALIDATE['analytics']['client_id'] = array('rule' => 'stringOrEmpty');
$_CONF_VALIDATE['analytics']['hostname'] = array('rule' => 'stringOrEmpty');

?>
