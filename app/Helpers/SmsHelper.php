<?php

function sendSms($to, $message)
{
    $curl = curl_init();
    $data = array("api_key" => "TLs2Bl5jWipLkeH6OatRBn6ib3kl3nTuH7dN9xu46v5SuKrcqKCQKwlvkoQAUq", "to" => '234' . substr($to, 1), "from" => "Kaytwo",
        "sms" => $message, "type" => "plain", "channel" => "generic");

    $post_data = json_encode($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}

