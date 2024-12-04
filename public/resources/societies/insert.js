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
				maxlength: 11,
				minlength: 11,
				number: true
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
			txtDepartment: {
				required: true,
				maxlength: 255
			},
			txtComunity: {
				required: false,
				maxlength: 255
			},
			txtAddress: {
				required: false,
				maxlength: 255
			},
			txtPhone: {
				required: false,
				number: true,
				maxlength: 9,
				minlength: 9
			},
			txtRepresentativeDni: {
				required: true,
				number: true,
				maxlength: 8,
				minlength: 8
			},
			txtRepresentativeFullName: {
				required: true,
				maxlength: 255
			},
			txtRepresentativePhone: {
				required: false,
				number: true,
				maxlength: 9,
				minlength: 9
			},
			txtRepresentativeEmail: {
				email: false,
				maxlength: 255
			},
			txtRepresentativeCharge: {
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
				number: 'El campo debe ser un número',
				minlength: 'El campo debe contener 11 caracteres',
				maxlength: 'El campo debe contener 11 caracteres'
			},
			txtConstitutionDate: {
				required: 'El campo es requerido',
				date: 'El campo debe ser una fecha válida'
			},
			txtPartNumber: {
				required: 'El campo es requerido',
				number: 'El campo debe ser un número',
				maxlength: 'El campo debe contener máximo 8 caracteres'
			},
			txtDistrict: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtProvince: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtDepartment: {
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
				number: 'El campo debe ser un número',
				minlength: 'El campo debe contener 9 caracteres',
				maxlength: 'El campo debe contener 9 caracteres'
			},
			txtRepresentativeDni: {
				required: 'El campo es requerido',
				number: 'El campo debe ser un número',
				minlength: 'El campo debe contener 8 caracteres',
				maxlength: 'El campo debe contener 8 caracteres'
			},
			txtRepresentativeFullName: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtRepresentativePhone: {
				required: 'El campo es requerido',
				number: 'El campo debe ser un número',
				minlength: 'El campo debe contener 9 caracteres',
				maxlength: 'El campo debe contener 9 caracteres'
			},
			txtRepresentativeEmail: {
				email: 'El campo debe ser un correo válido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtRepresentativeCharge: {
				required: 'El campo es requerido',
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

function getParntnerByDni(event) {
	$.ajax({
		url: `${_baseAppUrl}/partners/get-by-dni/${event.target.value}`,
		type: 'GET',
		data: null,
		success: function (response) {
			$('#txtRepresentativeFullName').val(response.full_name)
			$('#txtRepresentativePhone').val(response.phone)
			$('#txtRepresentativeEmail').val(response.email)
			$('#txtRepresentativeCharge').val(response.charge)
			$('#txtRepresentativeCharge').trigger('change')
			// $('.disabled').attr('disabled', 'disabled')
		},
		error: function (response) {
			$('#txtRepresentativeFullName').val('')
			$('#txtRepresentativePhone').val('')
			$('#txtRepresentativeEmail').val('')
			$('#txtRepresentativeCharge').val('')
			$('#txtRepresentativeCharge').trigger('change')
		}
	})
}