<?php
/**
 *
 * Admin Notes. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\adminnotes\migrations;

use phpbb\db\migration\migration;

class adminnotes_v101 extends migration
{
	static public function depends_on()
	{
		return [
			'\dmzx\adminnotes\migrations\install_adminnotes',
		];
	}

	public function update_data()
	{
		return [
			// Update config
			['config.update', ['dmzx_adminnotes_version', '1.0.1']],
		];
	}
}
