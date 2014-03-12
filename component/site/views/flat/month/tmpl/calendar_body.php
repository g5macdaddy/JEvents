<?php 
defined('_JEXEC') or die('Restricted access');

$cfg	 = JEVConfig::getInstance();

if ($cfg->get("tooltiptype",'overlib')=='overlib'){
	JEVHelper::loadOverlib();
}

$view =  $this->getViewName();
echo $this->loadTemplate('cell' );
$eventCellClass = "EventCalendarCell_".$view;
    ?>
            <table border="0" cellpadding="0" class="cal_top_day_names">
            <tr valign="top">
                <?php 
                foreach ($this->data["daynames"] as $dayname) { 
					$cleaned_day = strip_tags($dayname, '');?>
					<td class="cal_daysnames">
						<span class="<?php echo strtolower($cleaned_day); ?>">
							<?php echo extension_loaded('mbstring') ? mb_substr($cleaned_day, 0, 3) : substr($cleaned_day, 0, 3);?>
						</span>
					</td>
                    <?php
                } ?>
            </tr>
            </table>
        <table border="0" cellspacing="1" cellpadding="0" class="cal_table">
            <?php
            $datacount = count($this->data["dates"]);
            $dn=0;
            for ($w=0;$w<6 && $dn<$datacount;$w++){
            ?>
			<tr class="cal_cell_rows">
                <?php
                for ($d=0;$d<7 && $dn<$datacount;$d++){
                	$currentDay = $this->data["dates"][$dn];
                	switch ($currentDay["monthType"]){
                		case "prior":
                		case "following":
                		?>
                    <td width="14%" class="cal_daysoutofmonth" valign="top">
                        <?php echo $currentDay["d"]; ?>
                    </td>
                    	<?php
                    	break;
                		case "current":
                			$cellclass = $currentDay["today"]?'class="cal_today"':(count($currentDay["events"])>0?'class="cal_dayshasevents"':'class="cal_daysnoevents"');
						?>
                    <td <?php echo $cellclass;?>>
                     <?php   $this->_datecellAddEvent($this->year, $this->month, $currentDay["d"]);?>
                    	<a class="cal_daylink" href="<?php echo $currentDay["link"]; ?>" title="<?php echo JText::_('JEV_CLICK_TOSWITCH_DAY'); ?>"><?php echo $currentDay['d']; ?></a>
                        <?php

                        if (count($currentDay["events"])>0){
                        	foreach ($currentDay["events"] as $key=>$val){
                        		if( $currentDay['countDisplay'] < $cfg->get('com_calMaxDisplay',5)) {
                        			echo '<div class="event_div_1">';
                        		} else {
                        			// float small icons left
                        			echo '<div class="event_div_2">';
                        		}
                        		echo "\n";
                        		$ecc = new $eventCellClass($val,$this->datamodel, $this);
                        		echo $ecc->calendarCell($currentDay,$this->year,$this->month,$key);
                        		echo '</div>' . "\n";
                        		$currentDay['countDisplay']++;
                        	}
                        }
                        echo "</td>\n";
                        break;
                	}
                	$dn++;
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
            $this->eventsLegend();

