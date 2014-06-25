<?php

    function generateHTMLHeader(){
    echo <<<END
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title id="title">Questionnaire</title>

        <script type="text/javascript" src="dist/leaflet.js"></script>
        <script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
        <script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="lightbox/js/jquery.lightbox-0.5.js"></script>
        <script type="text/javascript" src="js/jquery.lightbox_me.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        
        <!--Analytics-->
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40299595-2', '37.187.242.99');
  ga('send', 'pageview');

</script>
   
        <!-- NO STYLESHEET <link rel="stylesheet" type="text/css" href="styles/styles.css" />-->
        
        <!-- Lets try to bootstrap that shit-->
        <link rel="stylesheet" type="text/css" href="styles/bootstrap.min.css" />
        
        <link rel="stylesheet" type="text/css" href="dist/leaflet.css" />
        <link rel="stylesheet" type="text/css" href="media/css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="lightbox/css/jquery.lightbox-0.5.css" media="screen" />

    </head>
    <body>
END;
    }


    function generateHTMLFooter(){
    echo <<<END
    </body>
    </html>
END;
}


    function getCurrentPage(){
        $docpages = simplexml_load_file('../../pages.xml');//on recharge à chaque fois mais rapide
        $pages=  $docpages->page;
        //load après action
        if(isset($_POST['page'])) {foreach($pages as $page){if($page->name==$_POST['page']) return $page;}}
        //load si page 
        //
        //if(isset($_SESSION['currentPage'])){foreach($pages as $page){if($page->name==$_SESSION['currentPage']) return $page;}}
        //default
        return CurrentPage::acceuil();
    }



    function generateContent(){
        $docpages = simplexml_load_file('pages.xml');
        $pages=  $docpages->page;

        
        echo "<header id='entete'>".PHP_EOL;
        echo "<h1>Questionnaire</h1>";
        echo "<h2></h2>";

        //generation du menu
        echo "<!--menu-->".PHP_EOL."<ul>".PHP_EOL;
        foreach($pages as $page){
           echo "<li><a href=\"#\" id=\"$page->name\" class=\"menulink\">$page->titlemenu</a></li>".PHP_EOL;
        }


echo <<<END
    </ul></header>

        <div id="bandeHaut"></div>

       <div id="loginarea"></div>
       <nav id="contenu"></nav>
       <div id="footer"></div>
END;
       }


?>

