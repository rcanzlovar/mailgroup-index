<?php
// index.php - main page for Drumcircles index
// copyright 2017 bob anzlovar 

//including the database connection file
include_once("config.php");

include "head.php";

include_once("dci_functions.php"); // common functions 

$limit_clause = limits($_GET['p']);

// initialize where clause to be blank so we get all by default. 
$where_clause = "";
//fetching data in descending order (lastest entry first)
$direction = " DESC ";

?>
    <div class="container">
<?php

$thing = "";
if(isset($_GET['s'])) {
	// clean up the subject so that we can get all the relevent 
	// ones remove "re" and "fwd", bracketed bits, leading white space
	$thing = urldecode($_GET['s']);
	$thing = mysqli_real_escape_string ( $mysqli ,$thing );
	$thing = str_replace("Re: ","",$thing);
	$thing = str_replace("Fwd: ","",$thing);
	// get rid of [1 attachment] and other mailing list names like [edcf] 
#    $thing = preg_replace('\[.+?\]','',$thing);
	$regex = '/\[[^\]]*\]/'; //This clever little gem selects square brackets with 
	                         // anything between them except a right square bracket
	                         // what does it do with [[] ? 
    $thing = preg_replace($regex,'',$thing);
    $thing = preg_replace('/^\s+/','',$thing); // leading whitespace
    $regex = '/\s+$/';
    $thing = preg_replace($regex,'',$thing); // trailing whitespace

    // we only want things by subject, so put something in the where clause...
    $where_clause = " WHERE subject like '%$thing%' ";
    // and threads are a little more interesting read from older to newer, so.. 
    $direction = " ASC ";
}

# show by author 
if(isset($_GET['a'])) {
	$thing = urldecode($_GET['a']);
	$thing = mysqli_real_escape_string ( $mysqli ,$thing );
    $where_clause = " WHERE author like '%$thing%'";
}
# show by keyword 
if(isset($_GET['k'])) {
	#join syntax to get the only ones that have this keyword applied to them. 
    $sql = "SELECT * 
            FROM messages m,keyword_results k 
            WHERE m.id = k.article_id AND k.keyword_id = ". $_GET['k'];
}
?>
<h2><?php echo $thing; ?></h2>
    </div> <!-- class=container -->
<?php
    if (!isset($sql)) {
        $sql = "SELECT * 
                FROM messages $where_clause 
                ORDER BY id $direction $limit_clause";
    }
    $result = mysqli_query($mysqli, $sql); // using mysqli_query instead
?>
    <div class="container">
       <div class="row">
  			<div class="col-sm-12">
<?php 
// hard coded number of records for now
    nav_buttons($_GET['p'],41896);
?>
	<table width='80%' border=0>
	<tr bgcolor='#CCCCCC'>
		<td>Date</td>
		<td>Author</td>
		<td>Subject</td>
		<td><a href="index.php"><img src="img/home-icon.png" width="32" alt="Home"></a>
	</tr>
	<?php 
	//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
	while($res = mysqli_fetch_array($result)) { 		
		echo "<TR>";
		echo "<TD>" . $res['date']."</td>";
		echo "<TD><A HREF=\"index.php?a="
		.urlencode($res['author'])."\">" 
		.$res['author']
		."</A></TD>";	

		echo "<TD><A HREF=\"index.php?s="
		.urlencode($res['subject'])."\">" 
		.$res['subject']
		."</A></TD>";	
	
		echo "<TD>
		<A HREF=\"keywords.php?id=$res[id]\"><IMG SRC=\"img/keywords-icon.png\"/ WIDTH=\"32\" ALT=\"Keywords\"></A> 

		</TD></TR> ";		
//		<a href=\"edit.php?id=$res[id]\"><img src=\"img/edit-icon.png\"/ width=\"32\" alt=\"Edit\"></a> 
//		<a href=\"delete.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\"><img src=\"img/delete-icon.png\"/ width=\"32\" alt=\"Delete\"></a>
	}
	?>
	</table> 
<?php 
// hard coded number of records for now
    nav_buttons($_GET['p'],41896);
?>
	</div> <!-- container -->
	</div> <!-- row -->
	</div> <!-- col -->
<?php include "foot.php"; ?>