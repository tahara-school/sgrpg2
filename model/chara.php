<?php
require_once('model.php');

/**
 * Charaモデル
 *
 * @version 1.0.0
 * @author  M.Katsube <katsubemakito@gmail.com>
 */
class CharaModel extends Model{
  protected $tableName = 'Chara';  // 対象テーブル

  function getCharaName(){
    $sql  = 'SELECT * FROM Chara';

    $this->query($sql);
    return( $this->fetchAll() );
  }
}
