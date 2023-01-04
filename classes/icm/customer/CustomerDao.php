<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class CustomerDao extends A_Dao
{
	private static $instance = null;

	private function __construct() {
	    // getInstance() 이용.
	}
	
	static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	function selectByKey($db, $key) {
		 
		$sql =" select imc_idx ,type, code ,name, email, tel1, tel2 ,rate_fee ,calc_period, account_bank, account_no, account_holder, memo, history ,reg_date "
			 ." from icm_mst_customer a "
			 ." where imc_idx = ".$this->quot($db, $key)
		 	 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();

        return $row;
	}

	function selectFirst($db, $wq) {

		$sql =" select imc_idx ,type, code ,name, email, tel1, tel2 ,rate_fee ,calc_period, account_bank, account_no, account_holder, memo, history ,reg_date "
			 ." from icm_mst_customer a "
			 .$wq->getWhereQuery()
			 .$wq->getOrderByQuery()
			 ;
		
		$row = null;

		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row;
	}

	function select($db, $wq) {
	    
	    $sql =" select imc_idx ,type, code ,name, email, tel1, tel2 ,rate_fee ,calc_period, account_bank, account_no, account_holder, memo, history ,reg_date "
	         ." from icm_mst_customer a "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, imc_idx ,type, code ,name, email, tel1, tel2 ,rate_fee ,calc_period, account_bank, account_no, account_holder, memo, history ,reg_date "
			 ."		from icm_mst_customer a "
			 ." 		INNER JOIN ( "
			 ."			select imc_idx as idx from icm_mst_customer a "
						 .$wq->getWhereQuery()
						 .$wq->getOrderByQuery()
			 ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." 		) pg_idx "
			 ." 		on a.imc_idx=pg_idx.idx "
			 ." ) r"
			 ;

        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from icm_mst_customer a "
			 .$wq->getWhereQuery()
			 ;

		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row["cnt"];
	}
	
	function exists($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from icm_mst_customer"
			 .$wq->getWhereQuery()
			 ;

		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();

		if ( $row["cnt"] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	function insert($db, $arrVal) {
	    
	    $sql =" insert into icm_mst_customer(type, code ,name, email, tel1, tel2 ,rate_fee ,calc_period, account_bank, account_no, account_holder, memo ,reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["type"])
	        ."', '".$this->checkMysql($db, $arrVal["code"])
	        ."', '".$this->checkMysql($db, $arrVal["name"])
	        ."', '".$this->checkMysql($db, $arrVal["email"])
	        ."', '".$this->checkMysql($db, $arrVal["tel1"])
	        ."', '".$this->checkMysql($db, $arrVal["tel2"])
	        ."', '".$this->checkMysql($db, $arrVal["rate_fee"])
	        ."', '".$this->checkMysql($db, $arrVal["calc_period"])
	        ."', '".$this->checkMysql($db, $arrVal["account_bank"])
	        ."', '".$this->checkMysql($db, $arrVal["account_no"])
	        ."', '".$this->checkMysql($db, $arrVal["account_holder"])
			."', '".$this->checkMysql($db, $arrVal["memo"])
	        ."', now())"
	            ;
	            
        return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update icm_mst_customer"
	        .$uq->getQuery($db)
	        ." where imc_idx = ".$this->quot($db, $key);
	        
        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    
	    $sql = "update icm_mst_customer set imc_fg_del = 1 where imc_idx = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}	
}
?>