<?php
class HiworksApiUtil
{
    static $OfficeToken = "aa54d6b74131dc08d0b9d3febba6f2be";

    static function sendAlarm($arrUser, $title, $message, $img, $link, $mlink) {

        $uri = "https://api.hiworks.com/office/v2/notify";

        if(empty($arrUser)) {
            return array("code"=>"001", "message"=>"전송 대상 ID를 입력해 주세요.");
        }

        if(empty($message)) {
            return array("code"=>"002", "message"=>"전송할 메시지를 입력해 주세요.");
        }

        $arrPost = [
            "user_list" => $arrUser
            ,"message" => $message
            ,"solution_name" => $title
            ,"solution_image_url" => $img
            ,"link" => $link
            ,"mlink" => $mlink
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $uri,
//            CURLOPT_HEADER => true,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept:application/json'
                ,'Content-Type: application/json; charset=utf-8'
                ,'Authorization: Bearer '.HiworksApiUtil::$OfficeToken
            ),
            CURLOPT_POSTFIELDS => json_encode($arrPost),
        ));

        $response = curl_exec($curl);

        return json_decode($response);
    }

    // HiworksApiUtil::sendMail("bis", 'dyang34@bis.co.kr', "dyang34@bis.co.kr, dyang34@naver.com","", "테스트", "테스트 입니다");
    static function sendMail($send_id, $to, $cc, $bcc, $subject, $content, $save_sent_mail="N") {

        $uri = "https://api.hiworks.com/office/v2/webmail/sendMail";

        if(empty($send_id)) {
            return array("code"=>"001", "message"=>"보내는 사람 하이웍스 ID를 입력해 주세요.");
        }

        if(empty($to)) {
            return array("code"=>"002", "message"=>"받는 사람을 입력해 주세요.");
        }

        if(empty($subject)) {
            return array("code"=>"003", "message"=>"제목을 입력해 주세요.");
        }

        if(empty($content)) {
            return array("code"=>"004", "message"=>"내용을 입력해 주세요.");
        }

        $arrPost = [
            "user_id" => $send_id
            ,"to" => $to
            ,"cc" => $cc
            ,"bcc" => $bcc
            ,"subject" => $subject
            ,"content" => $content
            ,"save_sent_mail" => $save_sent_mail
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $uri,
//            CURLOPT_HEADER => true,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept:application/json'
                ,'Content-Type: application/json; charset=utf-8'
                ,'Authorization: Bearer '.HiworksApiUtil::$OfficeToken
            ),
            CURLOPT_POSTFIELDS => json_encode($arrPost),
        ));

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
?>