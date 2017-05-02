<html>
    <head>
        <title>Flickr iframe</title>
        <style>
                /* Widget Flickr */
            html, body{padding:0; margin:0}
            .flickr_badge_image{float: left; margin-right: 5%; width: 30%;}
            .flickr_badge_image img{width: 100%; height:auto;}
            .flickr_badge_image:nth-of-type(3n) {clear: right;margin-right: 0%;margin-bottom: 3%;width: 30%;}
        </style>
    </head>
    <?php
        extract($_GET);
    ?>
    <body>
        <base target="_blank" />
        <div>
            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $showing; ?>&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>
        </div>
    </body>
</html>