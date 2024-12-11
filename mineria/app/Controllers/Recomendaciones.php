<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ExtraccionesModel;
use Config\Database;

class Recomendaciones extends Controller
{
    public function index()
    {
        // Conexión a la base de datos
        $db = Database::connect();
    
        // Ejecutar la consulta recomendaciones
        $query = $db->query("SELECT * FROM recomendaciones");
        $resultados = $query->getResultArray();
    
        // Cargar estados y años desde ExtraccionesModel
        $extraccionesModel = new ExtraccionesModel();
        $estados = $extraccionesModel->getEstados();
        $años = $extraccionesModel->getAños();
    
        $data = [
            'recomendaciones_db' => $resultados,
            'estados' => $estados,
            'años' => $años,
            // Por defecto no habrá resultado hasta filtrar
            'conteo' => null
        ];
    
        return view('recomendaciones', $data);
    }
    
    // Agrega un nuevo método para procesar el filtro vía AJAX
    public function filtrar()
    {
        $estado = $this->request->getPost('estado');
        $año = $this->request->getPost('año');
    
        $extraccionesModel = new ExtraccionesModel();
        $conteo = $extraccionesModel->contarIncidentesPorEstadoYAño($estado, $año);
    
        return $this->response->setJSON(['conteo' => $conteo]);
    }
    
}
