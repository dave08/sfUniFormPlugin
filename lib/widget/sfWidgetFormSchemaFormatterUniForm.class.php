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
    $rowFormat       = "<div class=\"ctrlHolder\">\n  %label% %error%\n  %field%\n<span class=\"formHint\">%help%<span>\n%hidden_fields%</div>\n",
    $errorRowFormat  = "<span class=\"formError\">%errors%</span>\n",
    $errorListFormatInARow     = "<span class=\"formError\">%errors%</span>\n",
    $errorRowFormatInARow      = "%error% ",
    $namedErrorRowFormatInARow = "%name%: %error%; ",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<div>\n  %content%</div>";
 
}
