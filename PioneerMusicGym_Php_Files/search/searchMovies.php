<?php
include("../config.php");

$db = new DB_Connect();
$con = $db->connect();

$movieName = $_POST["movieName"];
$lowerLimit = $_POST["lowerLimit"];
$upperLimit = $_POST["upperLimit"];

$sql_query = "SELECT mov.id AS movie_id, 
                mov.movie_name,
                mov.year,

                (SELECT COUNT(*) 
                    FROM song_master sm 
                    WHERE sm.movie_id = mov.id)
                    AS number_of_songs

                FROM movies mov
                WHERE mov.movie_name LIKE '%".$movieName."%'
                LIMIT $lowerLimit, $upperLimit";

$res = mysqli_query($con, $sql_query);

$result = array('status'=>false);

while($row = mysqli_fetch_array($res)){
    $result['status'] = true;
    $result['movies'][] = array('0'=>$row["movie_id"],
                            '1'=>$row["movie_name"],
                            '2'=>$row["year"],
                            '3'=>$row["number_of_songs"],
                        );
}

echo json_encode([$result]);

mysqli_close($con);
?>