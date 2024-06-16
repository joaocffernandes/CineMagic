<?php

namespace App\Services;

use QrCode;

class QrCodeService 
{
    public static function getQrCodeImageBase64(string $qrcode_url) 
    {
        $qrImageBase64 = QrCode::format('svg')
            ->size(200)
            ->generate($qrcode_url);

        return 'data:image/svg;base64,' . base64_encode($qrImageBase64);
    }
}
