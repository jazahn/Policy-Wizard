<?php

class IsitesIdentity extends UserIdentity {
	
	protected $userid;
	private $keyword;
	private $topicId;
	private $permissions;
	
	public function __construct(){
		$this->authenticate();
		
	}
	
	public function authenticate(){

		//error_log(var_export($_REQUEST, 1));

		$this->userid = Yii::app()->getRequest()->getParam('userid');
		$this->keyword = Yii::app()->getRequest()->getParam('keyword');
		$this->topicId = Yii::app()->getRequest()->getParam('topicId');
		$this->permissions = Yii::app()->getRequest()->getParam('permissions');
				
		$decrypted_userid = Yii::app()->isitestool->getDecryptedUserId($this->userid);
		$this->external_id = $decrypted_userid;
		$this->username = $decrypted_userid;
		
		//$this->username = "$decrypted_userid";
		/*
		Yii::app()->HarvardPerson->setup($decrypted_userid);
		//$this->id = Yii::app()->HarvardPerson->huid;
		$this->external_id = Yii::app()->HarvardPerson->huid;
		$this->username = Yii::app()->HarvardPerson->huid;
		$this->name = Yii::app()->HarvardPerson->full_name;
		$this->fname = Yii::app()->HarvardPerson->fname;
		$this->lname = Yii::app()->HarvardPerson->lname;
		*/
		
		// determine the level of permission...
		$perm = '';
		if(Yii::app()->isitestool->isGuest()){
			$perm = 'guest';
			$perm_id = UserIdentity::GUEST;
		}
		if(Yii::app()->isitestool->isEnrollee()){
			$perm = 'enrollee';
			$perm_id = UserIdentity::ENROLLEE;
		}
		if(Yii::app()->isitestool->isAdmin()){
			$perm = 'admin';
			$perm_id = UserIdentity::ADMIN;
		}
		if(Yii::app()->isitestool->isSuper()){
			$perm = 'super';
			$perm_id = UserIdentity::SUPER;
		}
		// set the collection we are logging in to with the highest level permission
		// TODO: use isites search service to get the collection name
		//$this->collections[$this->topicId] = $perm;
		$this->perm = $perm;
		$this->perm_id = $perm_id;
		
		
		
		$this->setup();
		
	}
	

	
	/*
	* logout will be handled by isites...
	*/
	public function logout(){
		@session_destroy();
		$logoutUrl = 'http://isites.harvard.edu/icb/logout.do';
		
		$this->redirect($logoutUrl);
		
	}
	
	
}


?>
