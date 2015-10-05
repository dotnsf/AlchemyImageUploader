<?php
require( "./credentials.php" );

// アップロードファイルを取得
$name = $_FILES["file"]["name"]; // ファイル名
$mimetype = $_FILES["file"]["type"]; // Content-Type
$filesize = $_FILES["file"]["size"]; // ファイルサイズ
$tmpname = $_FILES["file"]["tmp_name"]; // 一時ファイル名（ここに実体がある）

if( $tmpname ){
  try{
    // アップロードファイル（画像）のデータを取得
    $fp = fopen( $tmpname, "rb" );
    $imgdata = fread( $fp, $filesize );
    fclose( $fp );

    $dbh = new PDO( $dsn, $username, $password );
    if( $dbh != null ){
      // imgs テーブルに画像を格納
      $sql = "insert into imgs(img) values(:img)";
      $stmt = $dbh->prepare( $sql );
      $stmt->bindParam( ':img', $imgdata, PDO::PARAM_STR );

      $r = $stmt->execute(); //. 成功すると1
      if( $r == 1 ){
        // 格納した画像の ID を取得する
        $sql = "select last_insert_id() as img_id from imgs";
        $stmt = $dbh->prepare( $sql );
        $stmt->execute();
        if( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
          $img_id = $result['img_id'];
          
          // imetas テーブルに情報を格納
          $created = date( "Y/m/d H:i:s" );
          $sql = "insert into imetas(img_id,filename,created) values(:img_id,:filename,:created)";
          $stmt = $dbh->prepare( $sql );
          $stmt->bindParam( ':img_id', $img_id, PDO::PARAM_INT );
          $stmt->bindParam( ':filename', $name, PDO::PARAM_STR );
          $stmt->bindParam( ':created', $created, PDO::PARAM_STR );
          $r = $stmt->execute(); //. 成功すると1
          if( $r == 1 ){
            // 格納した画像の ID を取得する
            $sql = "select last_insert_id() as imeta_id from imetas";
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
            if( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
              $imeta_id = $result['imeta_id'];
          
              // Alchemy API
              $url = 'http://' . $_SERVER['SERVER_NAME'] . '/loadimg.php?img_id=' . $img_id;
              $apiurl = 'http://access.alchemyapi.com/calls/url/URLGetRankedImageKeywords?apikey=' . $apikey . '&outputMode=json&url=' . urlencode( $url );
              $text = file_get_contents( $apiurl );

              $json = json_decode( $text );
              $imageKeywords = $json->imageKeywords;
              if( count( $imageKeywords ) ){
                for( $i = 0; $i < count( $imageKeywords ); $i ++ ){
                  $imageKeyword = $imageKeywords[$i];
                  $tag = $imageKeyword->text;
                  $score = $imageKeyword->score;
                  
                  //. tags テーブルに情報を格納
                  $sql = "insert into tags(imeta_id,tag,score) values(:imeta_id,:tag,:score)";
                  $stmt = $dbh->prepare( $sql );
                  $stmt->bindParam( ':imeta_id', $imeta_id, PDO::PARAM_INT );
                  $stmt->bindParam( ':tag', $tag, PDO::PARAM_STR );
                  $stmt->bindParam( ':score', $score, PDO::PARAM_STR );
                  $stmt->execute();
                }
              }
            }
          }
        }
      }
      
      $dbh = null;
      print( 'r = ' . $r );
    }
  }catch( PDOException $e ){
    print( 'Error: ' . $e->getMessage() );
    die();
  }
}else{
  print( 'No tmpname' );
}
?>