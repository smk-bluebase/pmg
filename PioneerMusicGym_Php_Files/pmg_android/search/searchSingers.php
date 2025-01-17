<?php
include("../config.php");

$db = new DB_Connect();
$con = $db->connect();

$singerName = $_POST["singerName"];
$index = $_POST["index"];
$limit = $_POST["limit"];

$sql_query = "SELECT sng.id AS singer_id,
			    sng.singer_name,

                (SELECT COUNT(*) 
                    FROM movie_singer mov_sng
                    INNER JOIN song_master sm ON mov_sng.movie_id = sm.movie_id
                    WHERE mov_sng.singer_id = sng.id) 
                    AS number_of_songs,

                (SELECT COUNT(*) 
                    FROM movie_singer mov_sng 
                    WHERE mov_sng.singer_id = sng.id) 
                    AS number_of_movies
                    
                FROM singers sng
                WHERE sng.singer_name LIKE '%".$singerName."%'
                LIMIT $index, $limit";

$res = mysqli_query($con, $sql_query);

$result = array('status'=>false);

while($row = mysqli_fetch_array($res)){
    $result['status'] = true;
    $result['singers'][] = array('0'=>$row['singer_id'],
                            '1'=>$row['singer_name'],
                            '2'=>$row['number_of_songs'],
                            '3'=>$row['number_of_movies']
                        );
}

echo json_encode([$result]);

mysqli_close($con);
?>