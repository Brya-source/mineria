<?php

namespace App\Controllers;

use App\Models\ExtraccionesModel;
use CodeIgniter\Controller;

class Extracciones extends Controller
{
    public function index()
    {
        $model = new ExtraccionesModel();
        $data['estados'] = $model->getEstados();
        $data['años'] = $model->getAños();

        // No cargar la vista 'recomendaciones' aquí si está siendo manejada por 'Recomendaciones' controlador
        // return view('recomendaciones', $data);
    }

    public function getMunicipios()
    {
        if ($this->request->isAJAX()) {
            $estado = $this->request->getPost('estado');
            
            $model = new ExtraccionesModel();
            $municipios = $model->getMunicipiosPorEstado($estado);
    
            return $this->response->setJSON($municipios);
        }
    
        // Opcional: Retornar un error si no es una solicitud AJAX
        return $this->response->setStatusCode(400, 'Bad Request');
    }
    
    public function getMeses()
{
    if ($this->request->isAJAX()) {
        $año = $this->request->getPost('año');

        $model = new \App\Models\ExtraccionesModel();
        $meses = $model->getMesesPorAño($año);

        return $this->response->setJSON($meses);
    }

    // Retornar un error si no es AJAX
    return $this->response->setStatusCode(400, 'Bad Request');
}


    public function filtrar()
    {
        if ($this->request->isAJAX()) {
            $estado = $this->request->getPost('estado');
            $año = $this->request->getPost('año');
            $municipio = $this->request->getPost('municipio');

            $model = new ExtraccionesModel();
            $conteo = $model->contarIncidentes($estado, $año, $municipio);

            // Obtener el nuevo token CSRF
            $csrfHash = csrf_hash();

            // Preparar la respuesta JSON
            $response = [
                'conteo' => $conteo,
                'csrfHash' => $csrfHash
            ];

            return $this->response->setJSON($response);
        }

        // Retornar un error si no es una solicitud AJAX
        return $this->response->setStatusCode(400, 'Bad Request');
    }
}
