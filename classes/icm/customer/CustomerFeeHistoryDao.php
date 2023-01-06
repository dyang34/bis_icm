<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class CustomerFeeHistoryDao extends A_Dao
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
		 
		$sql =" select imc_idx ,rate_fee, to_date ,reg_date "
			 ." from icm_mst_customer_fee_history a "
			 ." where imcfh_idx = ".$this->quot($db, $key)
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

		$sql =" select imc_idx ,rate_fee, to_date ,reg_date "
			 ." from icm_mst_customer_fee_history a "
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
	    
	    $sql =" select imc_idx ,rate_fee, to_date ,reg_date "
	         ." from icm_mst_customer_fee_history a "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, imc_idx ,rate_fee, to_date ,reg_date "
			 ."		from icm_mst_customer_fee_history a "
			 ." 		INNER JOIN ( "
			 ."			select imcfh_idx as idx from icm_mst_customer_fee_history a "
						 .$wq->getWhereQuery()
						 .$wq->getOrderByQuery()
			 ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." 		) pg_idx "
			 ." 		on a.imcfh_idx=pg_idx.idx "
			 ." ) r"
			 ;

        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from icm_mst_customer_fee_history a "
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
			 ." from icm_mst_customer_fee_history"
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
	    
	    $sql =" insert into icm_mst_customer_fee_history(imc_idx ,rate_fee, to_date ,reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["imc_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["rate_fee"])
	        ."', '".$this->checkMysql($db, $arrVal["to_date"])
	        ."', now())"
	            ;
	            
        return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update icm_mst_customer_fee_history"
	        .$uq->getQuery($db)
	        ." where imcfh_idx = ".$this->quot($db, $key);
	        
        return $db->query($sql);
	}
	
	function delete($db, $key) {
		if ($key) {
			$sql = "delete from icm_mst_customer_fee_history where imcfh_idx = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>