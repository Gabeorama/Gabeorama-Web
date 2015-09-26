<?php 
	/* Template for building forms */
?>
<h1><?php echo $form["title"]; ?></h1>
<?php if (isset($form["errorMessage"])) {
	echo("<h4 style=\"color: red\">{$form["errorMessage"]}</h4>");
} ?>
<form action="<?php echo($form["action"]);?>" method="<?php echo($form["method"]); ?>" name="<?php echo($form["name"]); ?>">
	<table class="formTable" align="center" border="1px">
    <?php foreach ($form["questions"] as $ID => $formObject):
	    if ($formObject["type"] == "text" or $formObject["type"] == "textBox" or $formObject["type"] == "password") { ?>
            <tr>
                <td><label for="<?php echo($ID); ?>"><?php echo($formObject["text"]); ?></label></td>
                <td><input type="<?php echo($formObject["type"]); ?>" name="<?php echo($ID); ?>"></td>
            </tr>
        <?php } elseif ($formObject["type"] == "textarea") { ?>
            <tr>
                <td><label for="<?php echo ($ID); ?>"><?php echo($formObject["text"]); ?></label></td>
                <td><textarea cols="<?php echo($formObject["cols"]); ?>" rows="<?php echo($formObject["rows"]); ?>" name="<?php echo($ID); ?>"></textarea></td>
            </tr>
        <?php } elseif ($formObject["type"] == "hidden") { ?>
            <input type="hidden" name="<?php echo($ID); ?>" value="<?php echo($formObject["value"]); ?>" />
        <?php } elseif ($formObject["type"] == "staticText") { ?>
            </table>
            <?php echo($formObject["value"]);?>
            <table class="formTable" align="center">
        <?php } elseif ($formObject["type"] == "radioBox") {?>
            <tr>
                <td><label for="<?php echo($ID)?>"><?php echo ($formObject["text"]); ?></label></td>
                <td>
                <?php foreach ($formObject["options"] as $option) {?>
                    <input type="radio" name="<?php echo ($ID);?>" value="<?php echo ($option["value"]);?>"><?php echo ($option["text"]);?><br/>
                <?php } ?>
                </td>
            </tr>
        <?php }
    endforeach ?>
	</table>
	<input type="submit" name="submit"/>
</form>