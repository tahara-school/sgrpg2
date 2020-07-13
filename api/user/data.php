<?php
/**
 * MySQLに接続しデータを取得する
 *
 */

// 以下のコメントを外すと実行時エラーが発生した際にエラー内容が表示される
// ini_set('display_errors', 'On');
// ini_set('error_reporting', E_ALL);

//-------------------------------------------------
// ライブラリ
//-------------------------------------------------
require_once("../util.php");
require_once("../../model/user.php");
require_once("../../model/chara.php");

//-------------------------------------------------
// 引数を受け取る
//-------------------------------------------------
$token = UserModel::getTokenfromQuery();

if( !$token ){
  sendResponse(false, 'Invalid token');
  exit(1);
}

//-------------------------------------------------
// SQLを実行
//-------------------------------------------------
try{
  $user = new UserModel();
  $chara = new CharaModel();
  $uid  = $user->getUserIdByToken($token);
  if( $uid !== false ){
    $record = $user->getRecordById($uid);
    $chara_id = $user->getChara($uid);
    $chara_name = $chara->getCharaName();
  }
  else{
    $record = false;
    $chara_id = false;
    $chara_name = false;
  }
}
catch( PDOException $e ) {
  sendResponse(false, 'Database error: '.$e->getMessage());  // 本来エラーメッセージはサーバ内のログへ保存する(悪意のある人間にヒントを与えない)
  exit(1);
}

//-------------------------------------------------
// 実行結果を返却
//-------------------------------------------------
// データが0件
if( $record === false ){
  sendResponse(false, 'Not Found user');
}
// データを正常に取得
else{

  $result = $record;

  $user_chara = array_filter($chara_name, function($element) use ($chara_id){
    return in_array($element['id'], $chara_id);
  });

  $result['chara'] = $user_chara;

  sendResponse(true, $result);
}
