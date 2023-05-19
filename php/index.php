<?php

$fileInput = file_get_contents("INPUT.txt");
$holidays=explode(" ",$fileInput);

$today=getdate();

while ($currentHoliday = current($holidays)) {
    $array=explode("-",$currentHoliday);
    $allHolidays[intval($array[0])][intval($array[1])]=1;
    next($holidays);
}

$maxMonthDay[1]=31;
if (intval($today["year"])%4===0){
    $maxMonthDay[2]=29;
} else  {
    $maxMonthDay[2]=28;
};
$maxMonthDay[3]=31;
$maxMonthDay[4]=30;
$maxMonthDay[5]=31;
$maxMonthDay[6]=30;
$maxMonthDay[7]=31;
$maxMonthDay[8]=31;
$maxMonthDay[9]=30;
$maxMonthDay[10]=31;
$maxMonthDay[11]=30;
$maxMonthDay[12]=31;

$monthDateName[1]='января';
$monthDateName[2]='февраля';
$monthDateName[3]='марта';
$monthDateName[4]='апреля';
$monthDateName[5]='мая';
$monthDateName[6]='июня';
$monthDateName[7]='июля';
$monthDateName[8]='августа';
$monthDateName[9]='сентября';
$monthDateName[10]='октября';
$monthDateName[11]='ноября';
$monthDateName[12]='декабря';

$arrivalDate[0]=intval($today["mon"]);
$arrivalDate[1]=intval($today["mday"]);

if (intval($today["hours"])<20){
    $arrivalDate[1]++;
}else{
    $arrivalDate[1]+=2;
}

while ($allHolidays[$arrivalDate[0]][$arrivalDate[1]]===1){
    $arrivalDate[1]=$arrivalDate[1]+1;//+day
    if ($arrivalDate[1]>$maxMonthDay[$arrivalDate[0]]){
        $arrivalDate[1]=$arrivalDate[1]-$maxMonthDay[$arrivalDate[0]];
        $arrivalDate[0]++;
        if ($arrivalDate[0]>12){
            $arrivalDate[0]=$arrivalDate[0]-12;
        }
    }
}

$arrivalDate[2]="Дата ближайшей доставки ".$arrivalDate[1]." ".$monthDateName[$arrivalDate[0]];
echo $arrivalDate[2];
?>
