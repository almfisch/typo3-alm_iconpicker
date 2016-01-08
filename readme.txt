IconPicker - TYPO3 Module

Thanks to:
http://codeb.it/fonticonpicker/
https://github.com/micc83/fontIconPicker

################################################################################################################

TCA Example:

			'wizards' => array(
					'_VERTICAL' => 0,
                    '_DISTANCE' => 10,
					'iconPicker' => array(
						'type' => 'popup',
						'title' => 'IconPicker',
                        'module' => array(
                        	'name' => 'wizard_iconpicker',
                        ),
                        'icon' => 'EXT:alm_iconpicker/Resources/Public/Icons/wizard.gif',
                        'JSopenParams' => 'height=750,width=430,status=0,menubar=0,scrollbars=0',
                        'params' => array(
                        	'font_1' => array(
                        		'iconFontName' => 'Font Awesome',
                        		'iconFont' => 'fileadmin/templates/font_awesome/css/font-awesome.min.css',
                        		'iconList' => 'fileadmin/templates/font_awesome/iconlist.txt'
                        	),
                        	'font_2' => array(
                        		'iconFontName' => 'Font Ownsome',
                        		'iconFont' => 'fileadmin/templates/font_ownsome/font_ownsome.css',
                        		'iconList' => 'fileadmin/templates/font_ownsome/iconlist.txt'
                        	)
                        )
					)
                )
                
Path to icon font can be 'EXT, for example:
'iconFont' => 'EXT:provider_extension/Resources/Public/Fonts/font_awesome/css/font-awesome.min.css',
'iconList' => 'EXT:provider_extension/Resources/Public/Fonts/font_awesome/iconlist.txt'

################################################################################################################

Flexform Example:

	<wizards>
		<_VERTICAL>0</_VERTICAL>
		<_DISTANCE>10</_DISTANCE>
		<iconPicker>
			<type>popup</type>
			<title>IconPicker</title>
			<module>
				<name>wizard_iconpicker</name>
			</module>
			<icon>EXT:alm_iconpicker/Resources/Public/Icons/wizard.gif</icon>
			<JSopenParams>height=750,width=430,status=0,menubar=0,scrollbars=0</JSopenParams>
			<params>
				<font_1>
					<iconFontName>Font Awesome</iconFontName>
					<iconFont>fileadmin/templates/font_awesome/css/font-awesome.min.css</iconFont>
					<iconList>fileadmin/templates/font_awesome/iconlist.txt</iconList>
				</font_1>
				<font_2>
					<iconFontName>Font Ownsome</iconFontName>
					<iconFont>fileadmin/templates/font_ownsome/font_ownsome.css</iconFont>
					<iconList>fileadmin/templates/font_ownsome/iconlist.txt</iconList>
				</font_2>
			</params>
		</iconPicker>
	</wizards>
	
Path to icon font can be 'EXT, for example:
'iconFont' => 'EXT:provider_extension/Resources/Public/Fonts/font_awesome/css/font-awesome.min.css',
'iconList' => 'EXT:provider_extension/Resources/Public/Fonts/font_awesome/iconlist.txt'

################################################################################################################

The iconlist.txt is a simple list, each line a icon.

FontAwesome example list content:

fa fa-bars
fa fa-bed
fa fa-beer
fa fa-car
fa fa-plus

IcoMoon example list content:

icon-ownsome_1
icon-ownsome_2
icon-ownsome_3
icon-ownsome_4
icon-ownsome_5