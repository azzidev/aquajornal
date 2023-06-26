<?php
    include('connect-db.php');

    $time = $_POST['time'];
    $uri = $_POST['uri'];

    $stmt = $conn->prepare("UPDATE insights_news SET average_viewing_time_news=(average_viewing_time_news+:average) WHERE news_uri=:uri");
    $stmt->bindParam(':average', $time);
    $stmt->bindParam(':uri', $uri);
    echo $stmt->execute();
?>