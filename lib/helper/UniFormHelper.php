<?php

function include_uniform($style = 'default', $validate = false)
{
	$response = sfContext::getInstance()->getResponse();
	
	$response->addStylesheet('/sfUniFormPlugin/css/uni-form.css');
	$response->addStylesheet('/sfUniFormPlugin/css/'.$style.'.uni-form.css');
	
	$validation = $validate ? '-validation' : '';
	$script = '/sfUniFormPlugin/js/uni-form'.$validation.'.jquery.js';
	$response->addJavascript($script);
	
/*	use_helper('JavascriptBase');
	echo javascript_tag('
$(document).ready(function() {
	$(".uniForm").uniform();
}

');*/
}

function uniform_render($form, $fields, $renderTopErrors = true)
{
	$uniForm = new UniForm($form);
	
	$formRendered = '';
	foreach($fields as $label => $field)
	{
		if (!is_array($field))
		{
			$content = $uniForm->renderRow($field);
		}
		else
		{
			$altLayout = false;
			
			if ('=' == $label[0])
			{
				$label = trim($label, '=');
				$altLayout = true;
			}
			
			$content = $uniForm->renderRow($name);
		}
	
		
		$formRendered .= $content."\n\n";
	}
	
	$topErrors = $renderTopErrors? _uniform_render_top_errors($form): '';
	
	return $topErrors.$formRendered;
}

function _uniform_render_top_errors($form)
{
	if ($form->hasErrors())
	{
		$content = '';
		foreach ($form->getErrorSchema()->getErrors() as $error)
		{
			$content .= content_tag('li', $error)."\n";
		}
		$ol = content_tag('ol', $content);
		$h3 = content_tag('h3', 'Please correct the following errors:');
		return content_tag('div', $h3."\n".$ol, array('id'=>'errorMsg'));
	}
	
	return '';
}

function _uniform_render_multiple_fields($form, $label, $fields, $alt = false)
{
	$labelString = content_tag('p', $label, array('class'=>'label'));
	
	$liGroup = '';
	$error = '';
	foreach ($fields as $field)
	{
		$fieldWidget = $form[$field];
		$error = $fieldWidget->hasError()?' error':'';
		$li = content_tag('li', __uniform_render_field($form, $fieldWidget, $error));
		$liGroup .= $li;
	}
	
	$ulOptions = $alt ? array('class'=>'alternate') : array();
	
	$ctrlHolder = content_tag('div', $labelString.content_tag('ul', $liGroup, $ulOptions), array('class'=>'ctrlHolder'.$error));
	
	return $ctrlHolder;
}

function _uniform_render_single_field($form, $field)
{
	$fieldWidget = $form[$field];
	$error = $fieldWidget->hasError()?' error':'';
	$ctrlHolder = content_tag('div', __uniform_render_field($form, $fieldWidget, $error), array('class'=>'ctrlHolder'.$error));

	return  $ctrlHolder;	
}

function __uniform_render_field($form, $fieldWidget, $error)
{

	$hint = content_tag('p', $fieldWidget->renderHelp(), array('class'=>'formHint'));
	$widgetType = _getWidgetType($fieldWidget);

	return $fieldWidget->renderLabel()."\n"
				.$fieldWidget->render(array('class'=>$widgetType.$error))
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