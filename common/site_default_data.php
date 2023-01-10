<?php
$arrSystemMenu = [
    [  "menu_no0"=>0, "title"=>"계약관리", "url"=>"/branch.php?p_menu_no0=0", "grade_min"=>1, "grade"=>["0"=>"권한 없음", "1"=>"조회 권한","5"=>"조회/작업 권한", "10"=>"마스터 권한"]
        , "menu1"=>[
            [   "menu_no1"=>0, "title"=>"계약 통계", "url"=>"", "grade_min"=>1
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"계약 내역", "url"=>"/admin/contract/contract_list.php", "grade_min"=>1]
                ]
            ]
            ,[   "menu_no1"=>1, "title"=>"계약 관리", "url"=>"", "grade_min"=>5
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"계약 업로드", "url"=>"/admin/contract/upload_contract_data.php", "grade_min"=>5]
                    ,["menu_no2"=>1, "title"=>"계약 작업", "url"=>"/admin/contract/contract_adm_list.php", "grade_min"=>5]
                ]
            ]
            ,[   "menu_no1"=>2, "title"=>"기초 정보", "url"=>"", "grade_min"=>5
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"거래처 관리", "url"=>"/admin/contract/customer_list.php", "grade_min"=>5]
                ]
            ]
        ]
    ]

    ,[  "menu_no0"=>1, "title"=>"기업보험", "url"=>"/branch.php?p_menu_no0=1", "grade_min"=>1, "grade"=>["0"=>"권한 없음", "1"=>"조회 권한","5"=>"조회/작업 권한", "10"=>"마스터 권한"]
        , "menu1"=>[
            [   "menu_no1"=>0, "title"=>"계약 통계", "url"=>"", "grade_min"=>1
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"계약 리스트", "url"=>"/admin/corporation/contract_list.php", "grade_min"=>1]
                ]
            ]
            ,[   "menu_no1"=>2, "title"=>"기초 정보", "url"=>"", "grade_min"=>5
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"거래처 관리", "url"=>"/admin/contract/customer_list.php", "grade_min"=>5]
                ]
            ]
        ]
    ]

    ,[  "menu_no0"=>2, "title"=>"장기여행자보험", "url"=>"/branch.php?p_menu_no0=2", "grade_min"=>1, "grade"=>["0"=>"권한 없음", "1"=>"조회 권한","5"=>"조회/작업 권한", "10"=>"마스터 권한"]
        , "menu1"=>[
            [   "menu_no1"=>0, "title"=>"계약 통계", "url"=>"", "grade_min"=>1
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"계약 리스트", "url"=>"/admin/long_trip/contract_list.php", "grade_min"=>1]
                ]
            ]
            ,[   "menu_no1"=>2, "title"=>"기초 정보", "url"=>"", "grade_min"=>5
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"거래처 관리", "url"=>"/admin/contract/customer_list.php", "grade_min"=>5]
                ]
            ]
        ]
    ]

    ,[  "menu_no0"=>9, "title"=>"시스템 관리", "url"=>"/branch.php?p_menu_no0=9", "grade_min"=>10, "grade"=>["0"=>"권한 없음", "10"=>"마스터 권한"]
        , "menu1"=>[
            [   "menu_no1"=>0, "title"=>"회원 관리", "url"=>"", "grade_min"=>10
                ,"menu2"=>[
                    ["menu_no2"=>0, "title"=>"회원 리스트", "url"=>"/admin/system/adm_mem_list.php", "grade_min"=>10]
                ]
            ]
        ]
    ]
];

$arrCustomerCalcPeriod = [
    "D"=>"일 정산"
    ,"M"=>"월 정산"
];

$arrCustomerType = [
    "A"=>"단기여행자보험"
    ,"B"=>"장기여행자보험"
    ,"C"=>"기업보험"
];

$arrInsuranceCompany = [
    "1"=>"CHUBB"
    ,"2"=>"메리츠화재"
    ,"3"=>"MG손보"
    ,"4"=>"DB손보"
    ,"5"=>"삼성화재"
];

$arrInsuranceCompanyUpload = [
    "1"=>"CHUBB 파일(.xlsx)"
    ,"2"=>"메리츠화재 파일(.xlsx)"
    ,"4"=>"DB손보 파일(.xlsx)"
];

$arrAmtType = ["배서", "신계약", "환급"];
?>