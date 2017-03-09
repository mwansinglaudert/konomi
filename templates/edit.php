<?php $edit = $_GET;?>
<div id="ajax-header">
    Bearbeiten
</div>
<div id="ajax-content">
    <form class="js--post-form" action="index.php?post&update" method="post">
        <input type="hidden" name="delete" value="0">
        <div class="_scroll">
            <h2 class="modal-headline"><?php echo $edit['name'];?></h2>
            <ul class="ios-list">
                <?php
                    foreach($edit as $editKey => $editVal){
                        if($editKey != 'template' && $editKey != 'timestamp' && $editKey != 'time' && $editKey != 'type' && $editKey != 'image') {
                            if ($editKey == 'name' || $editKey == 'id') {
                                ?>
                                <input id="edit<?php echo $editKey; ?>" name="<?php echo $editKey; ?>" type="hidden"
                                       value="<?php echo $editVal; ?>">
                                <?php
                            } else {
                                ?>
                                <li class="ios-text-input <?php if($editKey == 'sum'){echo 'ios-text-input-brand';}?>">
                                <input required="required" id="edit<?php echo $editKey; ?>" name="<?php echo $editKey; ?>" type="text"
                                       value="<?php echo $editVal; ?>">
                                <span for="edit<?php echo $editKey; ?>"><?php echo $lang[$editKey]; ?></span>
                                </li>
                                <?php
                            }
                        }
                    }
                ?>
            </ul>
            <ul class="ios-list">
                <li>
                    <div class="delete-button-box">
                        <div class="delete-button js--delete-entry">
                            Löschen
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>

    <script>postLog.init()</script>
</div>


<li>
    <label for="edit<?php echo $editKey; ?>"><?php echo $editKey; ?></label>
    <input id="edit<?php echo $editKey; ?>" name="<?php echo $editKey; ?>" type="text"
           value="<?php echo $editVal; ?>">
</li>