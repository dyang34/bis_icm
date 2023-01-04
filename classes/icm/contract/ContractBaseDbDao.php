<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class ContractBaseDbDao extends A_Dao
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
		 
	    $sql =" select icbd_idx,product_title,plan_title,policy_no,apply_no,plan_code,name,apply_date,customer_name,insurance_amt,amt_type,start_date,end_date,cnt_member,add_info1,add_info2,grp_code,imc_idx,deposit_amt,advance_amt,pay_type,fg_del,reg_date "
	        ." from icm_contract_base_db "
			." where icbd_idx = ".$this->quot($db, $key)
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

		$sql =" select icbd_idx,product_title,plan_title,policy_no,apply_no,plan_code,name,apply_date,customer_name,insurance_amt,amt_type,start_date,end_date,cnt_member,add_info1,add_info2,grp_code,imc_idx,deposit_amt,advance_amt,pay_type,fg_del,reg_date "
			." from icm_contract_base_db"
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
	    
		$sql =" select icbd_idx,product_title,plan_title,policy_no,apply_no,plan_code,name,apply_date,customer_name,insurance_amt,amt_type,start_date,end_date,cnt_member,add_info1,add_info2,grp_code,imc_idx,deposit_amt,advance_amt,pay_type,fg_del,reg_date "
			." from icm_contract_base_db a "
			.$wq->getWhereQuery()
			.$wq->getOrderByQuery()
		;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, icbd_idx,product_title,plan_title,policy_no,apply_no,plan_code,name,apply_date,customer_name,insurance_amt,amt_type,start_date,end_date,cnt_member,add_info1,add_info2,grp_code,imc_idx,deposit_amt,advance_amt,pay_type,fg_del,reg_date "
			." 		from icm_contract_base_db a "
			." 		INNER JOIN ( "
	        ."			select icbd_idx from icm_contract_base_db a "
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
		    ." from icm_contract_base_db a "
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
		    ." from icm_contract_base_db a "
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
		
        $sql ="call sp_icm_contract_base_db_ins('".$this->checkMysql($db, $arrVal["customer_name"])
            ."', '".$this->checkMysql($db, $arrVal["add_info1"])
            ."', '".$this->checkMysql($db, $arrVal["add_info2"])
            ."', '".$this->checkMysql($db, $arrVal["product_title"])
            ."', '".$this->checkMysql($db, $arrVal["apply_no"])
            ."', '".$this->checkMysql($db, $arrVal["policy_no"])
            ."', '".$this->checkMysql($db, $arrVal["apply_date"])
            ."', '".$this->checkMysql($db, $arrVal["start_date"])
            ."', '".$this->checkMysql($db, $arrVal["end_date"])
            ."', '".$this->checkMysql($db, $arrVal["amt_type"])
            ."', '".$this->checkMysql($db, $arrVal["plan_title"])
            ."', '".$this->checkMysql($db, $arrVal["name"])
            ."', '".$this->checkMysql($db, $arrVal["cnt_member"])
            ."', '".$this->checkMysql($db, $arrVal["insurance_amt"])
            ."', '".$this->checkMysql($db, $arrVal["deposit_amt"])
            ."', '".$this->checkMysql($db, $arrVal["advance_amt"])
            ."', '".$this->checkMysql($db, $arrVal["pay_type"])
            ."', '".$this->checkMysql($db, $arrVal["grp_code"])
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
	
	function update($db, $uq, $key) {
	    
	    $sql =" update icm_contract_base_db"
	        .$uq->getQuery($db)
	        ." where icbd_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}

	function delete($db, $key) {
	    if ($key) {
    	    $sql = "update icm_contract_base_db set fg_del=1 where icbd_idx = ".$this->quot($db, $key);
    	    return $db->query($sql);
	    }
	}	

	function deleteByGrpCode($db, $grp_code) {
	    if ($grp_code) {
    	    $sql = "update icm_contract_base_db set fg_del=1 where company_type=4 and grp_code = ".$this->quot($db, $grp_code);
    	    return $db->query($sql);
	    }
	}	
}
?>