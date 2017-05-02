<?php
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
            <h3 class="joms-page__title"><?php echo 'New Requests'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php if ( $newrequest ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $newrequest as $row ) { ?>
            <?php 
				$gsid=$row->gsid;
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
                        <input type="checkbox" name="seldate[]" value="<?php echo $rowdate->id; ?>" id="typesea"  />
                        </label>
                    </div>
                    <?php } ?>
                    
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancel&reqid='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">
                        </div>
                    </div>
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } else { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no new requests.'; ?></div>
    <?php } ?>
    </div>

</div>


<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Requestor close requests'; ?></h3>
        </div>
    </div>

    <?php //echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    	

    <?php if ( $closerequest ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $closerequest as $row ) { ?>
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
            	<form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
					<div class="joms-ribbon__wrapper">
                    	<span class="joms-ribbon">Close Request</span>
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
						//$accmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id,1);
						//$acctotmem= $gsModel->getGroupScheduleAcceptCount($gsid,$rowdate->id);
						$accda= $gsModel->getDateCloseCount($gsid,$rowdate->id);
					?>
                    <div class="joms-list" style="font-size:11px; font-style:normal; width:32%; float:left; text-align:center; margin:1px;">
                        <div style="background-color:#DFF5F7; margin:1px;">
                        <label><?php echo $rowdate->sdate; ?> <br /><?php echo $accda->completemem; ?>/<?php echo $accda->totalmem; ?></label>
                        </div>
                        <?php if($accda->actmem>0) { 
						$accbutton=1;
						?>
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=accept&req='.$gsid.'&sd='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Select";?>">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php if($accbutton>0) { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&stepset=cancelclose&req='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel All Request";?>">&nbsp;
                        <!--<input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
                    <?php } ?>
            	</form>
        	</li>
            
            <?php } ?>
            </ul>

    <?php } else { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no close requests.'; ?></div>
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