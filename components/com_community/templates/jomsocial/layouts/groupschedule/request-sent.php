<?php
error_reporting(1);
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
            <h3 class="joms-page__title"><?php echo 'New Golf-With-Me Request'; ?></h3>
        </div>
    </div>
    <?php echo $submenu;?>

    <div class="joms-gap"></div>
	<?php //print_r($rows);?>
    
    <?php if($step=="members") { ?>
    	<div id="joms-profile--information" class="joms-tab__content">
            <?php if ( $rows ) { ?>
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form">
                <legend class="joms-form__legend">New Request</legend>
                <div class="joms-form__group has-privacy" for="field2">
                    <span title="Select Group">Select Members</span>
                    <select class="joms-select" name="selmember[]" id="selmember" size="10" multiple="multiple" data-required="true" required  >
                    <?php foreach($rows as $row) : ?>
                        <option value="<?php echo $row->id; ?>" style="color:#666;" ><?php echo $row->name; ?></option>
                    <?php endforeach; ?>
                    </select> 
                    <br />
                    Hold CTRL key to select multiple golfers.
                    <br />
                    Select : <a href="javascript:void(0);" onclick="selectall();">All</a> , <a href="javascript:void(0);" onclick="selectall('None');">None</a> 
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input type="hidden" name="selgroup" value="<?php echo $fieldset['selgroup']; ?>" />
                    <input type="hidden" name="stepset" value="course" />
                    <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest&selgroup='.$fieldset['selgroup']); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                </div>
            </form>
        
        <?php } else { ?>
            <div class="cEmpty cAlert"><?php echo 'You have no members.'; ?></div>
        <?php } ?>
        </div>
    <?php } else if($step=="course") { ?>
    	<div id="joms-profile--information" class="joms-tab__content">
            <?php //if ( $rows ) { ?>
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
                <legend class="joms-form__legend">New Request</legend>
                <div class="joms-form__group has-privacy" for="field2">
                    <span title="Select Group">Select Course</span>
                    <label><input type="radio" name="coursetype" value="Favorites"  id="typefav" /> Favorites:</label>
                    <select class="joms-select" name="selfavorite" id="selfavorite" >
                    <option value="" >Select Course</option>
                    <?php foreach($groupsFav as $row) : ?>
                        <option value="<?php echo $row->uid; ?>" ><?php echo $row->Name; ?></option>
                    <?php endforeach; ?>
                    </select> 
                </div>
                <div class="joms-form__group has-privacy" for="field2">
                    <span></span>
                    <label><input type="radio" name="coursetype" value="Local" id="typeloc" /> Local:</label>
                    <select class="joms-select" name="sellocal" id="sellocal" >
                    <option value="" >Select Course</option>
                    <?php foreach($groupsLoc as $row) : ?>
                        <option value="<?php echo $row->uid; ?>" ><?php echo $row->Name; ?></option>
                    <?php endforeach; ?>
                    </select> 
                </div>
                <div class="joms-form__group has-privacy" for="field2">
                    <span></span>
                    <label><input type="radio" name="coursetype" value="Search" id="typesea"  /> Search:</label>
                    <input type="text" name="searchkey" id="searchkey" class="joms-text" value="<?php echo $fieldset['searchkey']; ?>" />
                    <br />
                    <span><input type="submit" class="joms-button--neutral joms-button--full-small joms-button--smallest" value="<?php echo "Search";?>"></span>
                    <?php if ( $fieldset['searchkey']!='' ) { ?>
                    <select class="joms-select" name="selsearch" id="selsearch">
                    <option value="" >Select Course</option>
                    <?php foreach($rows as $row) : ?>
                        <option value="<?php echo $row->uid; ?>" ><?php echo $row->Name; ?></option>
                    <?php endforeach; ?>
                    </select> 
                    <?php } else { ?>
                    <input type="hidden" name="selsearch" id="selsearch" value="No" />
                    <?php } ?>
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input type="hidden" name="selgroup" value="<?php echo $fieldset['selgroup']; ?>" />
                    <?php foreach($fieldset['selmember'] as $rowmem) : ?>
                        <input type="hidden" name="selmember[]" value="<?php echo $rowmem; ?>" />
                    <?php endforeach; ?>
                    <input type="hidden" name="selcourse" id="selcourse" value="" />
                    <input type="hidden" name="stepset" value="options" />
                    <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest&stepset=members&selgroup='.$fieldset['selgroup']); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                </div>
            </form>
        
        <?php //} else { ?>
            <!--<div class="cEmpty cAlert"><?php echo 'You have no course.'; ?></div>-->
        <?php //} ?>
        </div>
     <?php } else if($step=="options") { ?>
    	<div id="joms-profile--information" class="joms-tab__content">
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
                <legend class="joms-form__legend">New Request - Select Options</legend>
                <!--<div class="joms-form__group has-privacy" for="field2">
                    <span>Max Picks : </span>
                    <input type="text" name="picks" class="joms-text"  />
                    <span>blank = no limit</span>
                </div>
                <div class="joms-form__group has-privacy" for="field2">
                    <span>Max Responses : </span>
                    <input type="text" name="responses" class="joms-text" />
                    <span>blank = no max</span>
                </div>-->
                <div class="joms-form__group has-privacy" for="field2">
                    <span></span>
                    <label><input type="checkbox" name="notify" value="notify" /> Notify On Each Response</label>
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input type="hidden" name="selgroup" value="<?php echo $fieldset['selgroup']; ?>" />
                    <?php foreach($fieldset['selmember'] as $rowmem) : ?>
                        <input type="hidden" name="selmember[]" value="<?php echo $rowmem; ?>" />
                    <?php endforeach; ?>
                    <input type="hidden" name="coursetype" value="<?php echo $fieldset['coursetype']; ?>" />
                    <input type="hidden" name="selcourse" value="<?php echo $fieldset['selcourse']; ?>" />
                    <input type="hidden" name="stepset" value="calender" />
                    <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest&stepset=course&selgroup='.$fieldset['selgroup']); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                </div>
            </form>
        </div>
    <?php } else if($step=="calender") { ?>
  <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/components/com_community/templates/jomsocial/assets/css/jquery-ui.css">
  <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/components/com_community/templates/jomsocial/assets/css/jquery-ui-timepicker-addon.css">
  <script src="<?php echo JURI::root(true); ?>/components/com_community/templates/jomsocial/assets/js/jquery-1.12.4.js"></script>
  <script src="<?php echo JURI::root(true); ?>/components/com_community/templates/jomsocial/assets/js/jquery-ui.js"></script>
  <script src="<?php echo JURI::root(true); ?>/components/com_community/templates/jomsocial/assets/js/jquery-ui-timepicker-addon.js"></script>
  <script>
  $( function() {
    //$( "#datepicker0" ).datetimepicker({showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, dateFormat: 'mm/dd/yy', timeInput: true, timeFormat: "hh:mm tt" });
	//$( "#datepicker0" ).datepicker( "option", "dateFormat", "mm/dd/yy" );
  } );
  
  function addcalender() {
	//alert('ok');
	var countrow= eval(document.getElementById('countf').value);
		
	$("#datepicker"+countrow ).datetimepicker({showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, dateFormat: 'mm/dd/yy', timeInput: true, stepMinute:15, hourMin:6, hourMax:20, controlType: 'select', oneLine: true, timeFormat: "hh:mm tt" });
	$('#datepicker'+countrow).datetimepicker('show');
	
	newrow=eval(countrow+1);
	$("#datepicker"+newrow ).datetimepicker({showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, dateFormat: 'mm/dd/yy', timeInput: true, stepMinute:15, hourMin:6, hourMax:20, controlType: 'select', oneLine: true, timeFormat: "hh:mm tt" });
	$('#datepicker'+newrow).datetimepicker('show');
	$("#container").append('<div class="joms-form__group has-privacy" for="field2" id="datediv'+newrow+'"><span><input type="button" onclick="removecal('+newrow+');" class="joms-button--primary joms-button--full-small" value="<?php echo "Remove";?>"></span><input type="text" name="datepicker[]" id="datepicker'+newrow+'" readonly="readonly"></div>');
	//$("#container").append('<div class="joms-form__group has-privacy" for="field2" id="datediv'+newrow+'"><span><input type="button" onclick="removecal('+newrow+');" class="joms-button--primary joms-button--full-small" value="<?php //echo "Remove";?>"></span><input type="text" name="datepicker[]"></div>');
	
	document.getElementById("countf").value=newrow;
	 
  }
  
  function removecal(removeid) {
	  var countrow= eval(document.getElementById('countf').value);
	  //newrow=eval(countrow-1);
	  $('#datediv'+removeid).remove();
	  //document.getElementById("countf").value=newrow;
  }
	
	$("#datepicker0").datetimepicker({showButtonPanel: true, showOtherMonths: true, selectOtherMonths: true, dateFormat: 'mm/dd/yy', timeInput: true, stepMinute:15, hourMin:6, hourMax:20,controlType: 'select', oneLine: true, timeFormat: "hh:mm tt" });
	$('#datepicker0').datetimepicker('show');
  </script>
    	<div id="joms-profile--information" class="joms-tab__content">
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form" onsubmit="return checkcal();">
                <legend class="joms-form__legend">Select Dates/Times you are available</legend>
                <div id="container">
                    <div class="joms-form__group has-privacy" for="field3" id="datediv0">
                        <span>Add Date/Time</span>
                         <!--<input type="text" name="datepicker[]" data-required="true" required>-->
                         <input type="text" name="datepicker[]" id="datepicker0" data-required="true" readonly="readonly" required>
                    </div>
                </div>
                <div class="joms-form__group has-privacy" for="field2">               
                </div>
                <div class="joms-form__group has-privacy" for="field2">
                    <span></span>
                    <input type="button" onclick="addcalender();" class="joms-button--primary joms-button--full-small" value="<?php echo "Add";?>">                   
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input name="countf" id="countf" type="hidden" value="0"  />
                    <input type="hidden" name="selgroup" value="<?php echo $fieldset['selgroup']; ?>" />
                    <?php foreach($fieldset['selmember'] as $rowmem) : ?>
                        <input type="hidden" name="selmember[]" value="<?php echo $rowmem; ?>" />
                    <?php endforeach; ?>
                    <input type="hidden" name="coursetype" value="<?php echo $fieldset['coursetype']; ?>" />
                    <input type="hidden" name="selcourse" value="<?php echo $fieldset['selcourse']; ?>" />
                    <input type="hidden" name="picks" value="<?php echo $fieldset['picks']; ?>" />
                    <input type="hidden" name="responses" value="<?php echo $fieldset['responses']; ?>" />
                    <input type="hidden" name="notify" value="<?php echo $fieldset['notify']; ?>" />
                    <input type="hidden" name="stepset" value="adddata" />
                    <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest&stepset=options&selgroup='.$fieldset['selgroup']); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                </div>
            </form>
        </div>
    <?php } else if($step=="adddata") { ?>
    	<div id="joms-profile--information" class="joms-tab__content">
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form" onsubmit="return checkcourse();">
                <legend class="joms-form__legend">New Request - Submit Request</legend>
                <div class="joms-tab__content">
                    <span>You are about submit a request to the group to find partners to play golf with.</span>
                    <br /><br />
                    <span>If you are sure you are ready, Click the Submit button below.</span>
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input type="hidden" name="selgroup" value="<?php echo $fieldset['selgroup']; ?>" />
                    <?php foreach($fieldset['selmember'] as $rowmem) : ?>
                        <input type="hidden" name="selmember[]" value="<?php echo $rowmem; ?>" />
                    <?php endforeach; ?>
                    <input type="hidden" name="coursetype" value="<?php echo $fieldset['coursetype']; ?>" />
                    <input type="hidden" name="selcourse" value="<?php echo $fieldset['selcourse']; ?>" />
                    <input type="hidden" name="picks" value="<?php echo $fieldset['picks']; ?>" />
                    <input type="hidden" name="responses" value="<?php echo $fieldset['responses']; ?>" />
                    <input type="hidden" name="notify" value="<?php echo $fieldset['notify']; ?>" />
                    <?php foreach($fieldset['datepicker'] as $rowdate) : ?>
                        <input type="hidden" name="datepicker[]" value="<?php echo $rowdate; ?>" />
                    <?php endforeach; ?>
                    <input type="hidden" name="stepset" value="submitsucc" />
                    <input type="button" onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest&stepset=calender&selgroup='.$fieldset['selgroup']); ?>';" class="joms-button--primary joms-button--full-small" value="<?php echo "Previous";?>">&nbsp;
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Submit";?>">
                </div>
            </form>
        </div>
    <?php } else if($step=="submitsucc") { ?>
    	<div id="joms-profile--information" class="joms-tab__content">
                <legend class="joms-form__legend">New Request - Submit Successfully</legend>
                <div class="joms-form__group has-privacy" for="field2">
                    <span>Group Schedule Request Sent Successfully.</span>
                </div> 
        </div>
    <?php } else { ?>
    
        <div id="joms-profile--information" class="joms-tab__content">
            <?php if ( $rows ) { ?>
            <form name="jsform-group-schedule" id="frmGroupSchedule" action="<?php echo CRoute::_('index.php?option=com_community&view=groupschedule&task=newrequest') ?>" method="POST" class="js-form">
                <legend class="joms-form__legend">New Request</legend>
                <div class="joms-form__group has-privacy" for="field2">
                    <span title="Select Group">Select Group</span>
                    <select class="joms-select" name="selgroup"  data-required="true" required>
                    <option value="" >Select Group</option>
                    <?php foreach($rows as $row) : ?>
                        <option value="<?php echo $row->id; ?>" ><?php echo $row->name; ?></option>
                    <?php endforeach; ?>
                    </select> 
                </div>
                <div class="joms-form__group">
                    <span></span>
                    <input type="hidden" name="stepset" value="members" />
                    <input type="submit" class="joms-button--primary joms-button--full-small" value="<?php echo "Next";?>">
                </div>
            </form>
        
        <?php } else { ?>
            <div class="cEmpty cAlert"><?php echo 'You have no group.'; ?></div>
        <?php } ?>
    
        </div>

    <?php } ?>
    
    
