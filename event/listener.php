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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\textformatter\s9e\parser */
	protected $parser;

	/** @var \phpbb\textformatter\s9e\renderer */
	protected $renderer;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\textformatter\s9e\utils */
	protected $utils;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\config\db_text					$config_text		Config text object
	 * @param \phpbb\textformatter\s9e\parser		$parser				Textformatter parser object
	 * @param \phpbb\textformatter\s9e\renderer		$renderer			Textformatter renderer object
	 * @param \phpbb\request\request				$request			Request object
	 * @param \phpbb\template\template				$template			Template object
	 * @param \phpbb\textformatter\s9e\utils		$utils				Textformatter utilities object
	 */
	public function __construct(
		\phpbb\config\db_text $config_text,
		\phpbb\textformatter\s9e\parser $parser,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\textformatter\s9e\utils $utils
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
