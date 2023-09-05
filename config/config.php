<?php


return [

    "payment_url" => env("CARD_PAYMENT_URL"),

    "secure_secret" => env("CARD_PAYMENT_SECURE_SECRET"),

    "merchant_id" => env("CARD_PAYMENT_MERCHANT_ID"),

    "merchant_name" => env("CARD_PAYMENT_MERCHANT_NAME"),

    "redirect_url" => env("CARD_PAYMENT_REDIRECT_URL"),

    "address_line1" => env("CARD_PAYMENT_ADDRESS_LINE1"),

    "address_line2" => env("CARD_PAYMENT_ADDRESS_LINE2"),

    "redirect_url_external" => env("CARD_PAYMENT_REDIRECT_URL_EXTERNAL"),
];
