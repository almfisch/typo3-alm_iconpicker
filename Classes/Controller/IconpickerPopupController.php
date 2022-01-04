<?php
namespace Alm\AlmIconpicker\Controller;

use Psr\Http\Message\ServerRequestInterface;
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

    /**
     * Return simple dummy content
     *
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        $linkParameter = $request->getQueryParams()['P'];

        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->moduleTemplate->setTitle('IconPicker');
        $this->moduleTemplate->getDocHeaderComponent()->disable();

        $view = $this->moduleTemplate->getView();
        $view->setLayoutRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Layouts')]);
        $view->setTemplateRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Templates')]);
        $view->setPartialRootPaths([\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Partials')]);
        $view->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:alm_iconpicker/Resources/Private/Templates/IconpickerModule/Popup.html'));

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

		$view->assign('cssArr', $cssArr);
		$view->assign('jsArr', $jsArr);
        $view->assign('iconList', $iconList);
        $view->assign('linkParameter', $linkParameter);

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($linkParameter);
        
        return new HtmlResponse($view->render());
    }
}