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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
jimport('joomla.utilities.arrayhelper');
jimport('joomla.html.html');

class CommunityViewGroupschedule extends CommunityView {
	
	 /**
     * DIsplay list of groupschedule
     *
     * if no id is set, we're viewing our own groupschedule
     */
    public function groupschedule($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }
		
		/**
         * Opengraph
         */
        CHeadHelper::setType('website', 'Golf-With-Me Request View');

        $this->showSubMenu();
		
		//start new requests
		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		//$fieldset = array();
		
		
		//$groupsModel = CFactory::getModel('groups');
		$groupsModel = CFactory::getModel('groupschedule');
                $courseModel = CFactory::getModel('courses');
		
		//expiry
		$groupsModel->getExpireSchedule();
		
		
		$step=$fields[stepset];
		if($step=="submitresponse") {		
			
			if($fields[seldate]!='') {
				$savedate = $groupsModel->addGroupScheduleAccept($my->id, $fields);
				$mainframe->enqueueMessage('Schedule response successfully.', 'success');
				$newrequest = $groupsModel->getPendingSchedule($my->id);
				//email
				$mailer = JFactory::getMailer();
				$sender = array( 
					$config->get( 'mailfrom' ),
					$config->get( 'fromname' ) 
				);
				$mailer->setSender($sender);

				

				$gsData = $groupsModel->getGroupSchedule($fields['reqid']);
				if($gsData->notify!="") {
					$usr = CFactory::getUser($gsData->uid);
					$recipient = $usr->email;
					$mailer->addRecipient($recipient);
					$course = $groupsModel->getCourseDetails($gsData->selcourse);
					$acceptUser=$my->name;
					
                                        $subject=$my->name . ' Accepted Your Golf-With-Me Request at ' . $course->Name;
                                        $mailer->setSubject($subject);
					$body   = "Hi ".$usr->name." <br /> Your friend ".$acceptUser." accepted your Golf-With-Me request at ".$course->Name.". <br /><br /> Please <a href='".JURI::base()."index.php?option=com_community&view=groupschedule'>click here to view the status of your request.</a>.<br /><br /> Thank You <br />The Golf With Me Team<br /><br /> <img src='".JURI::base()."images/gwmhorlogo2.png'>";
					$mailer->isHtml(true);
					$mailer->Encoding = 'base64';
					$mailer->setBody($body);
					$send = $mailer->Send();
				}
				//email
				
				//email notify
				$memneed=$gsData->picks;
				if($memneed!='') {
					foreach($fields['seldate'] as $rowdate) {
						$accda= $groupsModel->getDateCloseCount($fields['reqid'],$rowdate);
						$accmem=$accda->completemem;
						if($memneed==$accmem) {
							$subject='One of your Golf-With-Me Requests Has Enough Golfers';
							$mailer->setSubject($subject);
							$usr = CFactory::getUser($gsData->uid);
							$recipient = $usr->email;
							$mailer->addRecipient($recipient);
							
							$body   = "Hi ".$usr->name." <br /><br /> This is to notify you that you have at least one date and time that has enough golfers. <br /><br /> Thank You <br />The Golf With Me Team<br /><br /> <img src='".JURI::base()."images/gwmhorlogo2.png'>";
							$mailer->isHtml(true);
							$mailer->Encoding = 'base64';
							$mailer->setBody($body);
							$send = $mailer->Send();	
						}
						
					}
				}
				//email notify end
				
			} else {
				$mainframe->enqueueMessage('Please select a date.', 'error');
				$newrequest = $groupsModel->getPendingSchedule($my->id);
			}
		} else if ($step=="cancel") {
				$savedate = $groupsModel->addGroupScheduleCancel($my->id, $fields);
				$mainframe->enqueueMessage('Schedule cancel successfully.', 'success');
				$newrequest = $groupsModel->getPendingSchedule($my->id);
		} else if ($step=="cancelrejected") {
				$savedate = $groupsModel->addGroupScheduleCancelRejected($my->id, $fields);
				$mainframe->enqueueMessage('Schedule rejected cancel successfully.', 'success');
				$newrequest = $groupsModel->getPendingSchedule($my->id);
		} else if ($step=="rejected") {
			$newrequest = $groupsModel->getRejectedSchedule($my->id);
		} else {
			$newrequest = $groupsModel->getPendingSchedule($my->id);
		}
		
		$findModel = CFactory::getModel('findgolfers');
		//expiry
		$findModel->getExpireSchedule();
		if ($step=="rejected") {
			$findnewrequest = $findModel->getRejectedSchedule($my->id);
		} else {
			$findnewrequest = $findModel->getPendingSchedule($my->id);
		}
		//end new requests
		
