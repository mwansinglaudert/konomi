<div id="ajax-header">
    Monat
</div>
<div id="ajax-content">
    <?php
    $aYears = getMonths();
    arsort($aYears);
    echo '<div class="calender _scroll">';
    foreach($aYears as $year => $aMonths){
        echo '<h2 class="modal-headline">'.$year.'</h2>';
        arsort($aMonths);

        echo '<ul class="ios-list">';
        foreach($aMonths as $month){
            $url = 'index.php?time='.$year.'-'.$month;
            echo '<li class="ios-text-link">';
            echo '<a class="js-calender-link" href="'.$url.'">'.$lang['month'][intval($month)].'</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    echo '</div>';
    ?>
    <script>
        calender.init();
    </script>
</div>
