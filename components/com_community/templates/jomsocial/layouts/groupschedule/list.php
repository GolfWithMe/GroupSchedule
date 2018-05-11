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
?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <?php if($step=='rejected') { ?>
            <h3 class="joms-page__title"><?php echo 'Rejected Requests'; ?></h3>
            <!--<p><a href="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule'); ?>">Show New Request</a></p>-->
            <?php } else { ?>
            <h3 class="joms-page__title"><?php echo 'Incoming Requests'; ?></h3>
            <!--<p><a href="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=rejected'); ?>">Show Rejected Request</a></p>-->
            <?php } ?>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php //if ( $newrequest ) { 
	$nores=0;
	?>
    		<ul class="joms-list--friend">
            <?php foreach ( $newrequest as $row ) { ?>
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
                //if ($gsData->needCount > $gsModel->getGroupScheduleAcceptedCount($gsid)) {
			?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
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
                        <?php
                        $rcount = $gsModel->getGroupSchedule( $gsid );
                        $accda= $gsModel->getDateCloseCount($gsid,$rowdate->id);
                        //print_r($accda);
                        //print_r($rcount);
                        ?>
                        <label><?php echo $rowdate->sdate; ?> <br />
                        <?php //TODO: This should not be an equal (I think)
                        if ($rcount->responses == $accda->actmem) { ?>
                            <B>Full</b>
                        <?php } else { ?>
                        <input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />
                        <?php } ?>
                        </label>
                    </div>
                    <?php } ?>
                    <?php if($step=='rejected') { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancelrejected&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel Rejection";?>">
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">
                        </div>
                    </div>
                    <?php } ?>
            	</form>
        	</li>
            
            <?php } ?>
            
            <?php foreach ( $findnewrequest as $row ) { ?>
            <?php 
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
            	<form name="jsform-group-schedule" id="frmfindgolfers" action="<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
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
                        <input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />
                        </label>
                    </div>
                    <?php } ?>
                    <?php if($step=='rejected') { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner&stepset=cancelrejected&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel Rejection";?>">
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Not Available";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">
                        </div>
                    </div>
                    <?php } ?>
					
					
            	</form>
        	</li>
            
            <?php } ?>
            
            
            </ul>

    <?php //} else { ?>
    <?php if($nores==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no new requests.'; ?></div>
    <?php } ?>
    </div>

