<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'modelo' . DIRECTORY_SEPARATOR . 'ProductoModelo.php';

class ReportePdfControlador {
    
    public function descargarReporteProductos() {
        // Aseguramos la limpieza absoluta del búfer de salida antes de procesar el binario del PDF
        if (ob_get_length()) ob_end_clean();

        $modelo = new ProductoModelo();
        $productos = $modelo->listarProductosConUnidad();
        if (!$productos) { $productos = []; }

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);

        // --- ENCABEZADO DEL DOCUMENTO ---
        $pdf->SetFont('courier', '', 14); 
        $pdf->SetTextColor(13, 110, 253); 
        // 💡 Solución: mb_convert_encoding convierte de UTF-8 a ISO-8859-1 para evitar errores en tildes y caracteres especiales
        $pdf->Cell(0, 10, mb_convert_encoding('SISTEMA POS - REPORTE DE INVENTARIO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        
        $pdf->SetFont('courier', '', 10); 
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, mb_convert_encoding('Generado el: ' . date('d/m/Y H:i'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(10); 

        // --- CABECERA DE LA TABLA ---
        $pdf->SetFont('courier', '', 10); 
        $pdf->SetFillColor(240, 240, 240); 
        $pdf->SetTextColor(0, 0, 0);

        // Anchos de las columnas
        $pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
        $pdf->Cell(75, 8, mb_convert_encoding('Nombre del Producto', 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', true);
        $pdf->Cell(30, 8, 'Precio', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Stock', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Medida', 1, 1, 'C', true); 

        // --- CUERPO DE LA TABLA ---
        $pdf->SetFont('courier', '', 9);
        
        if (empty($productos)) {
            $pdf->Cell(180, 10, mb_convert_encoding('No hay productos registrados.', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
        } else {
            foreach ($productos as $p) {
                $pdf->Cell(15, 8, '#' . $p['id'], 1, 0, 'C');
                $pdf->Cell(75, 8, mb_convert_encoding($p['nombre_producto'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
                $pdf->Cell(30, 8, '$' . number_format($p['precio'], 2), 1, 0, 'C');
                $pdf->Cell(25, 8, $p['cantidad'], 1, 0, 'C');
                $pdf->Cell(35, 8, mb_convert_encoding($p['unidad_medida'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
            }
        }

        // Forzar descarga del archivo sin advertencias previas
        $pdf->Output('D', 'Reporte_Inventario_' . date('Ymd') . '.pdf');
        exit();
    }
}