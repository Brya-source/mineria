<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtraccionesModel extends Model
{
    protected $table = 'extracciones_filtradas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pais', 'estado', 'municipio', 'liberacion', 'tipo_liberacion', 
        'mes_secuestro', 'año_secuestro', 'captor', 'lugar', 'captura', 'tipo_secuestro'
    ];

    public function getEstados()
    {
        return $this->select('estado')
                    ->distinct()
                    ->where('estado !=', '')
                    ->orderBy('estado', 'ASC')
                    ->findAll();
    }

    public function getAños()
    {
        return $this->select('año_secuestro')
                    ->distinct()
                    ->where('año_secuestro !=', '')
                    ->orderBy('año_secuestro', 'ASC')
                    ->findAll();
    }

    public function getMunicipiosPorEstado($estado)
    {
        return $this->select('municipio')
                    ->distinct()
                    ->where('estado', $estado)
                    ->where('municipio !=', '')
                    ->orderBy('municipio', 'ASC')
                    ->findAll();
    }

    public function contarIncidentes($estado, $año, $municipio = null)
    {
        $builder = $this->builder();

        if ($estado) {
            $builder->where('estado', $estado);
        }

        if ($año) {
            $builder->where('año_secuestro', $año);
        }

        if ($municipio) {
            $builder->where('municipio', $municipio);
        }

        return $builder->countAllResults(false);
    }

    public function contarIncidentesPorEstadoYAño($estado = null, $año = null)
{
    $builder = $this->builder();

    if (!empty($estado) && empty($año)) {
        // Si solo se eligió estado
        $builder->where('estado', $estado);
    } elseif (empty($estado) && !empty($año)) {
        // Si solo se eligió año
        $builder->where('año_secuestro', $año);
    } elseif (!empty($estado) && !empty($año)) {
        // Si se eligió estado y año
        $builder->where('estado', $estado);
        $builder->where('año_secuestro', $año);
    } else {
        // Si no se elige nada, podrías decidir qué hacer (opcional)
        // Por ejemplo, retornar el conteo total
    }

    return $builder->countAllResults(false);
}
public function getMunicipios()
{
    return $this->select('municipio')
                ->distinct()
                ->where('municipio !=', '')
                ->orderBy('municipio', 'ASC')
                ->findAll();
}

public function contarIncidentesPorMunicipio($municipio = null)
{
    $builder = $this->builder();

    if (!empty($municipio)) {
        $builder->where('municipio', $municipio);
    }

    // Retorna el conteo. Si no se selecciona municipio, se podría contar todos, 
    // pero aquí es opcional. Al menos con municipio seleccionado, funcionará.
    return $builder->countAllResults(false);
}

public function contarIncidentesPorMunicipioYAño($municipio = null, $año = null)
{
    $builder = $this->builder();

    if (!empty($municipio) && empty($año)) {
        // Solo municipio
        $builder->where('municipio', $municipio);
    } elseif (empty($municipio) && !empty($año)) {
        // Solo año
        $builder->where('año_secuestro', $año);
    } elseif (!empty($municipio) && !empty($año)) {
        // Municipio y año
        $builder->where('municipio', $municipio);
        $builder->where('año_secuestro', $año);
    } 
    // Si no se elige nada, retornará el conteo total de la tabla, 
    // opcionalmente podrías dejarlo así o decidir otro comportamiento.

    return $builder->countAllResults(false);
}


public function getMesesPorAño($año)
{
    return $this->select('mes_secuestro')
                ->distinct()
                ->where('año_secuestro', $año)
                ->where('mes_secuestro !=', '')
                ->orderBy('mes_secuestro', 'ASC')
                ->findAll();
}


}
