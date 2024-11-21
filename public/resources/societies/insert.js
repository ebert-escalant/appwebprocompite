'use strict'

$(function () {

	$('#frmInsertSociety').validate({
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

function getParntnerByDni(event) {
	$.ajax({
		url: `${_baseAppUrl}/partners/get-by-dni/${event.target.value}`,
		type: 'GET',
		data: {
			dni: $('#txtDni').val()
		},
		success: function (response) {
			console.log(response.address)
			$('#txtFullName').val(response.full_name).prop('disabled', true)
			$('#txtPhone2').val(response.phone).prop('disabled', true)
			// $('txtAddress').val(response.address)//nani
			$('#txtEmail').val(response.email).prop('disabled', true)
			$('#txtCharge').val(response.charge).prop('disabled', true)
		}
	})
}