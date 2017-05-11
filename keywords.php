
<?php
// keywords.php - modify keywords for a post or group of posts 
// copyright 2017 bob anzlovar 

// including the database connection file
include_once("config.php");
include_once("dci_functions.php");
$limit_clause = limits($_GET['p']);

if(isset($_POST['update']))
{	

	### Here is where we look at the list of fields and figure out what 
	### to do with keywords


    ### check for injections 
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);	
	
#	echo "<pre>";
#	var_dump($_POST);
#	echo "</pre>";

	// parse out the stuff from _POST, starting with the  keywords 




    
	$myboxes = $_POST['keywords'];
    if(empty($myboxes)) {
        echo("You didn't select any boxes.");
    } else {
        $sql = "DELETE FROM `keyword_results` 
                WHERE `article_id` = " . $_POST['id'] . 
                " AND `identifier` = '" . $_SERVER['REMOTE_ADDR'] . "'";
        $result = mysqli_query($mysqli, $sql);

        $i = count($myboxes);
        echo("You selected $i box(es): ");

        $sql = "INSERT INTO `keyword_results` (`article_id`,`keyword_id`,`identifier`) VALUES "; 
        for($j = 0; $j < $i; $j++) {
        	# silently drop them into the db.. Maybe want to make this one big transaction?  
            $sql .= " (" . 
             $_POST['id'] . "," .
             $myboxes[$j] . "," .
             "\"" . $_SERVER['REMOTE_ADDR'] . "\"),"; 


        }
        $regex = '/,$/';
        $sql = preg_replace($regex,'',$sql);
        echo "SQL $sql<br>";

        $result = mysqli_query($mysqli, $sql);

		
		//redirectig to the display page. In our case, it is index.php
		header("Location: index.php");
	}
}
?>


<?php
// important that we waited until here for the head stuff - the paragraph above 
// depended on there being no output so that the header can redirect back to
// the index 
include "head.php";
//getting id from url
$id = $_GET['id'];
# TODO need to verify that this is an id...


//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM messages WHERE id=$id");

while($res = mysqli_fetch_array($result))
{
	$id = $res['id'];
	$number = $res['number'];
	$date = $res['date'];
	$author = $res['author'];
	$subject = $res['subject'];
	$content = $res['content'];
	$topic_id = $res['topic_id'];
}
?>
    <div class="container">
       <div class="row">
  			<div class="col-sm-8">
<?PHP


echo 	urlencode($res['author']) ; 


echo "<h2>Author: <a href=\"index.php?a="
	.urlencode($author)."\">" 
	.$author.
	"</a></h2>";
echo "<h3>Subject: <a href=\"index.php?s="
	.urlencode($subject)."\">" 
	.$subject.
	"</a></h3>";

echo "<p class=\"lead\">$date</p>\n";

echo "<!-- id=" . $id . 
     ", number=" . $number . 
     ", topic_id=" . $topic_id . "-->\n";

echo "<div>" . $content . "</div>";
?>
            </div>
  			<div class="col-sm-4">
	<form name="form1" method="post" action="keywords.php">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="author" value="<?php echo $author;?>">


<?php // include the keywords selection box here 
    include "keywords_select.php";
?>
				<input type="submit" name="update" value="Update">
	</form>
  			</div> <!-- class=col-sm-4 -->
        </div> <!-- class=row -->
    </div> <!-- class=container -->
<?php include "foot.php"; ?>
