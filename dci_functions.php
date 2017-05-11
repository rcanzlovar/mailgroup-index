<?php
// dcifunctions.php - routines for all the drumcirclesindex pages 
//
// copy 2017 bob anzlovar
// limits = generate the SQL clause to limit the results to a range based 
//          on the page number. 


	// how many lines per page? 
    $perPage = 50; 
// generate the LIMIT clause for SQL commands based on the page number 
function limits($page) {
    // set up the paging buttons 
	$page  = isset($_GET['p']) ? $_GET['p'] : 1;
	$perPage = 50; 
	$start = (($page-1)*$perPage);
	$display_start = (($page-1)*$perPage)+1;
	$end = $perPage * $page;
	$limit_clause = " LIMIT $start,$end ";
	return (" LIMIT $start,$end ");
}
// It also needs the expected number of records 
// 
function nav_buttons($page,$numlines) {
	$perPage = 50; 

	if ($page > 1) {
	    echo "<a href=\"index.php\"><img src='img/prev-icon.png' width=32 alt='End'></a> ";
	}
	if ($page > 2) {
		echo "<a href=\"index.php?p=" . ($page-1) . "\"><img src='img/first-icon.png' width=32  alt='Previous'></a> ";
	}
	
		echo "<a href=\"index.php?p=" . ($page+1) . "\"><img src='img/next-icon.png' width=32 alt='Next'></a> ";
		echo "<a href=\"index.php?p=" . (intval($numlines/$perPage)) . "\"><img src='img/last-icon.png' width=32 alt='Last'></a> ";
}
?>