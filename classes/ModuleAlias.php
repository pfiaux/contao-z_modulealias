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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class ModuleAlias
 *
 * @copyright  Andreas Schempp 2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Patrick Fiaux <nodz@nothing.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: ModuleAlias.php 178 2010-12-24 11:57:24Z aschempp $
 * @package Contao
 */
class ModuleAlias extends Module
{

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### MODULE ALIAS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = $this->Environment->script.'?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}


		$arrModules = deserialize($this->aliasModules);

        // If there's only 1 alias selected it will not be in an array, make it an array
        if (is_numeric($arrModules)) {
            $arrModules = array($arrModules);
        }

        // If there's at least 1 alias
		if (is_array($arrModules) && count($arrModules))
		{
			global $objPage;

            // Get the aliases
			$objModules = $this->Database->execute("SELECT id, aliasPages FROM tl_module WHERE id IN (" . implode(',', $arrModules) . ")");

            // Go through each alias until we find a match
			while( $objModules->next() )
			{
				$arrPages = deserialize($objModules->aliasPages);

                // If there's only 1 page selected it will not be in an array, make it an array
                if (is_numeric($arrPages)) {
                    $arrPages = array($arrPages);
                }

				if (is_array($arrPages) && count($arrPages))
				{
                    // Gather all the sub pages (isn't there a get page tree function?)
					foreach( $arrPages as $intPage )
					{
						$arrPages = array_merge($arrPages, $this->Database->getChildRecords($intPage, 'tl_page', false));
					}

					if (in_array($objPage->id, $arrPages))
					{
						return $this->getFrontendModule($objModules->id, $this->inColumn);
					}
				}
			}
		}

        // No matches found, return empty
		return '';
	}


	/**
	 * Not required but abstract in parent class
	 */
	protected function compile() {}
}