</div>
<script>
function show (elem) {  
    elem.style.display="block";
}
function hide (elem) { 
    elem.style.display="none"; 
}
</script>
<style>
.tooltip{
    position:absolute;
    margin:5px;
    width:200px;
    height:50px;
    border:1px solid black;
    /*display:none;*/
}
</style> 
<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Your Open Requests'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php //if ( $closerequest ) { 
	$nores=0;
	?>
    		<ul class="joms-list--friend">
            <?php foreach ( $closerequest as $row ) { ?>
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
            ?>
            <li class="joms-list__item">
            	<form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">In Progress</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span>You invited some friends to a new Golf With Me group event at <?php echo $course->Name; ?>. As you receive responses they will show in the blue boxes below.  Select a date/time when you are ready to schedule the tee time.</span>
                        <br /><br />
                    </div>
                    <?php
                    $accbutton=0;
                    $scheduleDate = $gsModel->getGroupScheduleDate($gsid);
                    foreach ( $scheduleDate as $rowdate ) {
                            //members request sent
                            //$smem= $gsModel->getGroupScheduleMemberCount($gsid);
                            //$accmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id,1);
                            //$acctotmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id);
                            $accda= $gsModel->getDateCloseCount($gsid,$rowdate->id);
                    ?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <!--<div style="background-color:#DFF5F7; margin:1px;" onmouseover="show(tooltip)" onmouseout="hide(tooltip)">-->
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>   
                        <div id="tooltip">
                        <?php 
                        $accMem = $gsModel->getGroupScheduleApproveUser($gsid,$rowdate->id);
                        foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                        ?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                        </div>
                        
                        <?php if($accda->actmem>0) { 
                        $accbutton=1;
                        ?>
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=closerequest&stepset=acceptcourse&req='.$gsid.'&sd='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Select";?>">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php //if($accbutton>0) { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancelclose&req='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel Request";?>">&nbsp;
                        <!--<input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
                    <?php //} ?>
            	</form>
        	</li>
            
            <?php } ?>
            
            <?php foreach ( $findcloserequest as $row ) { ?>
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
            	<form name="jsform-group-schedule" id="frmfindgolfers" action="<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">In Progress</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span>You Invited the public to a new Golf With Me group event at <?php echo $course->Name; ?>. You have received some responses. To close the request, select a date/time below.</span>
                        <br /><br />
                    </div>
                    <?php
                    $accbutton=0;
                    $scheduleDate = $gsModel->getFindGolfersDate($gsid);
                    foreach ( $scheduleDate as $rowdate ) {
                            //members request sent
                            //$smem= $gsModel->getfindgolfersMemberCount($gsid);
                            //$accmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id,1);
                            //$acctotmem= $gsModel->getfindgolfersAcceptCount($gsid,$rowdate->id);
                            $accda= $gsModel->getDateCloseCount($gsid,$rowdate->id);
                    ?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <div id="tooltip">
                        <?php 
                            $accMem = $gsModel->getFindGolfersApproveUser($gsid,$rowdate->id);
                            foreach ( $accMem as $damem ) {
                                    $usrda = CFactory::getUser($damem->userid);
                        ?>
                        <?php echo $usrda->name; ?>,
                        <?php } ?>
                        </div>
                        <?php if($accda->actmem>0) { 
                        $accbutton=1;
                        ?>
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=closerequest&stepset=acceptcourse&req='.$gsid.'&sd='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Select";?>">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php if($accbutton>0) { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=findgolfers&task=viewpartner&stepset=cancelclose&req='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel All Request";?>">&nbsp;
                        <!--<input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
                    <?php } ?>
            	</form>
        	</li>
            
            <?php } ?>
            
            
            </ul>

    <?php //} else { ?>
    <?php if($nores==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no open requests to close.'; ?></div>
    <?php } ?>
    </div>
</div>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Upcoming Events'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>
    <?php $error=0; ?>

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
                    	<span>You invited some friends to a Golf With Me group event at <?php echo $course->Name; ?>. The below date and time are your reserved Tee-Time and a list of friends that are participating.</span>
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
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:100%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label>Tee Time: <?php echo $gsData->teetime; ?> </label>
                        </div>
                        <?php 
                            $accMem = $gsModel->getGroupScheduleAcceptMember($gsid,$rowdate->id);
                            $beenhere=false;
                            foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                                if ($beenhere == true)
                                    echo ", ";
                                
                                echo $usrda->name; 
                                
                                $beenhere=true;
                            } ?>
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
                    	<span>You invited the public to Golf With you at <?php echo $course->Name; ?>. The below date and time are your reserved Tee-Time and a list of people that are participating.</span>
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
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:100%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label>Tee Time: <?php echo $gsData->teetime; ?> </label>
                        </div>
                        <?php 
                            $accMem = $gsModel->getFindGolfersAcceptMember($gsid,$rowdate->id);
                            foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                                
                                if ($beenhere == true)
                                    echo ", ";
                                
                                echo $usrda->name; 
                                
                                $beenhere=true;
                            } ?>
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
                    	<span class="joms-ribbon">Confirmed</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a Golf With Me group event at <?php echo $course->Name; ?>. The date and time below are the confirmed Tee-Time.  There is also a list of participants in the event below.</span>
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
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:100%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $gsData->teetime; ?> </label>
                        </div>
                        <?php 
                            $accMem = $gsModel->getGroupScheduleAcceptMember($gsid,$rowdate->id);
                            foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                                if ($beenhere == true)
                                    echo ", ";
                                
                                echo $usrda->name; 
                                
                                $beenhere=true; 
                                
                            } ?>
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
                    	<span class="joms-ribbon">Confirmed</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you via a Find Golfers request to play golf at <?php echo $course->Name; ?>. The date and time below are the confirmed Tee-Time.  There is also a list of participants in the event below.</span>
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
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:100%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $gsData->teetime; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php 
                            $accMem = $gsModel->getFindGolfersAcceptMember($gsid,$rowdate->id);
                            foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                                if ($beenhere == true)
                                    echo ", ";
                                
                                echo $usrda->name; 
                                
                                $beenhere=true; 
                                
                            } ?>
                    </div>
                    	<?php } ?>
                    <?php } ?>
                    
                    
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } ?>
    
    <?php if($error==0) { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no upcoming events.'; ?></div>
    <?php } ?>
    </div>
</div>

<?php /* ?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Golf-With-Me Request List'; ?></h3>
        </div>
    </div>

    <?php echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php if ( $rows ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $rows as $row ) { ?>
            <?php 
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
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Sent Request</span>
                    </div>
                    <div class="joms-list__avatar" style="width:100%; padding-left:37px;">
                    	<span><?php echo $usr->name; ?>, Invited you to a new Golf With Me group event at <?php echo $course->Name; ?>. Select the dates you are available below</span>
                        <br /><br />
                    </div>
                    <?php
					$scheduleDate = $gsModel->getGroupScheduleDate($gsid);
					foreach ( $scheduleDate as $rowdate ) {
						//members request sent
						//$smem=$gsModel->getGroupScheduleMemberCount($gsid);
						//$cancelmem=$gsModel->getGroupScheduleMemberCount($gsid,2);
						//$accmem= $gsModel->getGroupScheduleMemberCount($gsid,1);
						$acctotmem=$gsModel->getDateListCount($gsid,$rowdate->id);
						//$resmem=$accmem+$cancelmem;
						//echo $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id);
						//$restotmem=$acctotmem+$cancelmem;
						
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px; background-color:#DFF5F7;">
                        <label><?php echo $rowdate->sdate; ?> </label><br /><?php echo $acctotmem->completemem; ?>/<?php echo $acctotmem->totalmem; ?>
                    </div>
                    <?php } ?>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } else { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no request.'; ?></div>
    <?php } ?>
    </div>
    <?php if ( !empty($pagination) && ($pagination->pagesTotal > 1 || $pagination->total > 1) ) { ?>
    <div class="joms-pagination">
        <?php echo $pagination->getPagesLinks(); ?>
    </div>
    <?php } ?>
</div>

<?php */ ?>
