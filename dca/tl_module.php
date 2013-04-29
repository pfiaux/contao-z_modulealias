<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package ModuleAlias
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * @copyright  Andreas Schempp 2009-2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Patrick Fiaux <nodz@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: tl_news.php 348 2011-08-17 12:08:45Z aschempp $
 */



/**
 * Palettes
 */
foreach( $GLOBALS['TL_DCA']['tl_module']['palettes'] as $name => $palette )
{
	if ($name == '__selector__')
		continue;

	$GLOBALS['TL_DCA']['tl_module']['palettes'][$name] = str_replace('{expert_legend:hide}', '{expert_legend:hide},aliasPages', $palette);
}

$GLOBALS['TL_DCA']['tl_module']['palettes']['modulealias'] = '{title_legend},name,type;{config_legend},aliasModules';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['aliasPages'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['aliasPages'],
	'exclude'			=> true,
	'inputType'			=> 'pageTree',
	'eval'				=> array('fieldType'=>'checkbox'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['aliasModules'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['aliasModules'],
	'exclude'			=> true,
	'inputType'			=> 'select',
	'options_callback'	=> array('tl_module_modulealias', 'getModules'),
	'eval'				=> array('mandatory'=>true, 'multiple'=>true, 'size'=>20),
);



class tl_module_modulealias extends Backend
{

	public function getModules($dc)
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT *, (SELECT name FROM tl_theme WHERE tl_module.pid=tl_theme.id) AS theme FROM tl_module WHERE id!={$dc->id} ORDER BY name");

		while( $objModules->next() )
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name;
		}

		return $arrModules;
	}
}