		//start close their requests
		$step=$fields[stepset];
		if($step=="accept") {
			$savedate = $groupsModel->updateRequestorAccept($my->id, $fields);
			$mainframe->enqueueMessage('Request Closed and Tee-Time recorded successfully.', 'success');
			//$requestrecord = $groupsModel->getGroupScheduleListClose($my->id);
                        $requestdetails = $groupsModel->getScheduleDetails($fields['req']);
                        $coursedata = $courseModel->getCourseDetails($closerequest[0]->selcourse);
                        
                        //print_r($fields);
                        //print_r($requestdetails);
                        //print_r($closerequest);
                        //print_r($coursedata);
			//email
			
			
			$gsid=$fields['req'];
			$gsData = $groupsModel->getGroupSchedule($gsid);
			//date
			$scheduleDate = $groupsModel->getGroupScheduleDate($gsid);
			foreach ( $scheduleDate as $rowdate ) {
                            $accda= $groupsModel->getDateAcceptCount($gsid,$rowdate->id);
                            if($accda->actmem>0) { 
                                //user
                                $accMem = $groupsModel->getGroupScheduleAcceptMember($gsid,$rowdate->id);
                                foreach ( $accMem as $damem ) {
                                    $mailer = JFactory::getMailer();
                                    $sender = array( 
                                            $config->get( 'mailfrom' ),
                                            $config->get( 'fromname' ) 
                                    );
                                    $mailer->setSender($sender);

                                    $subject='Final Tee-Time set for  ' . $coursedata->Name;
                                    $mailer->setSubject($subject);
                                    
                                    $usr = CFactory::getUser($damem->userid);
                                    $recipient = $usr->email;
                                    $mailer->addRecipient($recipient);

                                    $body   = "Hi ".$usr->name." <br /><br /> This e-mail is to inform you that the final tee-time for the event at " . $requestdetails->CourseName . " is " . $gsData->teetime . ". <br /><br /> For more details, <a href='".JURI::base()."index.php?option=com_community&view=groupschedule'>click here.</a> <br /><br /> Thank You <br />The Golf With Me Team<br /><br /> <img src='".JURI::base()."images/gwmhorlogo2.png'>";
                                    $mailer->isHtml(true);
                                    $mailer->Encoding = 'base64';
                                    $mailer->setBody($body);
                                    $send = $mailer->Send();
                                }
                            }
			}

			//email
		} else if ($step=="cancelclose") {
			$savedate = $groupsModel->updateRequestorCancel($my->id, $fields);
			$mainframe->enqueueMessage('Requestor cancel successfully.', 'success');
			$closerequest = $groupsModel->getGroupScheduleListClose($my->id);
		} else {
			$closerequest = $groupsModel->getGroupScheduleListCloseNoAccepts($my->id);
                        //$closerequest = $groupsModel->getGroupScheduleListClose($my->id);
		}
		
		$findcloserequest = $findModel->getFindGolfersListClose($my->id);
		//end close their requests
		
		//start upcoming events
		$requstorupcomming = $groupsModel->getScheduleRequestorAcceptList($my->id);
		$userupcomming = $groupsModel->getGroupScheduleAcceptList($my->id);
		
		$findrequstorupcomming = $findModel->getScheduleRequestorAcceptList($my->id);
		$finduserupcomming = $findModel->getFindGolfersAcceptList($my->id);
		//print_r($finduserupcomming);
		//end upcoming events
		
