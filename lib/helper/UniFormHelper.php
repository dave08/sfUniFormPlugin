<?php

function include_uniform($style = 'default', $validate = false)
{
	$response = sfContext::getInstance()->getResponse();
	
	$response->addStylesheet('/sfUniFormPlugin/css/uni-form.css');
	$response->addStylesheet('/sfUniFormPlugin/css/'.$style.'.uni-form.css');
	
	$validation = $validate ? '-validation' : '';
	$script = '/sfUniFormPlugin/js/uni-form'.$validation.'.jquery.js';
	$response->addJavascript($script);
	
	use_helper('JavascriptBase');
/*	echo javascript_tag('
$(document).ready(function() {
	$(".uniForm").uniform();
}

');*/
}

function uniform_render($form, $fields)
{
	//if (!is_array($fields)) $fields[$fields->getLabel()] = $fields;
	
	$formRendered = '';
	foreach($fields as $label => $field)
	{
		if (!is_array($field))
		{
			$content = _uniform_render_field($form, $field);
		}
		else
		{
			$altLayout = false;
			
			if ('=' == $label[0])
			{
				$label = trim($label, '=');
				$altLayout = true;
			}
			
			$content = _uniform_render_multiple_fields($form, $label, $field, $altLayout);
		}
	
		$ctrlHolder = content_tag('div', $content, array('class'=>'ctrlHolder'));
		
		$formRendered .= $ctrlHolder."\n\n";
	}
	
	return $formRendered;
}

function _uniform_render_multiple_fields($form, $label, $fields, $alt = false)
{
	$labelString = content_tag('p', $label, array('class'=>'label'));
	
	$liGroup = '';
	foreach ($fields as $field)
	{
		$li = content_tag('li', _uniform_render_field($form, $field));
		$liGroup .= $li;
	}
	
	$ulOptions = $alt ? array('class'=>'alternate') : array();
	
	return $labelString.content_tag('ul', $liGroup, $ulOptions);
}

function _uniform_render_field($form, $field)
{
	$fieldWidget = $form[$field];
	$hint = content_tag('p', $form[$field]->renderHelp(), array('class'=>'formHint'));
	
	$widgetType = _getWidgetType($fieldWidget);
	
	return  $fieldWidget->renderLabel()."\n"
				.$fieldWidget->render(array('class'=>$widgetType))
				.$hint;
}

function _getWidgetType($widget)
{
	$types = array(
		'sfWidgetFormInputText' => 'textInput',
		'sfWidgetFormInputFile' => 'fileUpload',
		'sfWidgetFormInputFileEditable' => 'fileUpload',
		'sfWidgetFormChoice' => 'selectInput',
		'sfWidgetFormDoctrineChoice' => 'selectInput',
	);
	
	$widgetClass = get_class($widget->getWidget());
	
	if (isset($types[$widgetClass]))
	{
		return $types[$widgetClass];
	}
	else
	{
		return '';
	}
}