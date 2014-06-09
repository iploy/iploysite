<div class="footer" align="center" >
    <div class="container" >
    
        <div class="column_contain" >
            <div class="column" >
                <h4>Graduates</h4>
                <ul>
                    <li><a href="graduate_signup.php" >Register as a Graduate</a></li>
                    <li><a href="advice/" >Graduate Advice</a></li>
                    <li><a href="login.php" >Login</a></li>
                </ul>
            </div>
            <div class="column" >
                <h4>Employers</h4>
                <ul>
                    <li><a href="employer_signup.php" >Register as an Employer</a></li>
                    <li><a href="search.php" >Search Graduates</a></li>
                    <li><a href="login.php" >Login</a></li>
                </ul>
            </div>
            <div class="column" >
                <h4>iPloy</h4>
                <ul>
                    <li><a href="iploy_terms_and_conditions.php" >Terms &amp; Conditions</a></li>
                    <li><a href="iploy_privacy_policy.php" >Privacy Policy</a></li>
                    <li><a href="site_map.php" >Site Map</a></li>
                    <!--
                    <li><a href="site_map.php" >About iPloy</a></li>
                    <li><a href="contact_us.php" >Contact <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
                    -->
                </ul>
            </div>
            <div class="column" >
                <h4><a href="blog/" >Blog Latest</a></h4>
                <?php
                    // grab the graduate advice RSS feed
                    include_once('_system/classes/rss_feed.php') ;
                    $rss = new rssFeed ;
                    $rss->setUrl('http://www.iploy.co.uk/blog/?feed=rss2') ;
                    $rss->setItems(3) ;
                    $rss = $rss->getFeed() ;
                    if($rss['success']==true){
                        if(sizeof($rss['data']['items'])>0){
                            echo '<ul>'."\n" ;
                            foreach($rss['data']['items'] as $item){
                                $titleSplit = explode("(",$item['title']) ;
                                $title = trim(str_replace("...","",$titleSplit[0])) ;
                                $titleSplit = explode("view",$titleSplit[1]) ;
                                $views = $titleSplit[0] ;
                                echo '<li><a href="'.$item['link'].'" class="ellipsis" >'.$title.'</a></li>'."\n" ;
                            }
                            echo '</ul>'."\n" ;
                        }
                    } else {
                        echo '<p class="error" >'.$rss['status'].'</p>'."\n" ;
                    }
                ?>
            </div>
        </div>
    
        <div class="bottom" >
            <p align="center" >&copy; <?php echo date('Y').' '.$_SESSION['APP_CLIENT_NAME'] ; ?> Ltd</p>
        </div>
    
    </div>
</div>

