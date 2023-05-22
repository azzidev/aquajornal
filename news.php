<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
                $insights = $obj['insights_news'];
                $date = $obj['date_news'];
                $edit_date = $obj['edit_date'];
                $author = $obj['author_news'];
                $categories = str_replace(array('[',']'), '', $obj['category_news']);
                
                $stmt = $conn->prepare("SELECT * FROM categories_aqua WHERE (categories_uri in ($categories))");
                $stmt->execute();
                
                if($stmt->rowCount() == 1){
                    while($obj=$stmt->fetch()){
                        array_push($name_categories, $obj['name_category']);
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
        include('partials/head.php');
    ?>
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
</html>