'use strict'

$(function () {

	$('#frmAddMember').validate({
		rules: {
			txtDni: {
                maxlength: 8,
				minlength: 8,
				number: true,
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
                required: false,
				minlength: 9,
                maxlength: 9
            },
            txtEmail: {
                required: false,
                email: true,
                maxlength: 255
            },
			txtAddress: {
				required: false,
				maxlength: 255
			},
            txtCharge: {
                required: true,
                maxlength: 255
            },
			txtSpouseDni: {
				maxlength: 8,
				minlength: 8,
				number: true,
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
				required: false,
				minlength: 9,
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
				number: 'Formato inválido',
				minlength: 'El campo debe contener 8 caracteres',
                maxlength: 'El campo debe contener 8 caracteres'
            },
            txtFullName: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
            txtBirthDate: {
                required: 'El campo es requerido',
                date: 'El campo debe ser una fecha válida'
            },
            txtPhone: {
                required: 'El campo es requerido',
				minlength: 'El campo debe contener 9 caracteres',
                maxlength: 'El campo debe contener 9 caracteres'
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
            txtCharge: {
                required: 'El campo es requerido',
                maxlength: 'El campo debe contener máximo 255 caracteres'
            },
			txtSpouseDni: {
				required: 'El campo es requerido',
				number: 'Formato inválido',
				minlength: 'El campo debe contener 8 caracteres',
				maxlength: 'El campo debe contener 8 caracteres'
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
				minlength: 'El campo debe contener 9 caracteres',
				maxlength: 'El campo debe contener 9 caracteres'
			},
			txtSpouseEmail: {
				required: 'El campo es requerido',
				email: 'El campo debe ser un correo válido',
				maxlength: 'El campo debe contener máximo 255 caracteres'
			}
		},
		...validationConfig,
		submitHandler: function (form) {
			openConfirmModal(function() {
				addMemberAjax()
			})
		}
	})

	$('.select2').attr('aria-hidden', 'false')
	$(".form-check label,.form-radio label").append('<i class="input-frame"></i>')

	$('form[method="post"]').on('keypress', function (event) {
		if (event.keyCode === 13 && event.target.tagName != 'TEXTAREA') {
			event.preventDefault()
		}
	})
})

function changeChkHasSpouse(e) {
	if (e.target.checked) {
		$('.hasSpouseDiv').show()
	} else {
		$('.hasSpouseDiv').hide()
	}
}

// if key is enter, and txtDni is not empty, then get partner by dni
function keyUpTxtDni(event) {
	if (event.target.value.length === 8 && event.keyCode === 13) {
		getParntnerByDni(event)
	}
}

function clearPartnerFields() {
	var dniValue = $('#txtDni').val()
	$('#frmAddMember').trigger('reset')
	$('#txtDni').val(dniValue)
	$('#chkHasSpouse').prop('checked', false).trigger('change')
}

function getParntnerByDni(event) {
	if (event.target.value.length !== 8) return

	$.ajax({
		url: `${_baseAppUrl}/partners/get-by-dni/${event.target.value}`,
		type: 'GET',
		data: {
			dni: $('#txtDni').val()
		},
		success: function (response) {
			$('#txtFullName').val(response.full_name)
			$('#txtBirthDate').val(response.birthdate ? response.birthdate : '')
			$('#txtPhone').val(response.phone ? response.phone : '')
			$('#txtEmail').val(response.email ? response.email : '')
			$('#txtAddress').val(response.address ? response.address : '')
			$('#txtCharge').val(response.charge).trigger('change')
			$('#chkHasSpouse').prop('checked', response.spouse ? true : false).trigger('change')
			if (response.spouse) {
				$('#txtSpouseDni').val(response.spouse.dni)
				$('#txtSpouseFullName').val(response.spouse.full_name)
				$('#txtSpouseBirthDate').val(response.spouse.birthdate ? response.spouse.birthdate : '')
				$('#txtSpousePhone').val(response.spouse.phone ? response.spouse.phone : '')
				$('#txtSpouseEmail').val(response.spouse.email ? response.spouse.email : '')
			}
		},
		error: function (error) {
			clearPartnerFields()
		}
	})
}

function addMemberAjax() {
	var projectId = $('#txtProjectId').val()
	var data = {}

	$('#frmAddMember').serializeArray().forEach(item => {
		data[item.name] = item.value
	})

	$.ajax({
		url: `${_baseAppUrl}/projects/add-member/${projectId}`,
		type: 'POST',
		data: data,
		success: function (response) {
			if (response.status === 'success') {
				// clear form
				clearPartnerFields()
				// add new member to the table -> tbody in first position
				$('#tblMembers tbody').prepend(`
					<tr data-member-id="${response.data.id}">
						<td>${response.data.member.dni}</td>
						<td>${response.data.member.full_name}</td>
						<td>${response.data.observation}</td>
						<td>
							<button type="button" class="btn bg-default btn-sm px-1 py-0"  data-toggle="tooltip" data-placement="right" title="Eliminar" onclick="openConfirmModal(function() {deleteMemberAjax('${response.data.id}')})">
								<i class="fas fa-trash text-danger"></i>
							</button>
						</td>
					</tr>
				`)
				response.messages.forEach(message => toastr.success(message))
			} else {
				response.messages.forEach(message => toastr.error(message))
			}
		},
		error: function (error) {
			toastr.error('Ocurrió un error al intentar agregar el miembro al plan de negocio.')
		}
	})
}

function deleteMemberAjax(memberId) {
	$.ajax({
		url: `${_baseAppUrl}/projects/delete-member/${memberId}`,
		type: 'DELETE',
		data: {
			_token: _csrfToken
		},
		success: function (response) {
			if (response.status === 'success') {
				$(`#tblMembers tr[data-member-id="${memberId}"]`).remove()
				response.messages.forEach(message => toastr.success(message))
			} else {
				response.messages.forEach(message => toastr.error(message))
			}
		},
		error: function (error) {
			toastr.error('Ocurrió un error al intentar eliminar el miembro del plan de negocio.')
		}
	})
}