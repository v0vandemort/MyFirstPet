<?php

$FileInput = file_get_contents("INPUT.txt");
$Holidays=explode(" ",$FileInput);

$Today=getdate();

while ($CurrentHoliday = current($Holidays)) {
    $Array=explode("-",$CurrentHoliday);
    $AllHolidays[intval($Array[0])][intval($Array[1])]=1;
    next($Holidays);
}

$MaxMonthDay[1]=31;
if (intval($Today["year"])%4===0){
    $MaxMonthDay[2]=29;
} else  {
    $MaxMonthDay[2]=28;
};
$MaxMonthDay[3]=31;
$MaxMonthDay[4]=30;
$MaxMonthDay[5]=31;
$MaxMonthDay[6]=30;
$MaxMonthDay[7]=31;
$MaxMonthDay[8]=31;
$MaxMonthDay[9]=30;
$MaxMonthDay[10]=31;
$MaxMonthDay[11]=30;
$MaxMonthDay[12]=31;

$MonthDateName[1]='января';
$MonthDateName[2]='февраля';
$MonthDateName[3]='марта';
$MonthDateName[4]='апреля';
$MonthDateName[5]='мая';
$MonthDateName[6]='июня';
$MonthDateName[7]='июля';
$MonthDateName[8]='августа';
$MonthDateName[9]='сентября';
$MonthDateName[10]='октября';
$MonthDateName[11]='ноября';
$MonthDateName[12]='декабря';

$ArrivalDate[0]=intval($Today["mon"]);
$ArrivalDate[1]=intval($Today["mday"]);

if (intval($Today["hours"])<20){
    $ArrivalDate[1]++;
}else{
    $ArrivalDate[1]+=2;
}

while ($AllHolidays[$ArrivalDate[0]][$ArrivalDate[1]]===1){
    $ArrivalDate[1]=$ArrivalDate[1]+1;//+day
    if ($ArrivalDate[1]>$MaxMonthDay[$ArrivalDate[0]]){
        $ArrivalDate[1]=$ArrivalDate[1]-$MaxMonthDay[$ArrivalDate[0]];
        $ArrivalDate[0]++;
        if ($ArrivalDate[0]>12){
            $ArrivalDate[0]=$ArrivalDate[0]-12;
        }
    }
}

$ArrivalDate[2]="Дата ближайшей доставки ".$ArrivalDate[1]." ".$MonthDateName[$ArrivalDate[0]];
echo $ArrivalDate[2];
