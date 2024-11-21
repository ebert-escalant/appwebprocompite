'use strict'

$(function() {

    $('#frmEditProject').validate({
		rules: {
            txtPlanNegocio: {
				required: true,
				maxlength: 700
			},
            txtCategory:{
                required: true,
                maxlength: 255
            },
            txtAmountInversment:{
                required: true,
                number: true
            },
            txtConfinanceAmount:{
                required: true,
                number: true
            }

		},
		messages: {
            txtPlanNegocio: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 700 caracteres'
            },
            txtCategory:{
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtAmountInversment:{
                required: 'El campo es requerido',
                number: 'El campo debe ser un número'
            },
            txtConfinanceAmount:{
                required: 'El campo es requerido',
                number: 'El campo debe ser un número'
            }
		},
		...validationConfig,
		submitHandler: function (form) {
			openFormConfirm(form.id)
		}
	})

	$('.select2').attr('aria-hidden', 'false')
})