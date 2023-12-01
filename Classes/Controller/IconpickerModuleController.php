<?php
namespace Alm\AlmIconpicker\Controller;

class IconpickerModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	public function __construct(
        protected readonly \TYPO3\CMS\Backend\Template\ModuleTemplateFactory $moduleTemplateFactory,
    ) {}
	
	/**
     * Index Action
     *
     * @return void
     */
    protected function indexAction()
    {
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

		$moduleTemplate = $this->moduleTemplateFactory->create($this->request);
		$moduleTemplate->assign('cssArr', $cssArr);
		$moduleTemplate->assign('jsArr', $jsArr);
		$moduleTemplate->assign('iconList', $iconList);
		
		return $moduleTemplate->renderResponse('Index');
    }
}
