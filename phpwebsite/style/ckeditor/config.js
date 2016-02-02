/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.height = 300;
	config.toolbarCanCollapse = true;
	config.image_previewText=' ';
	config.filebrowserImageUploadUrl= "http://localhost/truecolorpro/newpbi/index.php/www/portal/ckeditMultiFiles"; //待会要上传的action或servlet
};
