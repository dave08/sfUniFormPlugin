<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UniForm
 *
 * @author owner
 */
class UniForm
{
	protected
		$style = '',
		$validate = false,
		$errors = array(),
		$form = null,
		$fields = array(),
		$formatter = null,
		$groupFields = array(),
		$alternateFieldLayout = false;
	
	protected $widgetTypes = array(
		'sfWidgetFormInputText' => 'textInput large',
		'sfWidgetFormInputFile' => 'fileUpload',
		'sfWidgetFormInputFileEditable' => 'fileUpload',
		'sfWidgetFormChoice' => 'selectInput',
		'sfWidgetFormDoctrineChoice' => 'selectInput',
	);
	
	protected
		$multiFieldRowFormat = "<div class=\"ctrlHolder\">\n\t<p class=\"label\">%label%</p>\n\t<ul>\n%fields%\t</ul>\n</div>\n",
		$altMultiFieldRowFormat = "<div class=\"ctrlHolder\">\n\t<p class=\"label\">%label%</p>\n\t<ul class=\"alternate\">\n%fields%\t</ul>\n</div>\n";
	
	public function __construct(sfForm $form, $style = 'default', $validate = false)
	{
		$this->form = $form;
		$this->style = $style;
		$this->validate = $validate;
		
		$this->form->getWidgetSchema()->setFormFormatterName('UniForm');
	}
	
	public function addFieldGroup($label, $fields = array())
	{
		$this->groupFields[$label] = $fields;
	}
	
	public function hasFieldGroup($label)
	{
		return isset($this->groupFields[$label]);
	}
	
	public function getFieldGroup($label)
	{
		return $this->groupFields[$label];
	}
	
	public function getMultiRowFormat()
	{
		return $this->alternateFieldLayout? $this->altMultiFieldRowFormat : $this->multiFieldRowFormat;
	}
	
	public function setAlternateFieldLayout($alternateFieldLayout)
	{
		$this->alternateFieldLayout = $alternateFieldLayout;
	}
	
	public function renderRow($name)
	{
		if ($this->hasFieldGroup($name))
		{
			return $this->formatMultiFieldRow($name);
		}
		else
		{
			return $this->formatSingleFieldRow($name);
		}
	}
	
	protected function formatSingleFieldRow($name)
	{
		$field = $this->form[$name];
		
		return $field->renderRow($this->getFieldAttributes($field));		
	}

	protected function formatMultiFieldRow($fieldGroup)
	{
		$fields = $this->getFieldGroup($fieldGroup);
		$formatter = $this->form->getWidgetSchema()->getFormFormatter();
		
		$renderedFields = '';
		foreach ($fields as $fieldName)
		{
			$field = $this->form[$fieldName];
			
			$renderedFields .= $formatter->formatMultiField($field->renderLabel(), $field->render($this->getFieldAttributes($field)));
		}	
		
		// The strtr w/ ' large' is a temporary way of getting rid of it
		// until varying textInput sizes is implemented
		return strtr($this->getMultiRowFormat(), array(
			'%label%' => $fieldGroup,
			'%fields%' => strtr($renderedFields, array(' large' => '')),
		));
	}

	public function getWidgetType($name)
	{
		$widgetClass = get_class($this->form[$name]->getWidget());

		return isset($this->widgetTypes[$widgetClass]) ? $this->widgetTypes[$widgetClass] : '';
	}
	
	protected function getFieldAttributes($field)
	{
		$error = $field->hasError() ? ' error' : '';
		$widgetType = $this->getWidgetType($field->getName());

		return array('class'=>$widgetType.$error);
	}
}
