<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class ContractDao extends A_Dao
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
	    $sql =" select ic_idx,company_type,base_idx,calc_period,insurance_amt,rate_fee,deposit_amt,advance_amt,pay_type,imc_idx,customer_code,customer_name,product_title,plan_title,policy_no,apply_no,plan_code,name,id_no,apply_date,seller_id,amt_type,start_date,end_date,cnt_member,trip_place,proc_date,add_info1,add_info2,grp_code,reg_date, customer_name_org "
	        ." from icm_contract "
			." where ic_idx = ".$this->quot($db, $key)
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
		$sql =" select ic_idx,company_type,base_idx,calc_period,insurance_amt,rate_fee,deposit_amt,advance_amt,pay_type,imc_idx,customer_code,customer_name,product_title,plan_title,policy_no,apply_no,plan_code,name,id_no,apply_date,seller_id,amt_type,start_date,end_date,cnt_member,trip_place,proc_date,add_info1,add_info2,grp_code,reg_date, customer_name_org "
			." from icm_contract"
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
		$sql =" select ic_idx,company_type,base_idx,calc_period,insurance_amt,rate_fee,deposit_amt,advance_amt,pay_type,imc_idx,customer_code,customer_name,product_title,plan_title,policy_no,apply_no,plan_code,name,id_no,apply_date,seller_id,amt_type,start_date,end_date,cnt_member,trip_place,proc_date,add_info1,add_info2,grp_code,reg_date, customer_name_org "
			." from icm_contract a "
			.$wq->getWhereQuery()
			.$wq->getOrderByQuery()
		;
		
        return $db->query($sql);
	}
	
	function selectGroupCode($db, $wq) {
		$sql =" select company_type, grp_code, min(reg_date) reg_date, count(*) "
			." from icm_contract a "
			.$wq->getWhereQuery()
			." group by company_type, grp_code "
			.$wq->getOrderByQuery()
		;
		
        return $db->query($sql);
	}

	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, ic_idx,company_type,base_idx,calc_period,insurance_amt,rate_fee,deposit_amt,advance_amt,pay_type,imc_idx,customer_code,customer_name,product_title,plan_title,policy_no,apply_no,plan_code,name,id_no,apply_date,seller_id,amt_type,start_date,end_date,cnt_member,trip_place,proc_date,add_info1,add_info2,grp_code,reg_date, customer_name_org "
			." 		from icm_contract a "
			." 		INNER JOIN ( "
	        ."			select ic_idx as idx from icm_contract a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.ic_idx=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}

	function selectCount($db, $wq) {
		$sql =" select count(*) cnt"
		    ." from icm_contract a "
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
		    ." from icm_contract a "
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

	function update($db, $uq, $key) {
	
	    $sql =" update icm_contract"
	        .$uq->getQuery($db)
	        ." where ic_idx = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}

	function delete($db, $key) {
	    if ($key) {
    	    $sql = "update icm_contract set fg_del=1 where ic_idx = ".$this->quot($db, $key);
    	    return $db->query($sql);
	    }
	}	

	function deleteByGrpCode($db, $grp_code) {
	    if ($grp_code) {
    	    $sql = "update icm_contract set fg_del=1 where grp_code = ".$this->quot($db, $grp_code);
    	    return $db->query($sql);
	    }
	}	
}
?>