</div>
<script>
function selectall(option) {
	var sel = document.getElementById("selmember");
	if(option=='None') {
		for(var i=0; i<sel.options.length; i++){
		  sel.options[i].selected = false;
		}
	} else {
		for(var i=0; i<sel.options.length; i++){
		  sel.options[i].selected = true;
		}
	}
}
function checkcourse() {
	
	if(document.getElementById('typefav').checked) {
		var courseval= document.getElementById('selfavorite').value;
		if(courseval=="") {
			alert("Select Favorites Course.");
			return false;
		} else {
			document.getElementById("selcourse").value=courseval;
		}
  		
	}else if(document.getElementById('typeloc').checked) {
		var courseval= document.getElementById('sellocal').value;
		if(courseval=="") {
			alert("Select Local Course.");
			return false;
		} else {
			document.getElementById("selcourse").value=courseval;
		}
	  
	}else if(document.getElementById('typesea').checked) {
		var courseval= document.getElementById('selsearch').value;
		var searchkeyval= document.getElementById('searchkey').value;
		
		document.getElementById("selcourse").value=courseval;
		
		if(searchkeyval!="" && courseval!="No") {
			if(courseval=="") {
				alert("Select Search Course.");
				return false;
			} else {
				document.getElementById("selcourse").value=courseval;
			}
		} else {
			if(searchkeyval=="") {
				alert("Enter Search Key.");
				return false;
			}
		}
	  
	}else {
	  	alert("Select Course Type.");
		return false;
	}

	return true;
}

function checkcal() {
	var calval= document.getElementById('datepicker0').value;
	if(calval=="") {
		alert("Click Add button for add date and time.");
		return false;
	} 
	return true;
}
</script>
