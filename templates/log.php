<div id="ajax-header">
    Log
</div>
<div id="ajax-content">

    <?php
        $aTemplates0 = getTemplates(0);
        $aTemplates1 = getTemplates(1);
    ?>
    <form class="js--post-form" action="index.php?post" method="post">
        <input type="hidden" id="logImage" name="image" placeholder="image">
        <input type="hidden" id="logUser" name="user" value="<?php echo loggedUser()?>">
        <input type="hidden" id="logTime" name="time" value="<?php echo $dateStamp ?>">
        <input class="js--log-segmented-control" id="type-01" data-segmented-control="1" value="0" type="radio" checked name="type">
        <input class="js--log-segmented-control" id="type-02" data-segmented-control="2" value="1" type="radio" name="type">
        <div class="ios-segmented-controls">
            <label for="type-01"><?php lang('button-rem')?></label>
            <label for="type-02"><?php lang('button-add')?></label>
        </div>
        <div class="_scroll" data-segmented-parent>
            <ul class="ios-list">
                <li class="ios-padding ios-padding-bottom">
                    <div class="horizontal-radio _scroll-full" data-segmented-content="1">
                        <?php
                        foreach($aTemplates0 as $key => $template){
                            echo '<div class="radio">';
                            echo '<input id="name'.$key.'0"type="radio" class="js--template-name" name="name" data-image="'.$template['image'].'" value="'.$template['name'].'">';
                            echo '<label for="name'.$key.'0">';
                            echo '<div class="template-sprite template-sprite-circle template-sprite-'.$template['image'].'"></div>';
                            echo '<span>'.$template['name'].'</span>';
                            echo '</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="horizontal-radio _scroll-full" data-segmented-content="2">
                        <?php
                        foreach($aTemplates1 as $key => $template){
                            echo '<div class="radio">';
                            echo '<input id="name'.$key.'1"type="radio" class="js--template-name" name="name" data-image="'.$template['image'].'" value="'.$template['name'].'">';
                            echo '<label for="name'.$key.'1">';
                            echo '<div class="template-sprite template-sprite-circle template-sprite-'.$template['image'].'"></div>';
                            echo '<span>'.$template['name'].'</span>';
                            echo '</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </li>
            </ul>
            <ul class="ios-list">
                <li class="ios-text-input">
                    <input id="log-description" required="required" type="text" name="description">
                    <span>Beschreibung</span>
                </li>
                <li class="ios-text-input ios-text-input-brand">
                    <input id="log-sum" type="text" required="required" name="sum">
                    <span>Summe</span>
                </li>
            </ul>
        </div>
    </form>

    <script>postLog.init()</script>
</div>
