<?php
    include('components/connect-db.php');
    include('modules/functions.php');

    $name = "";
    $content = "";
    $images = "";
    $insights = "";
    $date = "";
    $edit_date = "";
    $author = "";
    $name_categories = array();
    $news_uri = "";

    if(isset($_GET['q'])){
        $prevent_string = clearString($_GET['q']);

        $stmt = $conn->prepare("SELECT * FROM news_aqua WHERE (news_url=:url_str AND status_news=1)");
        $stmt->bindParam(':url_str', $prevent_string);
        $stmt->execute();

        if($stmt->rowCount() == 1){
            if($obj=$stmt->fetch()){
                $name = $obj['name_news'];
                $content = $obj['content_news'];
                $images = $obj['images_news'];
                $insights_uri = $obj['news_uri'];
                $news_uri = $insights_uri;
                $date = $obj['date_news'];
                $edit_date = $obj['edit_date'];
                $author = $obj['author_news'];
                $categories = str_replace(array('[',']'), '', $obj['category_news']);
                
                $stmt = $conn->prepare("SELECT * FROM categories_aqua WHERE (category_uri in ($categories))");
                $stmt->execute();
                
                if($stmt->rowCount() >= 1){
                    $objs=$stmt->fetchAll();
                    foreach($objs AS $obj){
                        array_push($name_categories, $obj['name_category']);
                    }

                    $stmt = $conn->prepare("SELECT * FROM insights_news WHERE news_uri=:uri");
                    $stmt->bindParam(':uri', $insights_uri);
                    $stmt->execute();

                    if($stmt->rowCount() >= 1){
                        if($obj=$stmt->fetch()){
                            $temp_views = intval($obj['views_page'])+1;

                            $stmt = $conn->prepare("UPDATE insights_news SET views_page=:views WHERE news_uri=:uri");
                            $stmt->bindParam(':views', $temp_views);
                            $stmt->bindParam(':uri', $insights_uri);
                            $stmt->execute();
                        }
                    }else{
                        $err = "ERR0001";
                    }
                }else{
                    $err = "ERR0001";
                }
            }else{
                $err = "ERR0002";
            }
        }else{
            $err = "ERR0001";
        }
    }
?>
<!doctype html>
<html lang="pt_BR">
<head>
    <?php
        echo "<script>var startRead = new Date();var news_uri = ".$news_uri.";</script>";
        include('partials/head.php');
    ?>
    <title><?=$name?></title>
</head>
    
<body>
<?php
    include('partials/preload.php');
    include('partials/header.php');
?>

<main>
    <!-- About US Start -->
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Trending Tittle -->
                        <div class="about-right mb-90">
                            <h3>
                                <?php
                                    foreach($name_categories AS $category){
                                        $color = random_color_part();
                                        $textColor = decide_color($color);

                                        echo "<span class='badge' style='background: $color; color: $textColor'>$category</span>";
                                    }
                                ?>                            
                            </h3>
                            <?=$content?>
                            <div class="social-share pt-30">
                                <div class="section-tittle">
                                    <h3 class="mr-20">Share:</h3>
                                    <ul>
                                        <li><a href="#"><img src="assets/img/news/icon-ins.png" alt=""></a></li>
                                        <li><a href="#"><img src="assets/img/news/icon-fb.png" alt=""></a></li>
                                        <li><a href="#"><img src="assets/img/news/icon-tw.png" alt=""></a></li>
                                        <li><a href="#"><img src="assets/img/news/icon-yo.png" alt=""></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!-- Flow Socail -->
                        <div class="single-follow mb-45">
                            <div class="single-box">
                                <div class="follow-us d-flex align-items-center">
                                    <div class="follow-social">
                                        <a href="#"><img src="assets/img/news/icon-fb.png" alt=""></a>
                                    </div>
                                    <div class="follow-count">  
                                        <span>8,045</span>
                                        <p>Fans</p>
                                    </div>
                                </div> 
                                <div class="follow-us d-flex align-items-center">
                                    <div class="follow-social">
                                        <a href="#"><img src="assets/img/news/icon-tw.png" alt=""></a>
                                    </div>
                                    <div class="follow-count">
                                        <span>8,045</span>
                                        <p>Fans</p>
                                    </div>
                                </div>
                                    <div class="follow-us d-flex align-items-center">
                                    <div class="follow-social">
                                        <a href="#"><img src="assets/img/news/icon-ins.png" alt=""></a>
                                    </div>
                                    <div class="follow-count">
                                        <span>8,045</span>
                                        <p>Fans</p>
                                    </div>
                                </div>
                                <div class="follow-us d-flex align-items-center">
                                    <div class="follow-social">
                                        <a href="#"><img src="assets/img/news/icon-yo.png" alt=""></a>
                                    </div>
                                    <div class="follow-count">
                                        <span>8,045</span>
                                        <p>Fans</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- New Poster -->
                        <div class="news-poster d-none d-lg-block">
                            <img src="assets/img/news/news_card.jpg" alt="">
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- About US End -->
</main>
<?php
    include('partials/footer.php');
    include('partials/search.php');
    include('partials/scripts.php');
?>
    
</body>
<script>
  window.addEventListener('beforeunload', setAverageTime)
</script>
</html>