<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/galeria', 'Galeria::index');
$routes->get('recomendaciones', 'Recomendaciones::index');
$routes->get('extracciones', 'Extracciones::index');
$routes->post('extracciones/getMunicipios', 'Extracciones::getMunicipios');
$routes->post('extracciones/filtrar', 'Extracciones::filtrar');

$routes->post('recomendaciones/filtrar', 'Recomendaciones::filtrar');

$routes->post('extracciones/getMeses', 'Extracciones::getMeses');
$routes->post('galeria/filtrar', 'Galeria::filtrar');

