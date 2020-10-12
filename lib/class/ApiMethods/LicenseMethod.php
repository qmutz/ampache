<?php

/*
 * vim:set softtabstop=4 shiftwidth=4 expandtab:
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPL-3.0-or-later)
 * Copyright 2001 - 2020 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=0);

namespace Lib\ApiMethods;

use AmpConfig;
use Api;
use JSON_Data;
use Session;
use XML_Data;

final class LicenseMethod
{
    private const ACTION = 'license';

    /**
     * license
     * MINIMUM_API_VERSION=420000
     *
     * This returns a single license based on UID
     *
     * @param array $input
     * filter = (string) UID of license
     * @return boolean
     */
    public static function license($input)
    {
        if (!AmpConfig::get('licensing')) {
            Api::error(T_('Enable: licensing'), '4703', self::ACTION, 'system', $input['api_format']);

            return false;
        }
        if (!Api::check_parameter($input, array('filter'), self::ACTION)) {
            return false;
        }
        $uid = array((int) scrub_in($input['filter']));
        ob_end_clean();
        switch ($input['api_format']) {
            case 'json':
                echo JSON_Data::licenses($uid);
                break;
            default:
                echo XML_Data::licenses($uid);
        }
        Session::extend($input['auth']);

        return true;
    }
}
