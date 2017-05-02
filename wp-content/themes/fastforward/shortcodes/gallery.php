<?php
function monstroGalleryShortCode($content){
    get_template_part('vendors/aq_resizer');
    $monstrotheme = MonstroTheme::getInstance();
    $imageSize = $monstrotheme->settings->imageSizes->gallery->height;
    if(!empty($content['ids'])){
        $imagesId = explode(",", $content['ids']);
    }else{
        $attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );
        if(!empty($attachments)){
            foreach($attachments as $id=>$content){
                $image[] = $id;
            }
            $imagesId  = $image;
        }else{
            $imagesId = null;
        }
    }
    if(!empty($imagesId)) {
        ?>
        <ul class="owl-carousel">
            <?php
            foreach ($imagesId as $value) {
                $image_url = wp_get_attachment_image_src($value, 'full');
                $image = aq_resize($image_url[0], '', $imageSize, true, true, true);
                $featimgFileType = wp_check_filetype($image);
                $animation = false;
                if('gif' == $featimgFileType['ext']){
                    $animation = $image_url[0];
                }
                echo '<li>
                        <span class="linkonDivece"></span>
                        <a href="' . $image_url[0] . '"' . ($animation ? ' class="monstro-gif"': '') . '>
                            <div class="icon_prev"></div>' .
                            ($animation ? '<img  data-src="' . $image_url[0] . '" class="animation owl-lazy" />' : '')
                            . '<img data-src="' . $image . '" class="owl-lazy" />
                        </a>
                    </li>';
            }
            ?>
        </ul>
        <script type="text/javascript">
              jQuery('.owl-carousel').owlCarousel({
                    items:3,
                    lazyLoad:true,
                    autoWidth:true,
                    loop:true,
                    center:true,
                    margin:2,
                    dots:false,
                    nav:true,
                    responsive: {
                        480: {
                            items: 1
                        },
                        678: {
                            items: 2
                        },
                        960: {
                            items: 3
                        }
                    }
                });
              var displayDimensions = window.innerWidth;
              if(displayDimensions > 1024){
                  var container = jQuery('ul.owl-carousel'),
                      old = jQuery('li', container),
                      api = jQuery('ul.owl-carousel li a').iLightBox(
                          {
                              skin: 'metro-white'
                          }
                      ),
                      pages = 4;
              }
        </script>
    <?php
    }else{
        echo __("<p>Please attach some images to this gallery</p>","monstrotheme");
    }
}
add_shortcode('gallery', 'monstroGalleryShortCode');
?>