		//all schedule
		$groupsModel = CFactory::getModel('groupschedule');
		$groupschedule = $groupsModel->getGroupScheduleList($my->id);

        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
            ->set('config', $config)
            ->set('rows', $groupschedule)
            ->set('newrequest', $newrequest)
            ->set('closerequest', $closerequest)
            ->set('requstorupcomming', $requstorupcomming)
            ->set('userupcomming', $userupcomming)
            ->set('findnewrequest', $findnewrequest)
            ->set('findcloserequest', $findcloserequest)
            ->set('findrequstorupcomming', $findrequstorupcomming)
            ->set('finduserupcomming', $finduserupcomming)
            ->set('step', $step)
            //->set('pagination', $data->pagination)
                            //->set('submenu', $this->showSubmenu(false))
            ->fetch('groupschedule/list');
    }
	
    public function newrequest($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }

        //$this->addPathway(JText::_('COM_COMMUNITY_FRIENDS'), CRoute::_('index.php?option=com_community&view=groupschedule'));
        //$this->addPathway(JText::_("COM_COMMUNITY_FRIENDS_WAITING_AUTHORIZATION"), '');

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', 'New Golf-With-Me Request');

        //$this->showSubMenu();

		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		$fieldset = array();
		
		$step=$fields[stepset];
		if($step=="members") {
			$groupId=$fields[selgroup];
			$groupsModel = CFactory::getModel('groupschedule');
			$groups = $groupsModel->getGroupsMembers($groupId);
			$fieldset['selgroup']=$groupId;
			
		} else if($step=="course") {
			$groupId=$fields[selgroup];
			$groupsModel = CFactory::getModel('groupschedule');
			//$groups = $groupsModel->getCourse();
			$groupsFav = $groupsModel->getCourseFavorites($my->id);
			$groupsLoc = $groupsModel->getCourseLocal($my->id);
			$fieldset['selgroup']=$groupId;
			$fieldset['selmember']=$fields[selmember];
			
		} else if($step=="options") {
			if($fields[selcourse]=="No") {
				$groupId=$fields[selgroup];
				$groupsModel = CFactory::getModel('groupschedule');
				$groups = $groupsModel->getCourseSearch($fields[searchkey]);
				$groupsFav = $groupsModel->getCourseFavorites($my->id);
				$groupsLoc = $groupsModel->getCourseLocal($my->id);
				$fieldset['selgroup']=$groupId;
				$fieldset['selmember']=$fields[selmember];
				$fieldset['searchkey']=$fields[searchkey];
				$step="course";
			} else {
				$groupId=$fields[selgroup];
				$fieldset['selgroup']=$groupId;
				$fieldset['selmember']=$fields[selmember];
				$fieldset['coursetype']=$fields[coursetype];
				$fieldset['selcourse']=$fields[selcourse];
				//$step="calender";
			}
			
		} else if($step=="calender") {
			$groupId=$fields[selgroup];
			$fieldset['selgroup']=$groupId;
			$fieldset['selmember']=$fields[selmember];
			$fieldset['coursetype']=$fields[coursetype];
			$fieldset['selcourse']=$fields[selcourse];
			$fieldset['picks']=$fields[picks];
			$fieldset['responses']=$fields[responses];
			$fieldset['notify']=$fields[notify];
			
		} else if($step=="adddata") {
			$groupId=$fields[selgroup];
			$fieldset['selgroup']=$groupId;
			$fieldset['selmember']=$fields[selmember];
			$fieldset['coursetype']=$fields[coursetype];
			$fieldset['selcourse']=$fields[selcourse];
			$fieldset['picks']=$fields[picks];
			$fieldset['responses']=$fields[responses];
			$fieldset['notify']=$fields[notify];
			$fieldset['datepicker']=$fields[datepicker];
			
		} else if($step=="submitsucc") {
			$groupId=$fields[selgroup];
			$fieldset['selgroup']=$groupId;
			$fieldset['selmember']=$fields[selmember];
			$fieldset['coursetype']=$fields[coursetype];
			$fieldset['selcourse']=$fields[selcourse];
			$fieldset['picks']=$fields[picks];
			$fieldset['responses']=$fields[responses];
			$fieldset['notify']=$fields[notify];
			$fieldset['datepicker']=$fields[datepicker];
			
			$groupsModel = CFactory::getModel('groupschedule');
			$savedate = $groupsModel->addGroupSchedule($my->id, $fieldset);
			//print_r($savedate);
			
			
			
			
			//if ( $send !== true ) {
				//echo 'Error sending email: ';
			//} else {
				//echo 'Mail sent';
			//}
			foreach($fields[selmember] as $rowmem) {
                            //email
                            $mailer = JFactory::getMailer();
                            $sender = array( 
                                $config->get( 'mailfrom' ),
                                $config->get( 'fromname' ) 
                            );
                            
                            $mailer->setSender($sender);
                            $userOwner = JFactory::getUser();
                            $sendName= $userOwner->name;


                            $subject='Invite from Golf-With-Me from '.$sendName;
                            $mailer->setSubject($subject);
                            
                            $usr = CFactory::getUser($rowmem);
                            $recipient = $usr->email;
                            $mailer->addRecipient($recipient);

                            $course = $groupsModel->getCourseDetails($fields[selcourse]);

                            $body   = "Hi ".$usr->name." <br /><br />Your Friend ".$sendName." invited you to Golf-With-Me at ".$course->Name." Golf Course. " . $fields[picks] . " golfers are needed.  Can you play? Please <a href='".JURI::base()."index.php?option=com_community&view=groupschedule'>click here.</a> to respond. <br /><br /> Thank You <br />The Golf With Me Team<br /><br /> <img src='".JURI::base()."images/gwmhorlogo2.png'>";
                            $mailer->isHtml(true);
                            $mailer->Encoding = 'base64';
                            $mailer->setBody($body);
                            $send = $mailer->Send();
			}
			//email

		} else {
			//$groupsModel = CFactory::getModel('groups');
			$groupsModel = CFactory::getModel('groupschedule');
			$sorted = $jinput->get->get('sort', 'latest', 'STRING');
			$groups = $groupsModel->getGroups($my->id);
		}
		//die();
		//print_r($groups);
        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rows', $groups)
				->set('groupsFav', $groupsFav)
				->set('groupsLoc', $groupsLoc)
                //->set('submenu', $this->showSubmenu(false))
                //->set('featuredList', $featuredList)
				->set('step', $step)
				->set('fieldset', $fieldset)
                ->set('pagination', $data->pagination)
                ->fetch('groupschedule/request-sent');
    }
	
    public function viewall($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', 'Golf-With-Me View List');

        $this->showSubMenu();
		
		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		//$fieldset = array();
		
		
		//$groupsModel = CFactory::getModel('groups');
		$groupsModel = CFactory::getModel('groupschedule');
		$golfersModel = CFactory::getModel('findgolfers');
		
		
		$step=$fields[stepset];
		$actstep=$fields[actstep];
		if($step=="created") {		
			//group
			if($actstep=='editdate') {
				$savedate = $groupsModel->updateRequestDate($fields);
				$mainframe->enqueueMessage('Request date update successfully.', 'success');
			}
			if($actstep=='deldate') {
				$savedate = $groupsModel->deleteRequestDate($fields);
				$mainframe->enqueueMessage('Request date delete successfully.', 'success');
			}
			$rowgroups = $groupsModel->getGroupScheduleList($my->id);
			
			//golfer
			if($actstep=='editdateg') {
				$savedate = $golfersModel->updateRequestDate($fields);
				$mainframe->enqueueMessage('Request date update successfully.', 'success');
			}
			if($actstep=='deldateg') {
				$savedate = $golfersModel->deleteRequestDate($fields);
				$mainframe->enqueueMessage('Request date delete successfully.', 'success');
			}
			$rowgolfers = $golfersModel->getFindGolfersList($my->id);
		
		} else if ($step=="response") {
				//group
				if($actstep=='canreq') {
					$savedate = $groupsModel->userScheduleAcceptUpdate($fields);
					$mainframe->enqueueMessage('Request cancel successfully.', 'success');
				}
				$rowgroups = $groupsModel->getUserAcceptSchedule($my->id);
				
				//golfer
				if($actstep=='canreqg') {
					$savedate = $golfersModel->userScheduleAcceptUpdate($fields);
					$mainframe->enqueueMessage('Request cancel successfully.', 'success');
				}
				$rowgolfers = $golfersModel->getUserAcceptSchedule($my->id);
			
		} else if ($step=="pastevents") {
			
			$requstorupcomming = $groupsModel->getScheduleRequestorAcceptListPast($my->id);
			$userupcomming = $groupsModel->getGroupScheduleAcceptListPast($my->id);
			
			$findrequstorupcomming = $golfersModel->getScheduleRequestorAcceptListPast($my->id);
			$finduserupcomming = $golfersModel->getFindGolfersAcceptListPast($my->id);
			
		} else if ($step=="cancel") {
				$savedate = $groupsModel->addGroupScheduleCancel($my->id, $fields);
				$mainframe->enqueueMessage('Schedule cancel successfully.', 'success');
				$schedule = $groupsModel->getPendingSchedule($my->id);
		} else {
			$rowgroups = $groupsModel->getPendingSchedule($my->id);
		}
		
		//print_r($groups);
        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rowsgroup', $rowgroups)
				->set('rowsgolfers', $rowgolfers)
				->set('requstorupcomming', $requstorupcomming)
				->set('userupcomming', $userupcomming)
				->set('findrequstorupcomming', $findrequstorupcomming)
				->set('finduserupcomming', $finduserupcomming)
				->set('step', $step)
                //->set('pagination', $data->pagination)
                ->fetch('groupschedule/viewall');
    }
	
    public function recresponse($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }
		
		//$mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'success');

        //$this->addPathway(JText::_('COM_COMMUNITY_FRIENDS'), CRoute::_('index.php?option=com_community&view=groupschedule'));
        //$this->addPathway(JText::_("COM_COMMUNITY_FRIENDS_WAITING_AUTHORIZATION"), '');

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', 'Golf-With-Me Pending Response');

        //$this->showSubMenu();
		
		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		//$fieldset = array();
		
		
		//$groupsModel = CFactory::getModel('groups');
		$groupsModel = CFactory::getModel('groupschedule');
		
		
		$step=$fields[stepset];
		if($step=="submitresponse") {		
			if($fields[seldate]!='') {
				$savedate = $groupsModel->addGroupScheduleAccept($my->id, $fields);
				$mainframe->enqueueMessage('Schedule response successfully.', 'success');
				$schedule = $groupsModel->getPendingSchedule($my->id);
				
			} else {
				
				$schedule = $groupsModel->getPendingSchedule($my->id);
				//$gsData = $groupsModel->getGroupSchedule($fields['reqid']);
				//print_r($gsData);
				$mainframe->enqueueMessage('Please select date.', 'error');
			}
		} else if ($step=="cancel") {
				$savedate = $groupsModel->addGroupScheduleCancel($my->id, $fields);
				$mainframe->enqueueMessage('Schedule cancel successfully.', 'success');
				$schedule = $groupsModel->getPendingSchedule($my->id);
		} else {
			$schedule = $groupsModel->getPendingSchedule($my->id);
		}
		
		//print_r($groups);
        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rows', $schedule)
                ->set('pagination', $data->pagination)
                ->fetch('groupschedule/request-received');
    }
	
    public function closerequest($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }
		
        /**
         * Opengraph
         */
        CHeadHelper::setType('website', 'Requestor Close Request');

        //$this->showSubMenu();
		
		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		//$fieldset = array();
		
		
		//$groupsModel = CFactory::getModel('groups');
		$groupsModel = CFactory::getModel('groupschedule');
		
		
		$step=$fields[stepset];
		if($step=="accept") {
			$savedate = $groupsModel->updateRequestorAccept($my->id, $fields);
			$mainframe->enqueueMessage('Requestor Accept successfully.', 'success');
			$schedule = $groupsModel->getGroupScheduleListClose($my->id);
		} else if ($step=="cancel") {
			$savedate = $groupsModel->updateRequestorCancel($my->id, $fields);
			$mainframe->enqueueMessage('Requestor cancel successfully.', 'success');
			$schedule = $groupsModel->getGroupScheduleListClose($my->id);
		} else if ($step=="acceptcourse") {
			$fieldset['req']=$fields[req];
			$fieldset['sd']=$fields[sd];
			$fieldset['step']=$step;
			$schedule = $groupsModel->getGroupScheduleListClose($my->id);
			
		} else if ($step=="acceptteetime") {
			$fieldset['req']=$fields[req];
			$fieldset['sd']=$fields[sd];
			$fieldset['member']=$fields[member];
			$memcount=count($fields[member]);
			if($memcount<1) {
				$fieldset['step']="acceptcourse";
				$mainframe->enqueueMessage('Please select Golfers.', 'error');
			} else {
				$fieldset['step']=$step;
			}
			$schedule = $groupsModel->getGroupScheduleListClose($my->id);
			
		} else {
			$schedule = $groupsModel->getGroupScheduleListClose($my->id);
		}
		
		//print_r($groups);
        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rows', $schedule)
				->set('fieldset', $fieldset)
                //->set('pagination', $data->pagination)
                ->fetch('groupschedule/request-closed');
    }
	
    public function acceptrequest($data = null) {
        $mainframe = JFactory::getApplication();
		$jinput = $mainframe->input;

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }
		
        /**
         * Opengraph
         */
        CHeadHelper::setType('website', 'Requestor Accept Request');

        //$this->showSubMenu();
		
		//fields
		$fields=$data->fields;
		//print_r($fields);
		//print_r($_POST);
		//$fieldset = array();
		
		
		//$groupsModel = CFactory::getModel('groups');
		$groupsModel = CFactory::getModel('groupschedule');
		
		
		$step=$fields[stepset];
		$schedule = $groupsModel->getScheduleRequestorAcceptList($my->id);
		$useraccept = $groupsModel->getGroupScheduleAcceptList($my->id);
		
		//print_r($groups);
        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rows', $schedule)
				->set('userrows', $useraccept)
                ->set('pagination', $data->pagination)
                ->fetch('groupschedule/request-accept');
    }

    public function _addSubmenu() {

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;
        $task = $jinput->get('task', '');

        if (JFile::exists(JPATH_COMPONENT . '/libraries/advancesearch.php')) {
            require_once (JPATH_COMPONENT . '/libraries/advancesearch.php');
            $mySQLVer = CAdvanceSearch::getMySQLVersion();
        }
		
		$this->addSubmenuItem('index.php?option=com_community&view=groupschedule', JText::_('View All'));
                $this->addSubmenuItem('index.php?option=com_community&view=findgolfers&task=recresponse', JText::_('You Might Be Interested'));
		//$this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=viewall&stepset=created', JText::_('Your Past Requests'));
		//$this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=viewall&stepset=response', JText::_('Response Request'));
		//$this->addSubmenuItem('index.php?option=com_community&view=groupschedule&stepset=rejected', JText::_('Rejected Request'));
		
		//$this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=viewall&stepset=pastevents', JText::_('Past Events'));
		
		/*

        //if($task != 'sent' && $task != 'pending' ) {
            $this->addSubmenuItem('index.php?option=com_community&view=groupschedule', JText::_('COM_COMMUNITY_FRIENDS_VIEW_ALL'));
            //$this->addSubmenuItem('index.php?option=com_community&view=search&task=advancesearch', JText::_('COM_COMMUNITY_CUSTOM_SEARCH'));
            //$this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=invite', JText::_('COM_COMMUNITY_INVITE_FRIENDS'));
        //}

        $tmpl = new CTemplate();
        $tmpl->set('url', CRoute::_('index.php?option=com_community&view=search'));
        $html = $tmpl->fetch('search.submenu');
        $this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=sent', JText::_('COM_COMMUNITY_FRIENDS_REQUEST_SENT'));
        $this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=pending', JText::_('COM_COMMUNITY_FRIENDS_PENDING_APPROVAL'));
		*/
    }

    public function showSubmenu($display=true) {
        $this->_addSubmenu();
        return parent::showSubmenu($display);
    }

   

    /**
     * Search list of friends
     *
     * if no id is set, we're viewing our own friends
     */
    public function friendsearch($data) {

        require_once (JPATH_COMPONENT . '/libraries/profile.php');
        require_once (JPATH_COMPONENT . '/helpers/friends.php');

        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_SEARCH_FRIENDS_TITLE'));

        $avatarOnly = $jinput->get('avatar', '', 'NONE');
        $this->addPathway(JText::_('COM_COMMUNITY_SEARCH_FRIENDS_TITLE'));

        $my = CFactory::getUser();
        $friendsModel = CFactory::getModel('friends');
        $resultRows = array();

        $id = $jinput->getInt('userid', $my->id);

        $user = CFactory::getUser($id);
        $isMine = ( ($id == $my->id) && ($my->id != 0) );

        $pagination = (!empty($data)) ? $data->pagination : '';
        $alreadyfriend = array();

        $tmpl = new CTemplate();

        for ($i = 0; $i < count($data->result); $i++) {
            $row = $data->result[$i];
            $user = CFactory::getUser($row->id);
            $row->profileLink = CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->id);
            $row->friendsCount = $user->getFriendCount();
            $isFriend = CFriendsHelper::isConnected($row->id, $my->id);

            $row->user = $user;
            $row->addFriend = ((!$isFriend) && ($my->id != 0) && $my->id != $row->id) ? true : false;
            if ($row->addFriend) {
                $alreadyfriend[$row->id] = $row->id;
            }

            $resultRows[] = $row;
        }

        $tmpl->set('alreadyfriend', $alreadyfriend);
        $tmpl->set('data', $resultRows);
        $tmpl->set('sortings', '');
        $tmpl->set('pagination', $pagination);

        $featured = new CFeatured(FEATURED_USERS);
        $featuredList = $featured->getItemIds();

        $tmpl->set('featuredList', $featuredList);

        //CFactory::load( 'helpers' , 'owner' );
        $tmpl->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin());
        $tmpl->set('showFeaturedList', false);
        $tmpl->set('my', $my);
        $resultHTML = $tmpl->fetch('people.browse');
        unset($tmpl);

        $searchLinks = parent::getAppSearchLinks('people');

        if ($isMine) {
            $this->showSubmenu();
            /**
             * Opengraph
             */
            CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIENDS_MY_FRIENDS'));
        } else {
            $this->addSubmenuItem('index.php?option=com_community&view=profile&userid=' . $user->id, JText::_('COM_COMMUNITY_PROFILE_BACK_TO_PROFILE'));
            $this->addSubmenuItem('index.php?option=com_community&view=groupschedule&userid=' . $user->id, JText::_('COM_COMMUNITY_FRIENDS_VIEW_ALL'));
            $this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=mutualFriends&userid=' . $user->id . '&filter=mutual', JText::_('COM_COMMUNITY_MUTUAL_FRIENDS'));

            $tmpl = new CTemplate();
            $tmpl->set('view', "groupschedule");
            $tmpl->set('url', CRoute::_('index.php?option=com_community&view=groupschedule&task=viewfriends'));
            $html = $tmpl->fetch('friendsearch.submenu');
            $this->addSubmenuItem('index.php?option=com_community&view=groupschedule&task=viewfriends', JText::_('COM_COMMUNITY_SEARCH_FRIENDS'), 'joms.videos.toggleSearchSubmenu(this)', SUBMENU_LEFT, $html);

           return parent::showSubmenu($display);
            /**
             * Opengraph
             */
            CHeadHelper::setType('website', JText::sprintf('COM_COMMUNITY_FRIENDS_ALL_FRIENDS', $user->getDisplayName()));
        }

        $tmpl = new CTemplate();
        $tmpl->set('avatarOnly', $avatarOnly);
        $tmpl->set('results', $data->result);
        $tmpl->set('resultHTML', $resultHTML);
        $tmpl->set('query', $data->query);
        $tmpl->set('searchLinks', $searchLinks);
        echo $tmpl->fetch('friendsearch');
    }

    public function add($data = null) {

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIENDS_ADD_NEW_FRIEND'));
        ?>
        <div class="app-box">
            <p><?php echo JText::sprintf('COM_COMMUNITY_ADD_USER_AS_FRIEND', $data->name); ?></p>
            <form name="addfriend" method="post" action="">
                <div>
                    <label><?php echo JText::sprintf('COM_COMMUNITY_INVITE_PERSONAL_MESSAGE_TO', $data->name); ?></label>
                </div>

                <div>
                    <textarea name="msg" class="input textarea"></textarea>
                </div>

                <div>
                    <input type="submit" class="button" name="submit" value="<?php echo JText::_('COM_COMMUNITY_FRIENDS_ADD_BUTTON'); ?>"/>
                    <input type="submit" class="button" name="cancel" value="<?php echo JText::_('COM_COMMUNITY_CANCEL_BUTTON'); ?>"/>
                </div>
                <input type="hidden" class="button" name="id" value="<?php echo $data->id; ?>"/>
            </form>
        </div>
        <?php
    }

    public function online($data = null) {
        // Load the toolbar
        $this->showHeader(JText::_('COM_COMMUNITY_FRIENDS_ONLINE_FRIENDS'), 'generic');
        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_ONLINE_FRIENDS_TITLE'));
        $this->friends('',true);
    }

    public function sent($data = null) {
        $mainframe = JFactory::getApplication();

        // Load necessary window css / javascript headers.
        CWindow::load();

        $config = CFactory::getConfig();
        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }

        $this->addPathway(JText::_('COM_COMMUNITY_FRIENDS'), CRoute::_('index.php?option=com_community&view=groupschedule'));
        $this->addPathway(JText::_("COM_COMMUNITY_FRIENDS_WAITING_AUTHORIZATION"), '');

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIENDS_WAITING_AUTHORIZATION'));

        //$this->showSubMenu();

        $friends = CFactory::getModel('friends');

        $rows = !empty($data->sent) ? $data->sent : array();

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];
            $row->user = CFactory::getUser($row->id);
            $row->user->friendsCount = $row->user->getFriendCount();
            $row->user->profileLink = CUrlHelper::userLink($row->id);
        }

        // Featured
        $featured = new CFeatured(FEATURED_USERS);
        $featuredList = $featured->getItemIds();

        $tmpl = new CTemplate();
        echo $tmpl->set('my', $my)
                ->set('config', $config)
                ->set('rows', $rows)
                ->set('submenu', $this->showSubmenu(false))
                ->set('featuredList', $featuredList)
                ->set('pagination', $data->pagination)
                ->fetch('friends/request-sent');
    }

    public function deleteLink($controller, $method, $id) {
        $deleteLink = '<a class="remove" onClick="if(!confirm(\'' . JText::_('COM_COMMUNITY_CONFIRM_DELETE_FRIEND') . '\'))return false;" href="' . CUrl::build($controller, $method) . '&fid=' . $id . '">&nbsp;</a>';
        return $deleteLink;
    }

    /**
     * Display a list of pending friend requests
     * */
    public function pending($data = null) {
        if (!$this->accessAllowed('registered'))
            return;

        $mainframe = JFactory::getApplication();
        $config = CFactory::getConfig();

        CWindow::load();

        $my = CFactory::getUser();

        if ($my->id == 0) {
            $mainframe->enqueueMessage(JText::_('COM_COMMUNITY_PLEASE_LOGIN_WARNING'), 'error');
            return;
        }

        // Set pathway
        $this->addPathway(JText::_('COM_COMMUNITY_FRIENDS'), CRoute::_('index.php?option=com_community&view=groupschedule'));
        $this->addPathway(JText::_('COM_COMMUNITY_FRIENDS_AWAITING_AUTHORIZATION'), '');

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIENDS_AWAITING_AUTHORIZATION'));

        // Load submenu
        //$this->showSubMenu();

        $rows = !empty($data->pending) ? $data->pending : array();

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];
            $row->user = CFactory::getUser($row->id);
            $row->user->friendsCount = $row->user->getFriendCount();
            $row->user->profileLink = CUrlHelper::userLink($row->id);
            $row->msg = $this->escape($row->msg);
        }

        //Featured
        $featured = new CFeatured(FEATURED_USERS);
        $featuredList = $featured->getItemIds();

        $tmpl = new CTemplate();
        echo $tmpl->set('rows', $rows)
                ->setRef('my', $my)
                ->set('config', $config)
                ->set('pagination', $data->pagination)
                ->set('submenu', $this->showSubmenu(false))
                ->set('featuredList', $featuredList)
                ->fetch('friends/request-received');
    }

    public function addSuccess($data = null) {
        $this->addInfo(JText::sprintf('COM_COMMUNITY_FRIENDS_WILL_RECEIVE_REQUEST', $data->name));

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIEND_ADDED_SUCCESSFULLY_TITLE'));
    }

    /**
     * Show the invite window
     */
    public function invite() {
        $mainframe = JFactory::getApplication();
        $jinput = $mainframe->input;

        $jConfig = JFactory::getConfig();

        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::sprintf('COM_COMMUNITY_FRIENDS_INVITE_FRIENDS_TITLE', $jConfig->get('sitename')));

        $my = CFactory::getUser();

        //$this->showSubmenu();

        $post = ($jinput->post->get('action', '', 'STRING') == 'invite') ? $jinput->post->getArray() : array('message' => '', 'emails' => '');

        $pathway = $mainframe->getPathway();
        $this->addPathway(JText::_('COM_COMMUNITY_FRIENDS'), CRoute::_('index.php?option=com_community&view=groupschedule'));
        $this->addPathway(JText::_('COM_COMMUNITY_INVITE_FRIENDS'), '');

        // Process the Suggest Friends
        // Load required filterbar library that will be used to display the filtering and sorting.

        $id = $jinput->getInt('userid', $my->id);
        $user = CFactory::getUser($id);
        $sorted = $jinput->get->get('sort', 'suggestion', 'STRING');
        $filter = $jinput->get->get('filter', 'suggestion', 'STRING');
        $friends = CFactory::getModel('friends');

        $rows = $friends->getFriends($id, $sorted, true, $filter);
        $resultRows = array();

        foreach ($rows as $row) {
            $user = CFactory::getUser($row->id);

            $obj = clone($row);
            $obj->friendsCount = $user->getFriendCount();
            $obj->profileLink = CUrlHelper::userLink($row->id);
            $obj->isFriend = true;
            $resultRows[] = $obj;
        }

        unset($rows);

        $app = CAppPlugins::getInstance();
        $appFields = $app->triggerEvent('onFormDisplay', array('jsform-friends-invite'));
        $beforeFormDisplay = CFormElement::renderElements($appFields, 'before');
        $afterFormDisplay = CFormElement::renderElements($appFields, 'after');

        $tmpl = new CTemplate();
        $config = CFactory::getConfig();
        $isLimit = $config->get('invite_only', 0) && (intval( $config->get('invite_registation_limit', 0) ) > 0) && !COwnerHelper::isCommunityAdmin();
        $limit = intval( $config->get('invite_registation_limit') );

        // substract limit with invitation sent
        $invitationTable = JTable::getInstance('invitation', 'CTable');
        $invitationTable->load($my->id);
        $invited = $invitationTable->getTotalInvitedUsers();
        $limit = max(0, $limit - $invited);

        echo $tmpl->set('beforeFormDisplay', $beforeFormDisplay)
                ->set('afterFormDisplay', $afterFormDisplay)
                ->set('my', $my)
                ->set('post', $post)
                ->setRef('friends', $resultRows)
                ->set('config', CFactory::getConfig())
                ->set('submenu', $this->showSubmenu(false))
                ->set('isLimit', $isLimit)
                ->set('limit', $limit)
                ->set('isAdmin', COwnerHelper::isCommunityAdmin())
                ->fetch('friends.invite');
    }

    public function news() {
        /**
         * Opengraph
         */
        CHeadHelper::setType('website', JText::_('COM_COMMUNITY_FRIENDS_FRIENDS_NEWS'));
    }

}
