<?php 
// Task Model Object

// empty TaskException class so we can catch task errors
class TaskException extends Exception { }

class Task {
	// define private variables
	// define variable to store task id number
	private $_id;
	private $_UrunKodu;
	private $_HedefSayi;
	private $_HedefSure;
	private $_UretimMiktari;
	private $_Ortalama;
	private $_Sure;
	private $_HedefIslem;
	private $_IslemSuresi;
	private $_MakinaNo;
	private $_Personel;
	private $_SonMakine;
  
  
  // constructor to create the task object with the instance variables already set
	public function __construct($id,$UrunKodu,$HedefSayi,$HedefSure,$UretimMiktari,$Ortalama,$Sure,$HedefIslem,$IslemSuresi,$MakinaNo,$Personel,$SonMakine) {
		$this->setID($id);
		$this->setUrunKodu($UrunKodu);
		$this->setHedefSayi($HedefSayi);
		$this->setHedefSure($HedefSure);
		$this->setUretimMiktari($UretimMiktari);
		$this->setOrtalama($Ortalama);
		$this->setSure($Sure);
		$this->setHedefIslem($HedefIslem);
		$this->setIslemSuresi($IslemSuresi);
		$this->setMakinaNo($MakinaNo);
		$this->setPersonel($Personel);
		$this->setSonMakine($SonMakine);
	}
  
  // function to return task ID
	public function getID() {
		return $this->_id;
	}
  
  
	public function getUrunKodu() {
		return $this->_UrunKodu;
	}
  
	public function getHedefSayi() {
		return $this->_HedefSayi;
	}

	
	public function getHedefSure() {
		return $this->_HedefSure;
	}

	
	public function getUretimMiktari() {
		return $this->_UretimMiktari;
	}

	
	public function getOrtalama() {
		return $this->_Ortalama;
	}

	
	public function getSure() {
		return $this->_Sure;
	}

	
	public function getHedefIslem() {
		return $this->_HedefIslem;
	}

	
	public function getIslemSuresi() {
		return $this->_IslemSuresi;
	}

	
	public function getMakinaNo() {
		return $this->_MakinaNo;
	}

	
	public function getPersonel() {
		return $this->_Personel;
	}



	public function getSonMakine() {
		return $this->_SonMakine;
	}
  
	// function to set the private task ID
	public function setID($id) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
			throw new TaskException("Task ID error");
		}
		$this->_id = $id;
	}
  
  
	public function setUrunKodu($UrunKodu) {
		
		$this->_UrunKodu = $UrunKodu;
	}
  
	public function setHedefSayi($HedefSayi) {
		
		if(($HedefSayi !== null) && (!is_numeric($HedefSayi) || $HedefSayi <= 0 || $HedefSayi > 9223372036854775807 || $this->_HedefSayi !== null)) {
			throw new TaskException("Task HedefSayi error");
		}
		$this->_HedefSayi = $HedefSayi;
	}

	public function setHedefSure($HedefSure) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($HedefSure !== null) && (!is_numeric($HedefSure) || $HedefSure <= 0 || $HedefSure > 9223372036854775807 || $this->_HedefSure !== null)) {
			throw new TaskException("Task HedefSure error");
		}
		$this->_HedefSure = $HedefSure;
	}

	public function setUretimMiktari($UretimMiktari) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($UretimMiktari !== null) && (!is_numeric($UretimMiktari) || $UretimMiktari <= 0 || $UretimMiktari > 9223372036854775807 || $this->_UretimMiktari !== null)) {
			throw new TaskException("Task UretimMiktari error");
		}
		$this->_UretimMiktari = $UretimMiktari;
	}

	public function setOrtalama($Ortalama) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($Ortalama !== null) && (!is_numeric($Ortalama) || $Ortalama <= 0 || $Ortalama > 9223372036854775807 || $this->_Ortalama !== null)) {
			throw new TaskException("Task Ortalama error");
		}
		$this->_Ortalama = $Ortalama;
	}

	public function setSure($Sure) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($Sure !== null) && (!is_numeric($Sure) || $Sure <= 0 || $Sure > 9223372036854775807 || $this->_Sure !== null)) {
			throw new TaskException("Task Sure error");
		}
		$this->_Sure = $Sure;
	}

	public function setHedefIslem($HedefIslem) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		if(($HedefIslem !== null) && (!is_numeric($HedefIslem) || $HedefIslem <= 0 || $HedefIslem > 9223372036854775807 || $this->_HedefIslem !== null)) {
			throw new TaskException("Task HedefIslem error");
		}
		$this->_HedefIslem = $HedefIslem;
	}

	public function setIslemSuresi($IslemSuresi) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		
		$this->_IslemSuresi = $IslemSuresi;
	}

	public function setMakinaNo($MakinaNo) {
		// if passed in task ID is not null or not numeric, is not between 0 and 9223372036854775807 (signed bigint max val - 64bit)
		// over nine quintillion rows
		
		$this->_MakinaNo = $MakinaNo;
	}

	public function setPersonel($Personel) {
		
		
		$this->_Personel = $Personel;
	}

	public function setSonMakine($SonMakine) {
		// if passed in SonMakine is not Y or N
		
		$this->_SonMakine = strtoupper($SonMakine);
	}
  
  
  // function to return task object as an array for json
	public function returnTaskAsArray() {
		$task = array();
		$task['id'] = $this->getID();
		$task['UrunKodu'] = $this->getUrunKodu();
		$task['HedefSayi'] = $this->getHedefSayi();
		$task['HedefSure'] = $this->getHedefSure();
		$task['UretimMiktari'] = $this->getUretimMiktari();
		$task['Ortalama'] = $this->getOrtalama();
		$task['Sure'] = $this->getSure();
		$task['HedefIslem'] = $this->getHedefIslem();
		$task['IslemSuresi'] = $this->getIslemSuresi();
		$task['MakinaNo'] = $this->getMakinaNo();
		$task['Personel'] = $this->getPersonel();
		$task['SonMakine'] = $this->getSonMakine();
		return $task;
	}
  
}