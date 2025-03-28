<?php
require 'vendor/autoload.php'; // Load Composer packages

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Get the medicine name from the URL parameter
$medicine = isset($_GET['medicine']) ? $_GET['medicine'] : 'Default Medicine';

// Generate QR Code
$qrCode = QrCode::create($medicine)
    ->setSize(300)
    ->setMargin(10)
    ->setWriter(new PngWriter());

// Set response headers
header('Content-Type: '.$qrCode->getContentType());

// Output QR code image
echo $qrCode->writeString();
?>
