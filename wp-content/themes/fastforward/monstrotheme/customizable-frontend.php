<?php
add_action('wp_footer', 'monstroInjectCustomizableFrontendBox');
function monstroInjectCustomizableFrontendBox(){
    ?>
    <div class="bootstrap-inside" id="customizable-frontend-hint" data-ng-show="state.customizer.showCustomizableItems" data-ng-include="'<?php echo get_template_directory_uri();?>/templates/customizable-frontend/hint-overlay.html'"></div>
    <?php
}