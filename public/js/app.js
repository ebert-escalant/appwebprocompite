const divGlobalContent='divGlobalContent'

toastr.options = {
	"closeButton": true,
	"progressBar": true,
	"positionClass": "toast-top-right",
	"preventDuplicates": true,
	"timeOut": "5000"
}

var validationConfig = {
	errorElement: 'small',
	errorPlacement: function (error, element) {
		error.addClass('mt-2 text-danger')
		element.closest('.form-group').append(error)
		/* error.addClass('mt-2 text-danger');
        error.insertAfter(element); */
	},
	highlight: function (element, errorClass, validClass) {
		$(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
		/* $(element).addClass('is-invalid')
		$(element).removeClass('is-valid') */
	},
	unhighlight: function (element, errorClass, validClass) {
		/* $(element).removeClass('is-invalid')
		$(element).addClass('is-valid') */
		$(element).parent().removeClass('has-danger')
		$(element).removeClass('form-control-danger')
	},
	success: function (label) {
		/* label.addClass("is-valid") */
	},
	invalidHandler: function (form, validator) {
		if (!validator.numberOfInvalids()) return

		toastr.error('Por favor, complete y corrija los campos del formulario.')
	},
	submitHandler: function (form) {
		openFormConfirm(form.id)
	}
}

const loadingModal = {
	show: () => {
		$('#appGlobalLoader').show()
	},
	hide: () => {
		$('#appGlobalLoader').hide()
	}
}

$(function() {
	$('.select2').select2({
		language: {
			noResults: function () {
				return "No se encontraron resultados."
			},
			searching: function () {
				return "Buscando..."
			},
			inputTooShort: function () {
				return 'Por favor ingrese 3 o más caracteres'
			}
		},
		placeholder: 'Seleccionar una opción'
	})
})

const goToUrl = () => {
	loadingModal.show()

	window.location.href = url
}

$(document).ready(function () {
	$('form[method="post"]').on('keypress', function (event) {
		if (event.keyCode === 13 && event.target.tagName != 'TEXTAREA') {
			event.preventDefault()
		}
	})
})

const openAjaxModal = (width, title, data, url, method) => {
	$(`#${divGlobalContent}`).html('')

	loadingModal.show()

	$.ajax({
		url: url,
		type: method,
		data: data,
		cache: false,
		async: true,
	}).done(response => {
		loadingModal.hide()

		const htmlResponse = `
			<div class="modal fade" id="${divGlobalContent}Modal" style="display:none">
				<div class="modal-dialog ${width ? width : ''}" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">${title}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times</span>
							</button>
						</div>
						<div class="modal-body">
							${response}
						</div>
					</div>
				</div>
			</div>
		`
		$(`#${divGlobalContent}`).html(htmlResponse)

		$(`#${divGlobalContent}Modal`).modal('show')

		//on modal show event apply select2
		$(`#${divGlobalContent}Modal`).on('shown.bs.modal', function () {
			$('.select2').select2({
				language: {
					noResults: function () {
						return "No se encontraron resultados."
					},
					searching: function () {
						return "Buscando..."
					},
					inputTooShort: function () {
						return 'Por favor ingrese 3 o más caracteres'
					}
				},
				placeholder: 'Seleccionar una opción'
			})

			$('.select2').on('select2:open', (e) => {
				const el = $(`#${e.target.id}`)

				setTimeout(() => {
					$(`[aria-controls="select2-${el.attr('id')}-results"]`).get(0).focus()
				}, 100)
			})
		})
	}).fail(() => {
		loadingModal.hide()
		toastr.error('Ocurrió un error inesperado. Por favor reporte esto a la plataforma.')
	})
}

function openConfirmModal(callback) {
	Swal.fire({
		title: 'Confirmar acción',
		text: '¿Está seguro de realizar esta acción?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, proceder!',
		cancelButtonText: 'No, cancelar!',
	}).then((result) => {
		if (result.isConfirmed) {
			callback()
		}
	})
}

function openFormConfirm(formId) {
	Swal.fire({
		title: 'Confirmar acción',
		text: '¿Está seguro de realizar esta acción?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, proceder!',
		cancelButtonText: 'No, cancelar!'
	}).then((result) => {
		if (result.isConfirmed) {
			loadingModal.show()

			$(`#${formId}`)[0].submit()
		}
	})
}
