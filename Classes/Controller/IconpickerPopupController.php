<?php
namespace Alm\AlmIconpicker\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * '/empty' routing target returns dummy content.
 * @internal This class is a specific Backend controller implementation and is not considered part of the Public TYPO3 API.
 */
class IconpickerPopupController
{
    /** @var ModuleTemplate */
    protected $moduleTemplate;

    /** @var ViewInterface */
    protected $view;


    /**
     * Return simple dummy content
     *
     * @return ResponseInterface the response with the content
     */
    public function mainAction(): ResponseInterface
    {
        $linkParameter = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('P');

        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->moduleTemplate->setTitle('IconPicker');
        $this->moduleTemplate->getDocHeaderComponent()->disable();


        $this->view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $this->view->setLayoutRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Layouts')]);
        $this->view->setTemplateRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Templates')]);
        $this->view->setPartialRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Partials')]);
        $this->view->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Templates/IconpickerModule/Popup.html'));


        $params = $GLOBALS['TCA']['tt_content']['columns']['tx_almiconfields_icon']['config']['wizards']['iconPicker']['params'];

        $iconList = array();
		$fontPath = array();
		
		foreach($params as $font)
		{
			$tmpList = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($font['iconList']);
			$tmpList = file_get_contents($tmpList);
			$tmpList = preg_split('/\r\n|\n|\r/', trim($tmpList));
			$iconList[$font['iconFontName']] = $tmpList;

			$tmpPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($font['iconFont']);
			$tmpPath = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath($tmpPath);
			$fontPath[] = $tmpPath;
		}
        $iconList = json_encode($iconList);
        
        $cssArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/fontIconPicker/css/jquery.fonticonpicker.min.css'));
		$cssArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/fontIconPicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css'));
		$cssArr = array_merge($cssArr, $fontPath);
		$cssArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/Css/iconpicker.css'));

		$jsArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/JavaScript/jquery.js'));
		$jsArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/fontIconPicker/jquery.fonticonpicker.min.js'));
		$jsArr[] = \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Public/Backend/JavaScript/IconPicker.js'));

		$this->view->assign('cssArr', $cssArr);
		$this->view->assign('jsArr', $jsArr);
        $this->view->assign('iconList', $iconList);
        $this->view->assign('linkParameter', $linkParameter);
        
        $pageContent = $this->view->render();
        $content .= $pageContent;
        $this->moduleTemplate->setContent($content);

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($linkParameter);
        
        return new HtmlResponse($this->moduleTemplate->renderContent());
    }
}