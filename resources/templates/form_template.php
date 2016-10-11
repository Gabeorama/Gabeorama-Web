<?php 
	/* Template for building forms */
?>
<h1 xmlns="http://www.w3.org/1999/html"><?php echo $form["title"]; ?></h1>
<?php if (isset($form["errorMessage"])) { ?>
	<div class="alert alert-danger">
        <strong>Error:</strong> <?php print($form["errorMessage"]); ?>
    </div>
<?php } ?>
<form class="form-horizontal" action="<?php echo($form["action"]);?>" method="<?php echo($form["method"]); ?>" name="<?php echo($form["name"]); ?>">
    <?php foreach ($form["formObjects"] as $ID => $formObject):
        $type = $formObject["type"];
        $error = false;
        //Display errors to user
        if (isset($formObject["error"])) {
            $error = true;
            $errorMsg = $formObject["error"];
        }
        if ($type == "text" or $type == "email" or $type == "password") { ?>
    <div class="form-group <?php if ($error) print("has-error") ?>">
        <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
        <div class="col-sm-9">
            <input name="<?php print($ID); ?>" type="<?php print($type);?>" class="form-control" id="<?php print($ID); ?>" <?php if ($error) print('aria-describedby="error' . $ID . '"');?>>
        </div>
        <?php if ($error) {?>
            <span id="error<?php print($ID); ?>" class="help-block col-sm-offset-3 col-sm-9"><?php print($errorMsg); ?></span>
        <?php } ?>
    </div>
        <?php } elseif ($type == "info" or $type == "error") {
            $modif = $type == "error" ? "danger" : $type;
            ?>
            <div class="alert alert-<?php print($modif); ?>">
                <?php print($formObject["value"]); ?>
            </div>
        <?php } elseif ($type == "header") {
            ?>
            <h3><?php print($formObject["value"]); ?></h3>
        <?php } elseif ($type == "select") { ?>
            <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
            <div class="col-sm-9">
                <select id="<?php print($ID); ?>" class="form-control" name="<?php print($ID);?>">
                    <?php foreach ($formObject["value"] as $value): ?>
                    <option><?php print($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php } elseif ($type == "textarea") { ?>
            <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
            <div class="col-sm-9">
                <textarea id="<?php print($ID); ?>" name="<?php print($ID); ?>" class="form-control col-sm-9" rows="<?php print $formObject["rows"]; ?>" style="margin-top: 0; margin-bottom: 10px"></textarea>
            </div>
        <?php }
    endforeach; ?>
    <div class="form-group">
	    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>