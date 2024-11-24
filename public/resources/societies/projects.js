'use strict'

$(function () {

	$('#frmAddProject').validate({
		rules: {
			txtYear: {
				required: true,
				number: true
			},
			txtProjectName: {
				required: true,
			}
		},
		messages: {
			txtYear: {
				required: 'El campo es requerido',
				number: 'El campo debe contener solo números'
			},
			txtProjectName: {
				required: 'El campo es requerido',
			}
		},
		...validationConfig,
		submitHandler: function (form) {
			openFormConfirm(form.id)
		}
	})

	$('.select2').attr('aria-hidden', 'false')
})