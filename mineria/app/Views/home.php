<!-- app/Views/home.php -->
<?= $this->include('templates/header'); ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<div class="container mt-5">
    <div class="jumbotron text-center">
        <h1 class="display-4">Análisis de Secuestros en México</h1>
        <p class="lead">Exploración de los datos y tendencias relacionadas con los secuestros.</p>
        <hr class="my-4">
        <p>Descubre estadísticas y recomendaciones en nuestra galería interactiva.</p>
        <a class="btn btn-primary btn-lg" href="<?= base_url('galeria'); ?>" role="button">Ver Galería</a>
        <!-- Nuevo botón para recomendaciones -->
        <a class="btn btn-secondary btn-lg" href="<?= base_url('recomendaciones'); ?>" role="button">Ver Recomendaciones</a>
    </div>
<!-- Contenedor de consultas dinámicas (igual que en recomendaciones) -->
<h2 class="mt-5 mb-4">Consulta de Incidentes por Estado y Año</h2>
<p>Selecciona un estado, año, o ambos para ver el número de incidentes registrados.</p>

<form id="filtro-form" method="post" action="<?= base_url('recomendaciones/filtrar'); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="">-- Selecciona un estado --</option>
                <?php if (!empty($estados)): ?>
                    <?php foreach ($estados as $row): ?>
                        <option value="<?= esc($row['estado']); ?>"><?= esc($row['estado']); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="año">Año:</label>
            <select name="año" id="año" class="form-control">
                <option value="">-- Selecciona un año --</option>
                <?php if (!empty($años)): ?>
                    <?php foreach ($años as $row): ?>
                        <option value="<?= esc($row['año_secuestro']); ?>"><?= esc($row['año_secuestro']); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <button type="button" id="filtrar-btn" class="btn btn-primary">Filtrar</button>
</form>

<div class="mt-4">
    <h4>Resultados:</h4>
    <div id="resultado">
        <!-- Aquí aparecerá el conteo vía AJAX -->
    </div>
</div>

<script>
document.getElementById('filtrar-btn').addEventListener('click', function() {
    const form = document.getElementById('filtro-form');
    const formData = new FormData(form);

    fetch('<?= base_url('recomendaciones/filtrar'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta de la red.');
        }
        return response.json();
    })
    .then(data => {
        const resultadoDiv = document.getElementById('resultado');
        resultadoDiv.innerHTML = `Número de incidentes: <strong>${data.conteo}</strong>`;
    })
    .catch(error => {
        console.error('Error al filtrar incidentes:', error);
        document.getElementById('resultado').innerHTML = 'Ocurrió un error al obtener los resultados.';
    });
});
</script>

    <div class="text-center">
        <img src="<?= base_url('images/mexicob_2024.png'); ?>" class="img-fluid" alt="Mexico2024">
    </div>

    <script>
    // Obtener el nombre y valor del token CSRF desde las meta etiquetas
    const csrfName = document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
    let csrfValue = document.querySelector('meta[name="csrf-token-value"]').getAttribute('content');

    // Cuando se seleccione un estado, cargamos los municipios vía AJAX
    document.getElementById('estado').addEventListener('change', function() {
        const estado = this.value; // Valor del estado seleccionado
        const municipioSelect = document.getElementById('municipio');

        if (estado) {
            // Crear los parámetros de la solicitud incluyendo el token CSRF
            const params = new URLSearchParams();
            params.append('estado', estado);
            params.append(csrfName, csrfValue); // Incluir el token CSRF

            fetch('<?= base_url('extracciones/getMunicipios'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: params
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la red.');
                }
                return response.json();
            })
            .then(data => {
                // Actualizar el token CSRF para futuras solicitudes
                csrfValue = data.csrfHash;
                document.querySelector('meta[name="csrf-token-value"]').setAttribute('content', csrfValue);

                municipioSelect.innerHTML = '<option value="">-- Selecciona un municipio --</option>';
                if (data.municipios && data.municipios.length > 0) {
                    data.municipios.forEach(function(municipio) {
                        municipioSelect.innerHTML += `<option value="${municipio.municipio}">${municipio.municipio}</option>`;
                    });
                } else {
                    municipioSelect.innerHTML += '<option value="">No se encontraron municipios</option>';
                }
            })
            .catch(error => {
                console.error('Error al obtener municipios:', error);
                municipioSelect.innerHTML = '<option value="">Error al cargar municipios</option>';
            });
        } else {
            municipioSelect.innerHTML = '<option value="">-- Primero selecciona un estado --</option>';
        }
    });

    // Al hacer clic en filtrar, enviamos la petición vía AJAX y mostramos el resultado
    document.getElementById('filtrar-btn').addEventListener('click', function() {
        const form = document.getElementById('filtro-form');
        const formData = new FormData(form);

        // Incluir el token CSRF en el FormData
        formData.append(csrfName, csrfValue);

        fetch('<?= base_url('extracciones/filtrar'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red.');
            }
            return response.json();
        })
        .then(data => {
            // Actualizar el token CSRF para futuras solicitudes
            csrfValue = data.csrfHash;
            document.querySelector('meta[name="csrf-token-value"]').setAttribute('content', csrfValue);

            const resultadoDiv = document.getElementById('resultado');
            resultadoDiv.innerHTML = `Número de incidentes: <strong>${data.conteo}</strong>`;
        })
        .catch(error => {
            console.error('Error al filtrar incidentes:', error);
            document.getElementById('resultado').innerHTML = 'Ocurrió un error al obtener los resultados.';
        });
    });
    </script>


</div>

<?= $this->include('templates/footer'); ?>
