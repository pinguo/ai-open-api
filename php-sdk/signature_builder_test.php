<?php

include "./SignatureBuilder.php";

$builder = new SignatureBuilder("ak","sk",300);
$rs = $builder->sign("/v1/photos/generate",["data"=>"a"],["a"=>"b","d"=>"c"],"","1751447149");
print_r($rs);
print_r($builder);