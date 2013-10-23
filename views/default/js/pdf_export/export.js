
elgg.provide('pdf_export');

/**
 * Initialize the pdf features
 */
pdf_export.init = function() {
	$('.elgg-menu-item-pdf > a').live('click', pdf_export.exportToPDF);
};

/**
 * Convert image src to base64 encoded data url
 * 
 * The converted images will be in png format. Firefox supports PNG
 * and JPEG. You could check img.src to guess the original format,
 * but using "image/jpg" would re-encode the image.
 * 
 * @param {Object} img
 */
pdf_export.getBase64Image = function(img) {
	// Create an empty canvas element
	var canvas = document.createElement("canvas");
	canvas.width = img.width;
	canvas.height = img.height;

	// Copy the image contents to the canvas
	var ctx = canvas.getContext("2d");
	ctx.drawImage(img, 0, 0);

	// Get the data-URL formatted image
	var dataURL = canvas.toDataURL("image/png");

	return dataURL;
};

/**
 * Send page contents to server for PDF generation
 * 
 * @param {Object} event
 */
pdf_export.exportToPDF = function(event) {
	if (!pdf_export.isCanvasSupported) {
		alert(elgg.echo('pdf_export:canvas_not_supported'));
		event.preventDefault();
		return;
	}

	var currentUrl = $(this).attr('href');
	var query = elgg.parse_url(currentUrl, 'query', true);
	var guid = query.guid;
	var images = $('.elgg-body .elgg-output img');

	// Replace the original image sources with base64 encoded strings
	images.each(function(key, image) {
		var src = pdf_export.getBase64Image(image);
		$(image).attr('src', src);
	});

	var content = $('.elgg-body .elgg-output').html();

	elgg.post('pdf/generate', {
		data: {
			guid: guid,
			content: content
		},
		success: function(data) {
			window.location = elgg.normalize_url('pdf/download?guid=' + guid);
		}
	});

	event.preventDefault();
};

/**
 * Check if browser supports canvas
 */
pdf_export.isCanvasSupported = function() {
	var elem = document.createElement('canvas');
	return !!(elem.getContext && elem.getContext('2d'));
};

elgg.register_hook_handler('init', 'system', pdf_export.init);