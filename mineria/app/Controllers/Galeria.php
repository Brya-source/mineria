<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ExtraccionesModel;
use Config\Database;

class Galeria extends Controller
{
    public function index()
    {
        $db = Database::connect();
        
        $extraccionesModel = new ExtraccionesModel();
        $municipios = $extraccionesModel->getMunicipios();
        $años = $extraccionesModel->getAños();

        $data = [
            'municipios' => $municipios,
            'años' => $años,
            'conteo' => null
        ];

        return view('galeria', $data);
    }

    public function filtrar()
    {
        $municipio = $this->request->getPost('municipio');
        $año = $this->request->getPost('año');
    
        $extraccionesModel = new \App\Models\ExtraccionesModel();
        $conteo = $extraccionesModel->contarIncidentesPorMunicipioYAño($municipio, $año);
    
        return $this->response->setJSON(['conteo' => $conteo]);
    }
    
    

}
