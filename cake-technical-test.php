<?php
/**
 * Cake technical test API script
 */

$retry_limit = 3;

$curl = curl_init();
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $curl, CURLOPT_URL, "https://fauxdata.codelayer.io/api/orders" );
curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );

$result = curl_exec( $curl );
$retries = 0;

if ( !$result && $retries++ <= $retry_limit ) {
    $result = curl_exec( $curl );
}

$data = json_decode( $result );

if ( !$data->orders || !is_array( $data->orders ) ) {
    die();
}

$running_total = 0;

foreach( $data->orders as $order ) {
    // Sum the order total up to the running total
    $running_total += array_reduce( $order->items, fn( $c, $i ) => $c += floatval( $i->price ) );
}

$average = $running_total / count( $data->orders );

exit();