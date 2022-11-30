<?php

/**
 * Gatewayに接続しない簡単なDiscord API Wrapper 
 * Discord API Versionはv10です
 * 
 * @version 1.0.0
 * @author Taka005
 * @link https://github.com/Taka005 
 * 
 */

/**=================================
 *             基本機能
 * =================================
 */

/**
 * @param String $token ログイントークン
 * @param String $version APIのバージョン
 * @return null 
 */
function Login($token){
    $GLOBALS["Token"] = $token;
}

/**
 * @param String $endpoint エンドポイント
 * @param Array $data POSTする連想配列
 * @return String APIレスポンス
 */
function DataPost($endpoint,$data){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,"https://discord.com/api/v10/".$endpoint); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        "Authorization: Bot ".$GLOBALS["Token"],
        "User-Agent: DiscordBot (https://github.com/Taka005, 10)",
        "Content-type: application/json"
    ));
    $res = json_decode(curl_exec($ch));
    curl_close($ch);
    
    return $res;
}

/**
 * @param String $endpoint エンドポイント
 * @return String APIレスポンス
 */
function DataGet($endpoint){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,"https://discord.com/api/v10/".$endpoint); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        "Authorization: Bot ".$GLOBALS["Token"],
        "User-Agent: DiscordBot (https://github.com/Taka005, 10)",
        "Content-type: application/json"
    ));
    $res = json_decode(curl_exec($ch));
    curl_close($ch);
    
    return $res;
}

/**=================================
 *             ユーザー操作
 * =================================
 */

/**
 * @param String $id ユーザーID
 * @return String ユーザーオブジェクト
 */
function getUser($id){
    $res = DataGet("users/".$id);
    if(isset($res["message"])) return false;
    return $res;
}

/**
 * @param String $id ユーザーID
 * @param Array $message
 * @return String ステータスコード
 */
function createDM($id,$message){
    $res = DataPost("users/@me/channels",array(
        "recipient_id"=>$id
    ));
    if(isset($res["message"])) return false;

    $res = DataPost("channels/".$res["id"]."/messages",$message);
    if(isset($res["message"])) return false;
    return $res;
}
?>