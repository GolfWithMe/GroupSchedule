<?php
//GWMFile
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
//echo $step;
?>
<script>
function divon(divid) {
	//alert("ok");
	document.getElementById(divid).style.display="block";
}
function divoff(divid) {
	//alert("ok");
	document.getElementById(divid).style.display="none";
}
</script>
<?php if($step=="created") { ?>
<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            
            <h3 class="joms-page__title"><?php echo 'Request Created'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php //if ( $newrequest ) { 
	$nores=0;
	?>
    		<ul class="joms-list--friend">
            <?php foreach ( $rowsgroup as $row ) { ?>
            <?php 
                $nores++;
                $gsid=$row->id;
                //group schedule
                $gsModel = CFactory::getModel('groupschedule');
                $gsData = $gsModel->getGroupSchedule($gsid);
                //user request
                $usr = CFactory::getUser($gsData->uid);
                //print_r($usr);
                //course
                $course = $gsModel->getCourseDetails($gsData->selcourse);

                //echo $gsData->status; //2 for expire
			?>
            <li class="joms-list__item">
            	
					<div class="joms-ribbon__wrapper"> 
                    	<span class="joms-ribbon">New Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$scheduleDate = $gsModel->getGroupScheduleDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px; background-color:#DFF5F7;">
                        <label><?php echo $rowdate->sdate; ?> <br />
                        <!--<input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />-->
                        </label>
                        <?php if($gsData->status<>2) { ?>
                        <br /><input type="button" class="joms-button--neutral joms-button--full-small joms-button--smallest" onclick="divon('datechange<?php echo $rowdate->id; ?>')" value="<?php echo "Edit";?>"> &nbsp; <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=viewall&stepset=created&actstep=deldate&did='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Delete";?>">
                    
                        <div id="datechange<?php echo $rowdate->id; ?>" style="display:none;">
                        <form name="jsform-group-schedule<?php echo $rowdate->id; ?>" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
                        <input type="text" name="sdate" value="<?php echo $rowdate->sdate; ?>" class="joms-text"  />
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Edit";?>">
                        <input type="button" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Close";?>" onclick="divoff('datechange<?php echo $rowdate->id; ?>')">
                        <input type="hidden" name="did" value="<?php echo $rowdate->id; ?>" />
                        <input type="hidden" name="gsid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="created" />
                        <input type="hidden" name="actstep" value="editdate" />
                        <input type="hidden" name="task" value="viewall" />
                        </form>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        
                        <!--<input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
        	</li>
            
            <?php } ?>
            
            <?php foreach ( $rowsgolfers as $row ) { ?>
            <?php 
				$nores++;
				$gsid=$row->id;
				//group schedule
				$gsModel = CFactory::getModel('findgolfers');
                $gsData = $gsModel->getFindGolfers($gsid);
				//user request
				$usr = CFactory::getUser($gsData->uid);
				//print_r($usr);
				//course
				$course = $gsModel->getCourseDetails($gsData->selcourse);
			?>
            <li class="joms-list__item">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">New Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$scheduleDate = $gsModel->getFindGolfersDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px; background-color:#DFF5F7;">
                        <label><?php echo $rowdate->sdate; ?> <br />
                        <!--<input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />-->
                        </label>
                        <?php if($gsData->status<>2) { ?>
                        <br /><input type="button" class="joms-button--neutral joms-button--full-small joms-button--smallest" onclick="divon('datechange<?php echo $rowdate->id; ?>')" value="<?php echo "Edit";?>"> &nbsp; <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=viewall&stepset=created&actstep=deldateg&did='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Delete";?>">
                    
                        <div id="datechange<?php echo $rowdate->id; ?>" style="display:none;">
                        <form name="jsform-group-schedule<?php echo $rowdate->id; ?>" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
                        <input type="text" name="sdate" value="<?php echo $rowdate->sdate; ?>" class="joms-text"  />
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Edit";?>">
                        <input type="button" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Close";?>" onclick="divoff('datechange<?php echo $rowdate->id; ?>')">
                        <input type="hidden" name="did" value="<?php echo $rowdate->id; ?>" />
                        <input type="hidden" name="gsid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="created" />
                        <input type="hidden" name="actstep" value="editdateg" />
                        <input type="hidden" name="task" value="viewall" />
                        </form>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <!--<input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
        	</li>
            
            <?php } ?>
            
            
            </ul>

    <?php //} else { ?>
    <?php if($nores==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no requests.'; ?></div>
    <?php } ?>
    </div>

</div>

<?php } else if($step=="response") { ?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            
            <h3 class="joms-page__title"><?php echo 'Response Request'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php //if ( $newrequest ) { 
	$nores=0;
	?>
    		<ul class="joms-list--friend">
            <?php foreach ( $rowsgroup as $row ) { ?>
            <?php 
                $nores++;
                $gsid=$row->gsid;
                //group schedule
                $gsModel = CFactory::getModel('groupschedule');
                $gsData = $gsModel->getGroupSchedule($gsid);
                //user request
                $usr = CFactory::getUser($gsData->uid);
                //print_r($usr);
                //course
                $course = $gsModel->getCourseDetails($gsData->selcourse);

                //echo $gsData->status; //2 for expire
            ?>
            <li class="joms-list__item">
            	
                <div class="joms-ribbon__wrapper">
                    <span class="joms-ribbon">New Request</span>
                </div>
                <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    <span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                    <br /><br />
                </div>
                <?php
                    $scheduleDate = $gsModel->getGroupScheduleDate($gsid);
                    foreach ( $scheduleDate as $rowdate ) { ?>
                        <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px; background-color:#DFF5F7;">
                            <label><?php echo $rowdate->sdate; ?> <br />
                            <!--<input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />-->
                            </label>
                            <?php if($row->sdate==$rowdate->id) { ?>
                                <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=viewall&stepset=response&actstep=canreq&gsid='.$gsid.'&sid='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel";?>">
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">

                        
                        <!--<input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
        	</li>
            
            <?php } ?>
            
            <?php foreach ( $rowsgolfers as $row ) { 
                $nores++;
                $gsid=$row->gsid;
                //group schedule
                $gsModel = CFactory::getModel('findgolfers');
                $gsData = $gsModel->getFindGolfers($gsid);
                //user request
                $usr = CFactory::getUser($gsData->uid);
                //print_r($usr);
                //course
                $course = $gsModel->getCourseDetails($gsData->selcourse);
            ?>
            <li class="joms-list__item">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">New Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$scheduleDate = $gsModel->getFindGolfersDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px; background-color:#DFF5F7;">
                        <label><?php echo $rowdate->sdate; ?> <br />
                        <!--<input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />-->
                        </label> 
                        <?php if($row->sdate==$rowdate->id) { ?>
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=viewall&stepset=response&actstep=canreqg&gsid='.$gsid.'&sid='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel";?>">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <!--<input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
        	</li>
            
            <?php } ?>
            
            
            </ul>

    <?php //} else { ?>
    <?php if($nores==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no requests.'; ?></div>
    <?php } ?>
    </div>

</div>

<?php } else if($step=="pastevents") { ?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Past Events'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>
   	
    <div class="joms-tab__content">
    
    <?php if ( $requstorupcomming ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $requstorupcomming as $row ) { ?>
            <?php 
				$error=1;
				$gsid=$row->id;
				//group schedule
				$gsModel = CFactory::getModel('groupschedule');
                                $gsData = $gsModel->getGroupSchedule($gsid);
				//user request
				$usr = CFactory::getUser($gsData->uid);
				//print_r($usr);
				//course
				$course = $gsModel->getCourseDetails($gsData->selcourse);
			?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=recresponse') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Requestor</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
                    $accbutton=0;
                    $scheduleDate = $gsModel->getGroupScheduleDate($gsid);
                    foreach ( $scheduleDate as $rowdate ) {
                        //members request sent
                        //$smem= $gsModel->getGroupScheduleMemberCount($gsid);
                        //$accmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id,2);
                        //$acctotmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id);
                        $accda= $gsModel->getDateAcceptCount($gsid,$rowdate->id);

                        if($accda->actmem>0) { 
                    ?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php 
                            $accMem = $gsModel->getGroupScheduleAcceptMember($gsid,$rowdate->id);
                            foreach ( $accMem as $damem ) {
                                    $usrda = CFactory::getUser($damem->userid);
						?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                    </div>
                    	<?php } ?>
                    <?php } ?>
                    
                    
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } ?>
    
    <?php if ( $findrequstorupcomming ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $findrequstorupcomming as $row ) { ?>
            <?php 
				$error=1;
				$gsid=$row->id;
				//group schedule
				$gsModel = CFactory::getModel('findgolfers');
                $gsData = $gsModel->getFindGolfers($gsid);
				//user request
				$usr = CFactory::getUser($gsData->uid);
				//print_r($usr);
				//course
				$course = $gsModel->getCourseDetails($gsData->selcourse);
			?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmfindgolfers" action="<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=recresponse') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Requestor</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$accbutton=0;
					$scheduleDate = $gsModel->getFindGolfersDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
						//members request sent
						//$smem= $gsModel->getfindgolfersMemberCount($gsid);
						//$accmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id,2);
						//$acctotmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id);
						$accda= $gsModel->getDateAcceptCount($gsid,$rowdate->id);
						
						if($accda->actmem>0) { 
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php 
							$accMem = $gsModel->getFindGolfersAcceptMember($gsid,$rowdate->id);
							foreach ( $accMem as $damem ) {
								$usrda = CFactory::getUser($damem->userid);
						?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                    </div>
                    	<?php } ?>
                    <?php } ?>
                    
                    
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } ?>

    <?php 
	//user accept
	//print_r($userrows);
	if ( $userupcomming ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $userupcomming as $row ) { ?>
            <?php 
				$error=1;
				$gsid=$row->id;
				//group schedule
				$gsModel = CFactory::getModel('groupschedule');
                $gsData = $gsModel->getGroupSchedule($gsid);
				//user request
				$usr = CFactory::getUser($gsData->uid);
				//print_r($usr);
				//course
				$course = $gsModel->getCourseDetails($gsData->selcourse);
			?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=recresponse') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$accbutton=0;
					$scheduleDate = $gsModel->getGroupScheduleDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
						//members request sent
						//$smem= $gsModel->getGroupScheduleMemberCount($gsid);
						//$accmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id,2);
						//$acctotmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id);
						$accda= $gsModel->getDateAcceptCount($gsid,$rowdate->id);
						
						if($accda->actmem>0) { 
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php 
							$accMem = $gsModel->getGroupScheduleAcceptMember($gsid,$rowdate->id);
							foreach ( $accMem as $damem ) {
								$usrda = CFactory::getUser($damem->userid);
						?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                    </div>
                    	<?php } ?>
                    <?php } ?>
                    
                    
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } ?>
    
    <?php 
	//user accept
	//print_r($finduserupcomming);
	if ( $finduserupcomming ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $finduserupcomming as $row ) { ?>
            <?php 
				$error=1;
				$gsid=$row->id;
				//group schedule
				$gsModel = CFactory::getModel('findgolfers');
                $gsData = $gsModel->getFindGolfers($gsid);
				//user request
				$usr = CFactory::getUser($gsData->uid);
				//print_r($usr);
				//course
				$course = $gsModel->getCourseDetails($gsData->selcourse);
			?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmfindgolfers" action="<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=recresponse') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$accbutton=0;
					$scheduleDate = $gsModel->getFindGolfersDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
						//members request sent
						//$smem= $gsModel->getfindgolfersMemberCount($gsid);
						//$accmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id,2);
						//$acctotmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id);
						$accda= $gsModel->getDateAcceptCount($gsid,$rowdate->id);
						
						if($accda->actmem>0) { 
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php 
							$accMem = $gsModel->getFindGolfersAcceptMember($gsid,$rowdate->id);
							foreach ( $accMem as $damem ) {
								$usrda = CFactory::getUser($damem->userid);
						?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                    </div>
                    	<?php } ?>
                    <?php } ?>
                    
                    
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } ?>
    
    <?php if($error==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no past events.'; ?></div>
    <?php } ?>
    </div>

</div>
<?php } ?>

