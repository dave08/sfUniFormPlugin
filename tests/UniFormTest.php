<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require '../../../../../plugins/sfUniFormPlugin/lib/UniForm.class.php';
/**
 * Description of sfUniFormHelperTest
 *
 * @author owner
 */
class sfUniFormTest extends DoctrineTestCase
{
	protected $uf = null;

	public function _start()
	{
		parent::_start();
		
		$this->form = new PaintingFormatForm();
		$this->uf = new UniForm($this->form);
	}
	
	public function testRenderTextField()
	{
		$this->form->getWidgetSchema()->setHelp('price','This is a form hint.');
		$html = $this->uf->renderRow('price');
		
		$this->assertEquals($this->getTextField($this->form['price'], true), $html);
	}
	
	public function testRenderSelectField()
	{
		$html = $this->uf->renderRow('type');
		
		$this->assertContains('class="selectInput', $html);
	}
	
	public function testRenderMultipleFields()
	{
		$groupLabel = 'Dimensions';
		
		$this->uf->addFieldGroup($groupLabel, array(
			'width','height'
		));
		
		$this->assertEquals($this->getMultiField(),$this->uf->renderRow($groupLabel));
	}
	
	public function testFieldError()
	{
		$this->form->bind(array());
		
		$this->assertFalse($this->form->isValid());
		
		$html = $this->uf->renderRow('width');
		$this->assertContains('<p class="formError">', $html);
		$this->assertContains('class="textInput large error"', $html);
		$this->assertContains('class="ctrlHolder error"', $html);
	}
	
	protected function getFieldInfo($widget)
	{
		return array(
			'id' => $widget->renderId(),
			'name' => $widget->renderName(),
			'label' => $widget->renderLabelName(),
		);
	}

	protected function getTextField($widget, $hasHint = false)
	{
		extract($this->getFieldInfo($widget));
		
		$formHint = $hasHint ? "\n\t<p class=\"formHint\">This is a form hint.</p>" : '';
		
		return <<<EOF
<div class="ctrlHolder">
	<label for="$id">$label</label>
	<input type="text" name="$name" class="textInput large" id="$id" />$formHint
</div>

EOF;
	}
	
	public function getMultiField()
	{
		return <<<EOF
<div class="ctrlHolder">
	<p class="label">Dimensions</p>
	<ul>
		<li><label for="painting_format_width">Width (in cm.)</label> <input type="text" name="painting_format[width]" class="textInput" id="painting_format_width" /></li>
		<li><label for="painting_format_height">Height (in cm.)</label> <input type="text" name="painting_format[height]" class="textInput" id="painting_format_height" /></li>
	</ul>
</div>

EOF;
	}
}

?>
