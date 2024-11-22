'use strict'

$(function () {

	$('#frmAddMember').validate({
		rules: {
			txtYear: {
				required: true,
				number: true
			},
			txtDni: {
				required: true,
				maxlength: 8
			}
		},
		messages: {
			txtYear: {
				required: 'El campo es requerido',
				number: 'El campo debe contener solo números'
			},
			txtDni: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 8 caracteres'
			}
		},
		...validationConfig,
		submitHandler: function (form) {
			openFormConfirm(form.id)
		}
	})

	$('.select2').attr('aria-hidden', 'false')
})

// if key is enter, and txtDni is not empty, then get partner by dni
function keyUpTxtDni(event) {
	if (event.keyCode !== 13) {
		return
	}

	if (event.target.value.length === 8) {
		getParntnerByDni(event)
	}
}

function getParntnerByDni(event) {
	$.ajax({
		url: `${_baseAppUrl}/partners/get-by-dni/${event.target.value}`,
		type: 'GET',
		data: {
			dni: $('#txtDni').val()
		},
		success: function (response) {
			$('#txtFullName').val(response.full_name);
		}
	})
}