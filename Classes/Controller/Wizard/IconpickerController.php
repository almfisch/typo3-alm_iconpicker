<?php
namespace Alm\AlmIconpicker\Controller\Wizard;

use TYPO3\CMS\Backend\Form\AbstractNode;

class IconpickerController extends AbstractNode
{
	public function render(): array
	{
		$hashService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Crypto\HashService\HashService::class);

		$options = $this->data['renderData']['fieldControlOptions'];
		$parameterArray = $this->data['parameterArray'];
		$itemName = $parameterArray['itemFormElName'];

		$urlParameters = [
            'P' => [
                'table' => $this->data['tableName'],
                'uid' => $this->data['databaseRow']['uid'],
                'pid' => $this->data['databaseRow']['pid'],
                'field' => $this->data['fieldName'],
                'formName' => 'editform',
                'itemName' => $itemName,
                'hmac' => $hashService->hmac('editform' . $itemName, 'wizard_js'),
                'fieldChangeFunc' => $parameterArray['fieldChangeFunc'],
                'fieldChangeFuncHash' => $hashService->hmac(serialize($parameterArray['fieldChangeFunc']), 'backend-link-browser'),
			],
		];
		
		$uriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
		$url = (string)$uriBuilder->buildUriFromRoute('popup_iconpicker', $urlParameters);

		$id = \TYPO3\CMS\Core\Utility\StringUtility::getUniqueId('t3js-formengine-fieldcontrol-');

		$result = [
			'iconIdentifier' => 'module_alm_iconpicker_popup',
			'title' => 'IconPicker',
			'linkAttributes' => [
				'class' => 'btn btn-default t3js-element-browser',
				'id' => htmlspecialchars($id),
				//'href' => 'javascript:alert("xxx")',
				'href' => $url,
				'data-item-name' => htmlspecialchars($itemName),
			],
			/*
			'requireJsModules' => [
				['TYPO3/CMS/AlmIconpicker/IconPickerPopup' => 'function(LinkPopup) {new LinkPopup(' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('#' . $id) . ');}'],
            ],
			*/
			/*
			'requireJsModules' => [
				[\TYPO3\CMS\Core\Page\JavaScriptModuleInstruction::forRequireJS('TYPO3/CMS/AlmIconpicker/IconPickerPopup')->instance('#' . $id)]
            ],
			*/
			//'requireJsModules' => [],
		];

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($urlParameters);
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url);
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url);

		return $result;
	}
}