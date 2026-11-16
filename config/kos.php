<?php

return [
    // Isi data asli pemilik kos di .env sebelum digunakan untuk transaksi.
    'bank_name' => env('KOS_BANK_NAME', ''),
    'bank_account' => env('KOS_BANK_ACCOUNT', ''),
    'bank_holder' => env('KOS_BANK_HOLDER', ''),
    // Format nomor Indonesia: 628xxxxxxxxxx (tanpa tanda +).
    'owner_whatsapp' => env('KOS_OWNER_WHATSAPP', ''),
];
