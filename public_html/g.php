<?php
	require_once(realpath(dirname(__FILE__) . "/../resources/configuration.php"));
	require_once(LIBRARY_PATH . "/surveys.php");
echo"what";
	//getSurvey(1);
$survey = array(
	array(
		"text" => "Håviss er så dom (ca.): ",
		"type" => "text",
		"position" => 0
	), array(
		"text" => "Domme personer: ",
		"type" => "radioBox",
		"options" => array(
			"håviss",
			"tinus",
			"johan"
		),
		"position" => 1
	)
);
echo "the";

createTable("gernjon", sqlConnect($configuration->db->gabeorama->dbname), $survey);
echo "fcuk";
	$title = "G";
	include_once(TEMPLATES_PATH . "/header.php");
	echo" is";
?>
<h1 style="font: bold 150px Comic; width: 100px; margin: auto;">G</h1>
<?php
	echo "going on";
	include_once(TEMPLATES_PATH . "/footer.php");
?>
