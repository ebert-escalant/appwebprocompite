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
			openConfirmModal(function() {
				addMemberAjax()
			})
		}
	})

	$('.select2').attr('aria-hidden', 'false')
})

// if key is enter, and txtDni is not empty, then get partner by dni
function keyUpTxtDni(event) {
	if (event.target.value.length === 8) {
		getParntnerByDni(event)
	}
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
			$('#txtFullName').val(response.full_name);
		},
		error: function (error) {
			toastr.error('El DNI ingresado no se encuentra registrado en el sistema.')
		}
	})
}

function addMemberAjax() {
	//console.log($('#frmAddMember').serialize())
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
			console.log(response)
			if (response.status === 'success') {
				$('#frmAddMember').trigger('reset')
				// add new member to the table -> tbody in first position
				$('#tblMembers tbody').prepend(`
					<tr data-member-id="${response.data.id}">
						<td>${response.data.member.dni}</td>
						<td>${response.data.member.full_name}</td>
						<td>${response.data.observation}</td>
						<td>
							<button type="button" class="btn bg-default btn-sm px-1 py-0"  data-toggle="tooltip" data-placement="right" title="Eliminar" onclick="openConfirmModal(function() {deleteMemberAjax(${response.data.id})})">
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