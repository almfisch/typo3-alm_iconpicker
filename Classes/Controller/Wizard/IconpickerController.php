<?php
namespace Alm\AlmIconpicker\Controller\Wizard;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Module\AbstractModule;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;

class IconpickerController extends AbstractModule
{
	public $content;


	public function __construct()
	{
		parent::__construct();
		$this->init();
	}


	protected function init()
	{
		$this->P = GeneralUtility::_GP('P', 1);
		$this->formName = $this->P['formName'];
		$this->fieldName = $this->P['itemName'];
		$this->table = $this->P['table'];
		$this->changeFunc = $this->P['fieldChangeFunc']['TBE_EDITOR_fieldChanged'];
		$this->changeFuncHash = $this->P['fieldChangeFuncHash'];
		$this->md5ID = $this->P['md5ID'];
		$this->currentValue = $this->P['currentValue'];
		$this->params = $this->P['params'];

		$this->iconList = array();
		foreach($this->params as $font)
		{
			$tmpList = GeneralUtility::getFileAbsFileName($font['iconList']);
			$tmpList = file_get_contents($tmpList);
			$tmpList = preg_split('/\r\n|\n|\r/', trim($tmpList));

			$this->iconList[$font['iconFontName']] = $tmpList;
		}
		$this->iconList = json_encode($this->iconList);

		$this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
		$this->pageRenderer->setXmlPrologAndDocType('<!DOCTYPE html>');
		$this->pageRenderer->setCharset('utf-8');
		$this->pageRenderer->setTitle('IconPicker');

		$this->pageRenderer->disableCompressCss();
		$this->pageRenderer->disableCompressJavascript();
		$this->pageRenderer->disableConcatenateCss();
		$this->pageRenderer->disableConcatenateJavascript();

		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/css/jquery.fonticonpicker.min.css');
		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css');
		foreach($this->params as $font)
		{
			$tmpPath = GeneralUtility::getFileAbsFileName($font['iconFont']);
			$tmpPath = PathUtility::getAbsoluteWebPath($tmpPath);
			$this->pageRenderer->addCssFile($tmpPath, 'stylesheet');
		}
		$this->pageRenderer->addCssFile($GLOBALS['BACK_PATH'] . ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/Css/iconpicker.css');

		$this->pageRenderer->addJsFile($GLOBALS['BACK_PATH'] . ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/JavaScript/jquery.js');
		$this->pageRenderer->addJsFile($GLOBALS['BACK_PATH'] . ExtensionManagementUtility::extRelPath('alm_iconpicker') . 'Resources/Public/Backend/fontIconPicker/jquery.fonticonpicker.min.js');

		$this->pageRenderer->addJsInlineCode('iconPicker', '
			tQuery = jQuery.noConflict();

			function windowResize(sizeX, sizeY)
			{
				posX = (screen.width / 2) - (sizeX / 2);
				posY = (screen.height / 2) - (sizeY / 2);

				window.resizeTo(sizeX, sizeY);
				window.moveTo(posX, posY);
			}
			onload = windowResize(430, 750);

			function getData()
			{
				var data = tQuery(\'#icon_element\').val();
				return data;
			}
			function storeData(data)
			{
				if(parent.opener && parent.opener.document && parent.opener.document.' . $this->formName . ' && parent.opener.document.' . $this->formName . '[\'' . $this->fieldName . '\'])
				{
					parent.opener.document.' . $this->formName . '[\'' . $this->fieldName . '\'].value = data;
					parent.opener.TYPO3.jQuery(\'[data-formengine-input-name="' . $this->fieldName . '"]\').val(data);
					parent.opener.' . $this->changeFunc . '
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


	public function mainAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->main();

        $this->moduleTemplate->setContent($this->content);
        $response->getBody()->write($this->moduleTemplate->renderContent());

        return $response;
    }


	public function main()
	{
		$buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();
		$iconFactory = GeneralUtility::makeInstance(IconFactory::class);

		$saveButton = $buttonBar->makeInputButton()
            ->setName('_savedok')
            ->setValue('1')
            ->setTitle($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:rm.saveDoc'))
            ->setOnClick('storeData(getData());return true;')
            ->setIcon($iconFactory->getIcon('actions-document-save', Icon::SIZE_SMALL));

        $saveAndCloseButton = $buttonBar->makeInputButton()
            ->setName('_savedokandclose')
            ->setValue('1')
            ->setTitle($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:rm.saveCloseDoc'))
            ->setOnClick('storeData(getData());window.close();return true;')
            ->setIcon(
                $iconFactory->getIcon('actions-document-save-close', Icon::SIZE_SMALL)
            );

        $splitButton = $buttonBar->makeSplitButton()
            ->addItem($saveAndCloseButton)
            ->addItem($saveButton);
        $buttonBar->addButton($splitButton);

        $closeButton = $buttonBar->makeLinkButton()
            ->setHref('#')
            ->setTitle($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:rm.closeDoc'))
            ->setOnClick('window.close();return true;')
            ->setIcon($iconFactory->getIcon('actions-document-close', Icon::SIZE_SMALL));
        $buttonBar->addButton($closeButton, ButtonBar::BUTTON_POSITION_LEFT, 30);


        $content = '<div class="icon_picker_main">';
		$content .= '<input type="text" id="icon_element" name="icon_element" />';
		$content .= '</div>';

		$content = '<div class="icon_picker_wrapper">' . $content . '</div>';

		$this->content = $content;
	}
}
