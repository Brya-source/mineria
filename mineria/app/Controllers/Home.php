<?php

namespace App\Controllers;

use App\Models\ExtraccionesModel;
use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $model = new ExtraccionesModel();
        $estados = $model->getEstados();
        $años = $model->getAños();

        $data = [
            'estados' => $estados,
            'años' => $años
        ];

        return view('home', $data);
    }
}
