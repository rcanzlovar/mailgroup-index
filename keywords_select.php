<div id="keywords">
<a href="index.php"><img src="img/home-icon.png"  width="32" alt="Home"></a>
<br/>
<?php

#    include_once("config.php");

# this will give us the count of how many reports of each keyword exist 
# so you can click into the category views
    $sql = "SELECT keyword_id,count(*) AS keyword_count 
        FROM keyword_results
        GROUP BY keyword_id";
    $result = mysqli_query($mysqli, $sql); // using mysqli_query instead
	while($res = mysqli_fetch_array($result)) { 	
		$keywordcounts[$res['keyword_id']] = $res['keyword_count'];
	}
# fetch keyword responses
# generate this IP or cookie's previous response
    $sql = "SELECT keyword_id,identifier 
            FROM `keyword_results` 
            WHERE article_id = $id AND identifier = '" . $_SERVER['REMOTE_ADDR'] . "'";
    $result = mysqli_query($mysqli, $sql); // using mysqli_query instead
	while($res = mysqli_fetch_array($result)) { 	
		$mychoices[$res['keyword_id']] = 1;
	}

//fetching keywords
	$sql = "SELECT * FROM keywords ORDER BY subcat,keyword ASC";
    $result = mysqli_query($mysqli, $sql);

	while($res = mysqli_fetch_array($result)) { 	
		$id = $res['id'];
		$keyword = $res['keyword'];
		$fieldname = "kwd_" . $id . "_" . $keyword;
		$checked_value = "";
		if (isset($mychoices[$id])) { $checked_value  = " CHECKED ";}
		 
		$response_count = "";
		if (isset($keywordcounts[$id])) {
		    $response_count  = "(" . $keywordcounts[$id] . ")";
		}
?>
 <input type="checkbox" name="keywords[]" value="<?php echo $id ?>" 
 id="<?php echo $fieldname ?>" <?php echo $checked_value; ?>> <label for="<?php echo $fieldname ?>"><a href=index.php?k=<?php echo $id ?>><?php echo  $keyword ?></a></label><?php echo $response_count; ?><br/>
<?php
    }
    mysqli_free_result($result);
?>
</div>