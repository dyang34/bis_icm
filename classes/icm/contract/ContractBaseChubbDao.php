<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class ContractBaseChubbDao extends A_Dao
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
		 
	    $sql =" select icbc_idx ,product_title ,plan_title ,policy_no ,apply_no ,plan_code ,name ,id_no ,apply_date ,agent_id ,agent_name ,customer_name ,seller_id ,insurance_amt ,amt_type ,pay_method ,start_date ,end_date ,cnt_member ,trip_place ,proc_date ,contract_type ,fg_mobile ,add_info1 ,add_info2 ,grp_code ,reg_date "
	        ." from icm_contract_base_chubb "
			." where icbc_idx = ".$this->quot($db, $key)
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

		$sql =" select icbc_idx ,product_title ,plan_title ,policy_no ,apply_no ,plan_code ,name ,id_no ,apply_date ,agent_id ,agent_name ,customer_name ,seller_id ,insurance_amt ,amt_type ,pay_method ,start_date ,end_date ,cnt_member ,trip_place ,proc_date ,contract_type ,fg_mobile ,add_info1 ,add_info2 ,grp_code ,reg_date "
			." from icm_contract_base_chubb"
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
	    
		$sql =" select icbc_idx ,product_title ,plan_title ,policy_no ,apply_no ,plan_code ,name ,id_no ,apply_date ,agent_id ,agent_name ,customer_name ,seller_id ,insurance_amt ,amt_type ,pay_method ,start_date ,end_date ,cnt_member ,trip_place ,proc_date ,contract_type ,fg_mobile ,add_info1 ,add_info2 ,grp_code ,reg_date "
			." from icm_contract_base_chubb a "
			.$wq->getWhereQuery()
			.$wq->getOrderByQuery()
		;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, icbc_idx ,product_title ,plan_title ,policy_no ,apply_no ,plan_code ,name ,id_no ,apply_date ,agent_id ,agent_name ,customer_name ,seller_id ,insurance_amt ,amt_type ,pay_method ,start_date ,end_date ,cnt_member ,trip_place ,proc_date ,contract_type ,fg_mobile ,add_info1 ,add_info2 ,grp_code ,reg_date "
			." 		from icm_contract_base_chubb a "
			." 		INNER JOIN ( "
	        ."			select icbc_idx from icm_contract_base_chubb a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.idx=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}

	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
		    ." from icm_contract_base_chubb a "
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
		    ." from icm_contract_base_chubb a "
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
		
        $sql ="call sp_icm_contract_base_chubb_ins('".$this->checkMysql($db, $arrVal["product_title"])
            ."', '".$this->checkMysql($db, $arrVal["plan_title"])
            ."', '".$this->checkMysql($db, $arrVal["policy_no"])
            ."', '".$this->checkMysql($db, $arrVal["apply_no"])
            ."', '".$this->checkMysql($db, $arrVal["plan_code"])
            ."', '".$this->checkMysql($db, $arrVal["name"])
            ."', '".$this->checkMysql($db, $arrVal["id_no"])
            ."', '".$this->checkMysql($db, $arrVal["apply_date"])
            ."', '".$this->checkMysql($db, $arrVal["agent_id"])
            ."', '".$this->checkMysql($db, $arrVal["agent_name"])
            ."', '".$this->checkMysql($db, $arrVal["customer_name"])
            ."', '".$this->checkMysql($db, $arrVal["seller_id"])
            ."', '".$this->checkMysql($db, $arrVal["insurance_amt"])
            ."', '".$this->checkMysql($db, $arrVal["amt_type"])
            ."', '".$this->checkMysql($db, $arrVal["pay_method"])
            ."', '".$this->checkMysql($db, $arrVal["start_date"])
            ."', '".$this->checkMysql($db, $arrVal["end_date"])
            ."', '".$this->checkMysql($db, $arrVal["cnt_member"])
            ."', '".$this->checkMysql($db, $arrVal["trip_place"])
            ."', '".$this->checkMysql($db, $arrVal["proc_date"])
            ."', '".$this->checkMysql($db, $arrVal["contract_type"])
            ."', '".$this->checkMysql($db, $arrVal["fg_mobile"])
            ."', '".$this->checkMysql($db, $arrVal["add_info1"])
            ."', '".$this->checkMysql($db, $arrVal["add_info2"])
            ."', '".$this->checkMysql($db, $arrVal["grp_code"])
            ."', '".$this->checkMysql($db, $arrVal["deposit_amt"])
            ."', '".$this->checkMysql($db, $arrVal["advance_amt"])
            ."', '".$this->checkMysql($db, $arrVal["pay_type"])
            ."')"
        ;

		$row = array();
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            @ $result->free();
        }

        return $row;
	}
	
	function insert_check($db, $arrVal) {
	    
	    $sql ="call sp_icm_contract_base_chubb_chk('".$this->checkMysql($db, $arrVal["company_type"])."', '".$this->checkMysql($db, $arrVal["grp_code"])."')"
        ;
	        
        $row = array();
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            @ $result->free();
        }
        
        return $row;
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update icm_contract_base_chubb"
	        .$uq->getQuery($db)
	        ." where icbc_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}

	function delete($db, $key) {
	    if ($key) {
    	    $sql = "update icm_contract_base_chubb set fg_del=1 where icbc_idx = ".$this->quot($db, $key);
    	    return $db->query($sql);
	    }
	}	

	function deleteByGrpCode($db, $grp_code) {
	    if ($grp_code) {
    	    $sql = "update icm_contract_base_chubb set fg_del=1 where company_type=1 and grp_code = ".$this->quot($db, $grp_code);
    	    return $db->query($sql);
	    }
	}	
}
?>