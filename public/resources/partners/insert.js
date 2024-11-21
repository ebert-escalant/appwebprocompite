'use strict'

$(function() {

	$('#frmInsertPartner').validate({
		rules: {
			txtDni: {
                maxlength: 8,
                required: true
            },
            txtFullName: {
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
                required: false,
                email: true,
                maxlength: 255
            },
			txtAddress: {
				required: true,
				maxlength: 255
			},
            txtFamilyCharge: {
                required: true,
                maxlength: 255
            },
            txtCharge: {
                required: true,
                maxlength: 255
            },
			txtSpouseDni: {
				maxlength: 8,
				required: {
					depends: function() {
						return $('#chkHasSpouse').is(':checked')
					}
				}
			},
			txtSpouseFullName: {
				required: {
					depends: function() {
						return $('#chkHasSpouse').is(':checked')
					}
				},
				maxlength: 255
			},
			txtSpouseBirthDate: {
				required: {
					depends: function() {
						return $('#chkHasSpouse').is(':checked')
					}
				},
				date: true
			},
			txtSpousePhone: {
				required: {
					depends: function() {
						return $('#chkHasSpouse').is(':checked')
					}
				},
				maxlength: 9
			},
			txtSpouseEmail: {
				email: true,
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
			txtAddress: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
            txtFamilyCharge: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtCharge: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
			txtSpouseDni: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 8 caracteres'
			},
			txtSpouseFullName: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			},
			txtSpouseBirthDate: {
				required: 'El campo es requerido',
				date: 'El campo debe ser una fecha válida'
			},
			txtSpousePhone: {
				required: 'El campo es requerido',
				maxlength: 'El campo debe contener máximo 9 caracteres'
			},
			txtSpouseEmail: {
				required: 'El campo es requerido',
				email: 'El campo debe ser un correo válido',
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

function changeChkHasSpouse(e) {
	console.log(e.target.checked)
	if (e.target.checked) {
		$('.hasSpouseDiv').show()
	} else {
		$('.hasSpouseDiv').hide()
	}
}