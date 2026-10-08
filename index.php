<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : 'productos';

// 💡 NUEVA RUTA: Intercepta la descarga directa del PDF antes de cargar cabeceras HTML
if ($modulo === 'descargar_pdf') {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'controlador' . DIRECTORY_SEPARATOR . 'ReportePdfControlador.php';
    $controladorPdf = new ReportePdfControlador();
    $controladorPdf->descargarReporteProductos();
    exit();
}

if ($modulo === 'unidades') {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'controlador' . DIRECTORY_SEPARATOR . 'UnidadControlador.php';
    $controlador = new UnidadControlador();
    $controlador->gestionarUnidades();
} elseif ($modulo === 'reporte_productos') {
    // 1. Instanciamos el modelo de productos para traer los datos reales de la BD
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'modelo' . DIRECTORY_SEPARATOR . 'ProductoModelo.php';
    $modeloProducto = new ProductoModelo();
    $productos = $modeloProducto->listarProductosConUnidad();
    if (!$productos) { $productos = []; }

    // 2. Cargamos la cabecera HTML común
    imprimirCabeceraHTML("Reporte de Productos");
    
    // 3. Cargamos el menú horizontal
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'vista' . DIRECTORY_SEPARATOR . 'menu.php';
    
    // 4. Cargamos la nueva vista exclusiva para el reporte de productos
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'vista' . DIRECTORY_SEPARATOR . 'ReporteProductosVista.php';
    
    // 5. Cargamos el pie HTML
    imprimirPieHTML();
} elseif ($modulo === 'reporte_unidades') {
    // 📐 VISTA DE REPORTE DE UNIDADES CON ESTILOS
    imprimirCabeceraHTML("Reporte de Unidades");
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'vista' . DIRECTORY_SEPARATOR . 'menu.php';
    
    echo '<div class="container my-5 text-center">';
    echo '  <h2 class="text-secondary">📐 Reporte Detallado de Unidades</h2>';
    echo '  <p class="text-muted">Aquí visualizarás cuántos productos tiene asignada cada unidad de medida.</p>';
    echo '</div>';
    
    imprimirPieHTML(); // Mantenemos la llamada aquí
} else {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'controlador' . DIRECTORY_SEPARATOR . 'ProductoControlador.php';
    $controlador = new ProductoControlador();
    $controlador->gestionarRegistro();
}

// ESTA FUNCIÓN SE QUEDA AQUÍ (Es la que arma la cabecera de los reportes)
function imprimirCabeceraHTML($titulo) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $titulo . '</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">';
}

// ESTA FUNCIÓN TAMBIÉN SE QUEDA AQUÍ (Es la que cierra las páginas de los reportes)
function imprimirPieHTML() {
    echo '<script src="js/bootstrap.bundle.min.js"></script>
    </body>
    </html>';
}
// NOTA: Asegúrate de que no haya ninguna línea extra después de esta llave de cierre.