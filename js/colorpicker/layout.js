$(function () {
	// var initLayout = function() {
		$('#colorpickerField1').ColorPicker({
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
				console.log('----')
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
				console.log('----')
			},
			onChange: function (hsb, hex, rgb) {
				$('#target').css('backgroundColor', '#' + hex);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	}());