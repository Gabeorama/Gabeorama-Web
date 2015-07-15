<?php 
	/* Template for building forms */
?>
<h1><?php echo $form["title"]; ?></h1>
<?php if (isset($form["errorMessage"])) {
	echo("<h4 style=\"color: red\">{$form["errorMessage"]}</h4>");
} ?>
<form action="<?php echo($form["action"]);?>" method="<?php echo($form["method"]); ?>" name="<?php echo($form["name"]); ?>">
	<table style="margin: 0 auto 0 auto; text-align: right">
<?php foreach ($form["formObjects"] as $ID => $formObject): 
	if ($formObject["type"] == "text" or $formObject["type"] == "password") { ?>
		<tr>
			<td><label for="<?php echo($ID); ?>"><?php echo($formObject["text"]); ?></label></td>
			<td><input type="<?php echo($formObject["type"]); ?>" name="<?php echo($ID); ?>"></td>
		</tr>
<?php }
endforeach ?>
	</table>
	<input type="submit" name="submit"/>
</form>