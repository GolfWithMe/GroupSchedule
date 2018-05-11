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
$error=0;
?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo 'Accept Request List'; ?></h3>
        </div>
    </div>

    <?php echo $submenu;?>

    <div class="joms-gap"></div>

    <div class="joms-tab__content">
    
    <?php if ( $rows ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $rows as $row ) { ?>
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
	if ( $userrows ) { ?>
    		<ul class="joms-list--friend">
            <?php foreach ( $userrows as $row ) { ?>
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
        <div class="cEmpty cAlert"><?php echo 'You have no pending response.'; ?></div>
    <?php } ?>
    </div>
    <?php if ( !empty($pagination) && ($pagination->pagesTotal > 1 || $pagination->total > 1) ) { ?>
    <div class="joms-pagination">
        <?php echo $pagination->getPagesLinks(); ?>
    </div>
    <?php } ?>
</div>





