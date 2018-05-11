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
            <h3 class="joms-page__title"><?php echo 'Request Final Selections'; ?></h3>
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
                if($gsid==$fieldset['req']) {
                //group schedule
                $gsModel = CFactory::getModel('groupschedule');
                $gsData = $gsModel->getGroupSchedule($gsid);
                //user request
                $usr = CFactory::getUser($gsData->uid);
                //print_r($usr);
                //course
                $course = $gsModel->getCourseDetails($gsData->selcourse);
                //print_r($course);
            ?>
            <li class="joms-list__item">
				<?php if($fieldset['step']=='acceptcourse') { ?>
                    <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=closerequest') ?>" method="POST" class="js-form">
                    <div class="joms-list__avatar" style="width:100%;">
                    	<span><b>Complete Request - Select Golfers</b></span>
                        <br /><br />
                    </div>
                    <div class="joms-form__group has-privacy" for="field2">
                    <?php 
                        $accMem = $gsModel->getGroupScheduleApproveUser($gsid,$fieldset['sd']);
                        foreach ( $accMem as $damem ) {
                                $usrda = CFactory::getUser($damem->userid);
                    ?>
                    <label><input type="checkbox" name="member[]" value="<?php echo $damem->userid; ?>" /> <?php echo $usrda->name; ?></label> <br/>
                    <?php } ?>
                    
                </div>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="req" value="<?php echo $fieldset['req']; ?>" />
                        <input type="hidden" name="sd" value="<?php echo $fieldset['sd']; ?>" />
                        <?php $sdtime = $gsModel->getsdateRealTime($fieldset['sd']); ?>
                        <input type="hidden" name="chosentime" value="<?php echo $sdtime; ?>" />
                        <input type="hidden" name="stepset" value="acceptteetime" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule'); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                        <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                        </div>
                    </div>
                    </form>
            <?php } else if($fieldset['step']=='acceptteetime') { ?>
                    <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form">
                    <div class="joms-list__avatar" style="width:100%;">
                    	<span><?php echo $course->Name; ?></span>
                        <br /><br />
                    </div>
                    <div class="joms-form__group">
                    	<div style="background-color:#ffffff; color:#000; margin:3px;">
                        Main: <?php echo $course->Phone; ?> <br />
                        Booking: <?php echo $course->Phone; ?> <br />
                        Web Site: <a href='<?php echo $course->URL; ?>'><?php echo $course->URL; ?></a> <br /><br />
                        Selected Time: <?php echo $_REQUEST['chosentime']; ?> <br /><br />
                        <a href="<?php echo $course->BURL; ?>" target="_blank"><b>Book a Tee Time</b></a>
                        <?php if ($course->ownerid == 0) { ?>
                        <br />This course is not a member of Golf With Me.  When you call to book your tee time please suggest they Join!
                        <?php } ?>
                        </div>
                    </div>
                    <div class="joms-form__group has-privacy" for="field2">
                        <span>Booked Tee Time:  </span>
                        
                        <input type="text" name="teetime" class="joms-text"  />
                    </div>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="req" value="<?php echo $fieldset['req']; ?>" />
                        <input type="hidden" name="sd" value="<?php echo $fieldset['sd']; ?>" />
                        <input type="hidden" name="chosentime" value="<?php echo $_REQUEST['chosentime']; ?>" />
                        <?php foreach($fieldset['member'] as $rowmem) : ?>
                            <input type="hidden" name="member[]" value="<?php echo $rowmem; ?>" />
                        <?php endforeach; ?>
                        <input type="hidden" name="stepset" value="accept" />
                        <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Finish";?>">
                        </div>
                    </div>
                    </form>
             <?php } else { ?>
                    <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule') ?>" method="POST" class="js-form">
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
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=closerequest&stepset=accept&req='.$gsid.'&sd='.$rowdate->id); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Select";?>">
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php if($accbutton>0) { ?>
                    <div class="joms-list__actions">
                        <div class="joms-list__button--dropdown">
                        <input type="hidden" name="reqid" value="<?php echo $gsid; ?>" />
                        <input type="hidden" name="stepset" value="submitresponse" />
                        <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=closerequest&stepset=cancel&req='.$gsid); ?>';" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Cancel All Request";?>">&nbsp;
                        <!--<input type="submit" class="joms-button--primary joms-button--full-small joms-button--smallest" value="<?php echo "Save";?>">-->
                        </div>
                    </div>
                    <?php } ?>
                    <?php } //if?>
            	</form>
        	</li>
            
            <?php } } ?>
            </ul>

    <?php } else { ?>
        <div class="cEmpty cAlert"><?php echo 'You have no pending response.'; ?></div>
    <?php } ?>
    </div>
</div>





