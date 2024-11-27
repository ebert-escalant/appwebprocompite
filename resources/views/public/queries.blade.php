<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procompite | Búsqueda de socio</title>
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
</head>
<body>
    <main>
        <div class="container">
			<header class="align-center">
				<img src="{{ asset('images/full_logo.webp') }}" alt="Logo" class="logo">
			</header>
            <div class="search-container">
                <input type="text" id="dni-input" placeholder="Ingrese DNI">
                <button id="search-button">Buscar</button>
            </div>
			<div class="user-name-container">
				<p id="user-name" class="user-name"></p>
			</div>

            <div id="results" class="results-container">
                <!-- Los resultados se mostrarán aquí -->
            </div>
        </div>
    </main>
    <script>
        document.getElementById('search-button').addEventListener('click', function() {
            const dni = document.getElementById('dni-input').value;
            const resultsContainer = document.getElementById('results');

			if (!dni || dni.trim().length !== 8) {
				resultsContainer.innerHTML = '<p class="no-results">Ingrese un DNI válido.</p>';
				return;
			}

			fetch("{{ url('') }}" + '/consultas/' + dni, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
				}
			}).then(response => {
				if (!response.ok) {
					throw new Error('Error en la petición');
				}
				return response.json();
			}).then(({data}) => {
				console.log(data);
				if (data) {
					document.querySelector('.user-name-container').style.display = 'flex';
					document.querySelector('#user-name').innerHTML = data.full_name;
					let tableHTML = `
						<table>
							<thead>
								<tr>
									<th>Año</th>
									<th>Proyecto</th>
									<th>Asociación</th>
								</tr>
							</thead>
							<tbody>
								${data.societies.map(ietm => `
									<tr>
										<td>${ietm.year}</td>
										<td>${ietm.project?.name}</td>
										<td>${ietm.society?.social_razon}</td>
									</tr>
								`).join('')}
							</tbody>
						</table>
					`;

					resultsContainer.innerHTML = tableHTML;
				} else {
					resultsContainer.innerHTML = '<p class="no-results">No se encontraron resultados.</p>';
				}
			}).catch(error => {
				resultsContainer.innerHTML = '<p class="no-results">Ocurrió un error al realizar la búsqueda.</p>';
			});
        });
    </script>
</body>

</html>
