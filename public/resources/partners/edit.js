'use strict'

$(function() {

	$('#frmEditSociety').validate({
		rules: {
			txtDni: {
                maxlength: 8,
                required: true
            },
            txtFirstName: {
                required: true,
                maxlength: 255
            },
            txtBirthDate: {
                required: true,
                date: true
            },
            txtPhone: {
                required: true,
                maxlength: 9
            },
            txtEmail: {
                required: true,
                pattern: 'El campo debe ser un correo válido',
                // email: true,
                maxlength: 255
            },
            txtFamilyCharge: {
                required: true,
                maxlength: 255
            },
            txtCharge: {
                required: true,
                maxlength: 255
            }
		},
		messages: {
			txtDni: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 8 caracteres'
            },
            txtFirstName: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtBirthDate: {
                required: 'El campo es requerido',
                date: 'El campo debe ser una fecha válida'
            },
            txtPhone: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 9 caracteres'
            },
            txtEmail: {
                required: 'El campo es requerido',
                email: 'El campo debe ser un correo válido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtFamilyCharge: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtCharge: {
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

