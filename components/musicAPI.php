<?php
    //30 por mês
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://billboard-api2.p.rapidapi.com/hot-trending-songs-powered-by-twitter?date=2023-06-24&range=1-10",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: billboard-api2.p.rapidapi.com",
            "X-RapidAPI-Key: 7e4fba7ef0msh663d8ab56e536dcp1e1bf9jsn059b47501ff1"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }