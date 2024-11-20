'use strict'

$(function() {

	$('#frmEditSociety').validate({
		rules: {
			txtType: {
				required: true
			},
			txtSocialRazon: {
				required: true,
				maxlength: 255
			},
			txtRuc: {
				required: true,
				maxlength: 11
			},
			txtConstitutionDate: {
				required: true,
				date: true
			},
			txtPartNumber: {
				required: true,
				number: true,
				maxlength: 8
			},
			txtDistrict: {
				required: true,
				maxlength: 255
			},
			txtProvince: {
				required: true,
				maxlength: 255
			},
			txtDepartement: {
				required: true,
				maxlength: 255
			},
			txtComunity: {
				required: true,
				maxlength: 255
			},
			txtAddress: {
				required: true,
				maxlength: 255
			},
			txtPhone: {
				required: true,
				maxlength: 9
			},
			txtEmail: {
				required: true,
				maxlength: 255
			}
		},
		messages: {
			txtType: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 30 caracteres'
			},
			txtSocialRazon: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtRuc: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 11 caracteres'
			},
			txtConstitutionDate: {
				required: 'El campo es requerido',
				date: 'El campo debe ser una fecha válida'
			},
			txtPartNumber: {
				required: 'El campo es requerido',
				number: 'El campo debe ser un número'
			},
			txtDistrict: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtProvince: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtDepartement: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtComunity: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtAddress: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtPhone: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 13 caracteres'
			},
			txtEmail: {
				required: 'El campo es requerido',
				pattern: 'El campo debe ser un correo válido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			}
		},
		...validationConfig,
		submitHandler: function (form) {
			openFormConfirm(form.id)
		}
	})

	$('.select2').attr('aria-hidden', 'false')
})

function getProvinces(event) {
	$('#txtDistrict').html('<option></option>')
	$('#txtProvince').html('<option></option>')

	$.ajax({
		url: `${_baseAppUrl}/ubigeo/provinces/${event.target.value}`,
		type: 'GET',
		success: function (response) {
			const html = response.map(province => `<option value="${province.name}">${province.name}</option>`)

			$('#txtProvince').html(`<option></option>${html.join('')}`)
		}
	})
}

function getDistricts(event) {
	$('#txtDistrict').html('<option></option>')

	$.ajax({
		url: `${_baseAppUrl}/ubigeo/districts/${event.target.value}`,
		type: 'GET',
		success: function (response) {
			const html = response.map(district => `<option value="${district.name}">${district.name}</option>`)

			$('#txtDistrict').html(`<option></option>${html.join('')}`)
		}
	})
}