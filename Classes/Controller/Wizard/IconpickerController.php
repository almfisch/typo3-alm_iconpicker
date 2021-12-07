<?php
namespace Alm\AlmIconpicker\Controller\Wizard;

use TYPO3\CMS\Backend\Form\AbstractNode;

class IconpickerController extends AbstractNode
{
	public function render()
	{
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
                'hmac' => \TYPO3\CMS\Core\Utility\GeneralUtility::hmac('editform' . $itemName, 'wizard_js'),
                'fieldChangeFunc' => $parameterArray['fieldChangeFunc'],
                'fieldChangeFuncHash' => \TYPO3\CMS\Core\Utility\GeneralUtility::hmac(serialize($parameterArray['fieldChangeFunc']), 'backend-link-browser'),
			],
		];
		
		$uriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
		$url = (string)$uriBuilder->buildUriFromRoute('popup_iconpicker', $urlParameters);

		$id = \TYPO3\CMS\Core\Utility\StringUtility::getUniqueId('t3js-formengine-fieldcontrol-');

		$result = [
			'iconIdentifier' => 'icon-picker',
			'title' => 'IconPicker',
			'linkAttributes' => [
				'class' => 'btn btn-default',
				'id' => htmlspecialchars($id),
				//'href' => 'javascript:alert("xxx")',
				'href' => $url,
				'data-item-name' => htmlspecialchars($itemName),
			],
			'requireJsModules' => [
				['TYPO3/CMS/AlmIconpicker/IconPickerPopup' => 'function(LinkPopup) {new LinkPopup(' . \TYPO3\CMS\Core\Utility\GeneralUtility::quoteJSvalue('#' . $id) . ');}'],
            ],
		];


		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($urlParameters);
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url);
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url);


		return $result;
	}
}