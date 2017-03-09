<?php
$aLogsData = getLogs($dateStamp);
$iBalance = $aLogsData['balance'];
$aLogs = $aLogsData['logs'];
?>

<div class="dashboard">
    <div class="ios-header ios-header-brand">
        <a href="./index.php?access" class="ion-btn pull-left js--ajax">
            <i class="ion ion-ios-person"></i>
        </a>
        <div class="ios-header-name">konomi</div>
        <a class="ion-btn pull-right js-open-ajaxmodal" href="index.php?template=log&logtype=0">
            <i class="ion ion-ios-plus-empty"></i>
        </a>
    </div>
    <div class="dashboard-display">
        <div class="dashboard-hero">
            <h1 class="dashboard-hero-balance">
                <?php echo ($iBalance < 0) ? '<span class="dashboard-hero-negative">-</span>':'';?>
                <span data-count="<?php echo abs($iBalance);?>" id="balance">0</span>
                <span class="dashboard-hero-currency">€</span>
            </h1>
            <div class="dashboard-hero-date">
                <a href="index.php?template=calender" class="js-open-ajaxmodal">
                    <span class="dashboard-hero-month">
                        <?php echo $lang['month'][intval($aDate[1])];?>
                    </span>
                    <span class="dashboard-hero-year">
                        <?php echo $aDate[0];?>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="dashboard-log _scroll">
        <?php
        $type = array(
            '1' => '<span style="color:green;">&uarr;</span>',
            '0' => '<span style="color:orangered;">&darr;</span>',
            '-1' => '<span style="color:darkgray;">&darr;</span>',
        );

        echo '<table style="width: 100%">';
        foreach($aLogs as $logEl){
            $logLink = 'index.php?template=edit';
            foreach($logEl as $logKey => $logVal){
                $logLink.='&'.$logKey.'='.$logVal;
            }
            echo '<tr>';
            echo '<td><div class="template-sprite template-sprite-circle template-sprite-'.$logEl['image'].'"></div></td>';
            echo '<td>';
            echo '<div style="font-weight: bold;">'.$logEl['name'].'</div>';
            echo '<small>'.$logEl['description'].'</small>';
            echo '</td>';
            echo '<td style="text-align: right; white-space: nowrap">'.$logEl['sum'].' €</td>';
            echo '<td>'.$type[$logEl['type']].'</td>';
            echo '<td><a href="'.$logLink.'" class="btn-edit js-open-ajaxmodal"><i class="dot"></i></a></td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
    </div>
</div>
