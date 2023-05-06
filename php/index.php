<?php

$input_file=file_get_contents('./INPUT.txt');
$salaries=explode(" ",$input_file);

$n=count($salaries);
$max_sal=$salaries[0];
$min_sal=$salaries[0];

$i=0;

for(;!($i==$n);$i++){
    if ($max_sal<$salaries[$i]){
        $max_sal=$salaries[$i];
    };
    if ($min_sal>$salaries[$i]){
        $min_sal=$salaries[$i];
    };
}
$delta=$max_sal-$min_sal;

echo ($delta);

?>