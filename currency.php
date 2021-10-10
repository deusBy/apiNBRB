<?
$_GET['amount']<>'' ? :$_GET['amount'] = 0; 
$option = array(
    'ondate' => $_GET['date'],
    'parammode' => '2',
);




function getNBRB($opt,$cur = 'BYN'){ //запрос курса у сервера НБРБ

    if ($cur=='BYN'){return [1,1];}
    else {
        $url = 'https://www.nbrb.by/api/exrates/rates/'.$cur;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($opt));
        $response = curl_exec($ch);
        if(!$response){
            //die("Ошибка соединения");
            return [-1, -1];
        }
        $data = json_decode($response, false);
        curl_close($ch);
        if (json_last_error() != JSON_ERROR_NONE){
            return [-1, -1];
            //die("Сервер вернул неверные данные, возможно, где-то ошибка.");
        }

        return [$data->Cur_OfficialRate, $data->Cur_Scale];
    }
}
if ($val == -1 || $val2 == -1) {
    $data = array ( 'rate' => -1, 'result' => -1);
    echo json_encode($data);
} else {
[$val,$scale] = getNBRB($option,$_GET['currency']);
[$val2,$scale2] = getNBRB($option,$_GET['currency2']);
if ($_GET['currency']=="RUB") {
    $rate = round($val / ($val2 * 100 ) , 4);
    $result = round($_GET['amount'] * $rate, 2);
}elseif ($_GET['currency2']=="RUB"){ 
    $rate = round($val * 100   / $val2 , 4);
    $result = round($_GET['amount'] * $rate, 2);
}
else
{ 
    $rate = round($val / $val2 , 4);
    $result = round($_GET['amount'] * $rate, 2);
} ;
$data = array ( 'rate' => $rate, 'result' => $result);
echo json_encode($data);
}
?>