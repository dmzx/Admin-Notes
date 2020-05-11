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

class install_adminnotes extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('dmzx_adminnotes_version');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	public function update_data()
	{
		$parser = $this->container->get('text_formatter.parser');

		$adminnotes = $parser->parse('[b]Admin notes, put your notes here[/b]!');

		return [
			['config.add', ['dmzx_adminnotes_version', '1.0.0']],
			['config_text.add', ['dmzx_adminnotes_notes', $adminnotes]],
		];
	}
}
