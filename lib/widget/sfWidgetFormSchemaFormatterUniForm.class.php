<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * 
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterList.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterUniForm extends sfWidgetFormSchemaFormatter
{
	protected
		$rowFormat       = "<div class=\"ctrlHolder\">\n\t%label%\n\t%field%\n\t%help%%error%\n%hidden_fields%</div>\n",
		$errorRowFormat  = "<span class=\"formError\">%errors%</span>\n",
		$errorListFormatInARow     = "<p class=\"formError\">%errors%</p>\n",
		$errorRowFormatInARow      = '%error% ',
		$namedErrorRowFormatInARow = "%name%: %error%; ",
		$helpFormat      = '<p class="formHint">%help%</p>',
		$decoratorFormat = "<div>\n  %content%</div>";
	
	protected 
		$multiFieldFormat = "\t\t<li>%label% %field%</li>\n";

	public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
	{
		$row = '';
		if (count($errors) > 0)
		{
			$oldRowFormat = $this->rowFormat;
			$this->rowFormat = str_replace('ctrlHolder', 'ctrlHolder error', $this->rowFormat);
			
			$row = parent::formatRow($label, $field, $errors, $help, $hiddenFields);
			
			$this->rowFormat = $oldRowFormat;
		}
		else
		{
			$row = parent::formatRow($label, $field, $errors, $help, $hiddenFields);
		}
		
		return $row;
	}
	
	public function getMultiFieldFormat()
	{
		return $this->multiFieldFormat;
	}


	public function formatMultiField($label, $field)
	{
		return strtr($this->getMultiFieldFormat(), array(
		  '%label%'         => $label,
		  '%field%'         => $field,
		));
	}
}
