<?php
namespace Alm\AlmIconpicker\Backend\Controller\Wizard;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;


class IconpickerController
{
	public $content;


	public function __construct()
	{
		$GLOBALS['LANG']->includeLLFile('EXT:alm_iconpicker/Resources/Private/Language/locallang.xlf');
		$GLOBALS['SOBE'] = $this;
		$this->init();
	}


	protected function init()
	{
		$this->P = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('P', 1);
		$this->formName = $this->P['formName'];
		$this->fieldName = $this->P['itemName'];
		$this->hiddenFieldName = str_replace('_hr', '', $this->fieldName);
		$this->table = $this->P['table'];
		$this->changeFunc = $this->P['fieldChangeFunc']['TBE_EDITOR_fieldChanged'];
		$this->md5ID = $this->P['md5ID'];
		$this->currentValue = $this->P['currentValue'];
		$this->params = $this->P['params'];
		$this->rootPath = '../';

		$this->iconList = array();
		foreach($this->params as $font)
		{
			$tmpList = file_get_contents($this->rootPath . $font['iconList']);
			$tmpList = preg_split('/\r\n|\n|\r/', trim($tmpList));

			$this->iconList[$font['iconFontName']] = $tmpList;
		}
		$this->iconList = json_encode($this->iconList);

		$this->pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Page\PageRenderer');
		$this->pageRenderer->setTemplateFile('EXT:alm_iconpicker/Resources/Private/Templates/PageRenderer.html');
		$this->pageRenderer->setXmlPrologAndDocType('<!DOCTYPE html>');
		$this->pageRenderer->setCharset('utf-8');
		$this->pageRenderer->setTitle('IconPicker');

		$this->pageRenderer->disableCompressCss();
		$this->pageRenderer->disableCompressJavascript();
		$this->pageRenderer->disableConcatenateCss();
		$this->pageRenderer->disableConcatenateJavascript();

		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/css/jquery.fonticonpicker.min.css');
		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css');
		foreach($this->params as $font)
		{
			$this->pageRenderer->addCssFile($this->rootPath . $font['iconFont'], 'stylesheet');
		}
		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/Css/iconpicker.css');

		$this->pageRenderer->addJsFile($GLOBALS['BACK_PATH'] . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/JavaScript/jquery.js');
		$this->pageRenderer->addJsFile($GLOBALS['BACK_PATH'] . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/jquery.fonticonpicker.min.js');

		$this->pageRenderer->addJsInlineCode('iconPicker', '
			tQuery = jQuery.noConflict();

			function windowResize(sizeX, sizeY)
			{
				posX = (screen.width / 2) - (sizeX / 2);
				posY = (screen.height / 2) - (sizeY / 2);

				window.resizeTo(sizeX, sizeY);
				window.moveTo(posX, posY);
			}
			onload = windowResize(430, 720);

			function getData()
			{
				var data = tQuery(\'#icon_element\').val();
				return data;
			}
			function storeData(data)
			{
				if(parent.opener && parent.opener.document && parent.opener.document.' . $this->formName . ' && parent.opener.document.' . $this->formName . '["' . $this->fieldName . '"])
				{
					parent.opener.document.' . $this->formName . '["' . $this->fieldName . '"].value = data;
					parent.opener.document.' . $this->formName . '["' . $this->hiddenFieldName . '"].value = data;
					parent.opener.' . $this->changeFunc . ';
				}
			}

			tQuery(document).ready(function($)
			{
				$(\'#icon_element\').val(\'' . $this->currentValue . '\');

				fnt_icons = ' . $this->iconList . ';
        		$(\'#icon_element\').fontIconPicker({
            		source: fnt_icons,
            		emptyIcon: true,
            		hasSearch: true,
            		iconsPerPage: 40
        		});
    		});
		');
	}


	public function main()
	{
		$content = '<a href="#" title="' .
		            $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.saveDoc', TRUE) . '" onclick="storeData(getData());return true;">' .
		            \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-document-save') . '</a>';

		$content .= '<a href="#" title="' .
		            $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.saveCloseDoc', TRUE) . '" onclick="storeData(getData());window.close();return true;">' .
		            \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-document-save-close') . '</a>';

		$content .= '<a href="#" title="' .
		            $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.closeDoc', TRUE) . '" onclick="window.close();return true;">' .
		            \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-document-close') . '</a>';

		$content = '<div class="icon_picker_head">' . $content . '</div>';

		$content .= '<div class="icon_picker_main">';
		$content .= '<input type="text" id="icon_element" name="icon_element" />';
		$content .= '</div>';

		$content = '<div class="icon_picker_wrapper">' . $content . '</div>';

		$this->pageRenderer->addBodyContent($content);

		echo $this->pageRenderer->render();
	}
}
