<?php
/**
 *
 * Admin Notes. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, dmzx, https://www.dmzx-web.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dmzx\adminnotes\event;

use phpbb\config\db_text;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\textformatter\s9e\parser;
use phpbb\textformatter\s9e\renderer;
use phpbb\textformatter\s9e\utils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var db_text */
	protected $config_text;

	/** @var parser */
	protected $parser;

	/** @var renderer */
	protected $renderer;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var utils */
	protected $utils;

	/**
	 * Constructor.
	 *
	 * @param db_text		$config_text		Config text object
	 * @param parser		$parser				Textformatter parser object
	 * @param renderer		$renderer			Textformatter renderer object
	 * @param request		$request			Request object
	 * @param template		$template			Template object
	 * @param utils			$utils				Textformatter utilities object
	 */
	public function __construct(
		db_text $config_text,
		parser $parser,
		renderer $renderer,
		request $request,
		template $template,
		utils $utils
	)
	{
		$this->config_text		= $config_text;
		$this->parser			= $parser;
		$this->renderer			= $renderer;
		$this->request			= $request;
		$this->template			= $template;
		$this->utils			= $utils;
	}

	public static function getSubscribedEvents(): array
	{
		return [
			'core.acp_main_notice'	=> 'acp_main_notice',
		];
	}

	public function acp_main_notice(): void
	{
		$adminnotes = $this->config_text->get('dmzx_adminnotes_notes');

		if ($this->request->is_set_post('submit_adminnotes'))
		{
			$adminnotes = $this->request->variable('adminnotes_notes', '', true);
			$adminnotes = $this->parser->parse($adminnotes);

			$this->config_text->set('dmzx_adminnotes_notes', $adminnotes);
		}

		$this->template->assign_vars([
			'ADMINNOTES_NOTES'		=> $this->renderer->render(htmlspecialchars_decode($adminnotes, ENT_COMPAT)),
			'ADMINNOTES_EDIT'		=> $this->utils->unparse($adminnotes),
			'S_ADMINNOTES_EDIT'		=> $this->request->is_set('edit_adminnotes_notes'),
		]);
	}
}
