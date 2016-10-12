<?php 
	/* Template for building forms */

	function addElement($ID, $formObject) {
        $type = $formObject["type"];
        $error = false;
        //Display errors to user
        if (isset($formObject["error"])) {
            $error = true;
            $errorMsg = $formObject["error"];
        }

        /** Simple text boxes */

        if ($type == "text" or $type == "email" or $type == "password") {?>
            <div class="form-group <?php if ($error) print("has-error") ?>">
                <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
                <div class="col-sm-9">
                    <input name="<?php print($ID); ?>" type="<?php print($type);?>" class="form-control" id="<?php print($ID); ?>" <?php if ($error) print('aria-describedby="error' . $ID . '"');?>>
                </div>
                <?php if ($error) {?>
                    <span id="error<?php print($ID); ?>" class="help-block col-sm-offset-3 col-sm-9"><?php print($errorMsg); ?></span>
                <?php } ?>
            </div>
        <?php }

        /** Info panels */

        elseif ($type == "info" or $type == "error") {
            $modif = $type == "error" ? "danger" : $type;
            ?>
            <div class="alert alert-<?php print($modif); ?>">
                <?php print($formObject["value"]); ?>
            </div>
        <?php }

        /** Section headers */

        elseif ($type == "header") {
            ?>
            <h3><?php print($formObject["value"]); ?></h3>
        <?php } elseif ($type == "select") { ?>
            <div class="form-group">
                <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php isset($formObject["text"]) ? print($formObject["text"]) : print(""); ?></label>
                <div class="col-sm-9">
                    <select id="<?php print($ID); ?>" class="form-control" name="<?php print($ID);?>">
                        <?php foreach ($formObject["value"] as $value): ?>
                            <option><?php print($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php }

        /** Text Area */

        elseif ($type == "textarea") { ?>
            <div class="form-group">
                <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
                <div class="col-sm-9">
                    <textarea id="<?php print($ID); ?>" name="<?php print($ID); ?>" class="form-control col-sm-9" rows="<?php print $formObject["rows"]; ?>" style="margin-top: 0; margin-bottom: 10px"></textarea>
                </div>
            </div>
        <?php }

        /** Date/time selection */

        elseif ($type == "dateTime") { ?>
            <div class="form-group">
                <label class="control-label col-sm-3" for="<?php print($ID); ?>"><?php print($formObject["text"]); ?></label>
                <div id="dateTime-<?php print($ID); ?>" class="input-group date col-sm-9">
                    <input type="text" name="<?php print($ID); ?>" class="form-control" id="<?php print($ID); ?>">
                    <span class="input-group-addon add-on">
                        <i class="glyphicon glyphicon-calendar" data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i>
                    </span>
                </div>
                <script>
                    $.getScript("http://<?php print($_SERVER['HTTP_HOST']); ?>/js/bootstrap-datetimepicker.min.js", function() {
                        $.getScript("http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js", function() {
                            $("#dateTime-<?php print($ID); ?>").datetimepicker({
                                format: "yyyy-MM-dd hh:mm:ss",
                                language: 'en',
                                pickTime: true
                            });

                            var picker = $("#dateTime-<?php print($ID); ?>").data("datetimepicker");
                            picker.setLocalDate(new Date());
                        });
                    });
                </script>
            </div>
        <?php }

        /** Inline  */

        elseif ($type == "inline") { ?>
            <div class="row">
                <fieldset class="form-inline">
                    <?php
                    foreach ($formObject["value"] as $id2 => $obj):
                        addElement($id2, $obj);
                    endforeach;
                    ?>
                </fieldset>
            </div>
        <?php }

        /** Buttons */

        elseif ($type == "button") {?>
            <button type="button" class="btn btn-default form-control"><?php print($formObject["value"]); ?></button>
        <?php }
    }
?>
<h1><?php echo $form["title"]; ?></h1>
<?php if (isset($form["errorMessage"])) { ?>
	<div class="alert alert-danger">
        <strong>Error:</strong> <?php print($form["errorMessage"]); ?>
    </div>
<?php } ?>

<!-- actual form -->

<form class="form-horizontal" action="<?php echo($form["action"]);?>" method="<?php echo($form["method"]); ?>" name="<?php echo($form["name"]); ?>">
    <?php foreach ($form["formObjects"] as $ID => $formObject):
        addElement($ID, $formObject);
    endforeach; ?>
    <div class="form-group">
	    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>