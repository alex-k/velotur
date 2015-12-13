<?php

$smarty=gs_tpl::get_instance();
include "../config/trip_difficulty.php";
$smarty->assign('d_tourdifficulty',$difficultyarray);
include "../config/trip_comfort.php";
$smarty->assign('d_tourcomfort',$comfortarray);


