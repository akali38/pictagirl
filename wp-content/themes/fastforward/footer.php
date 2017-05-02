<?php $monstrotheme = MonstroTheme::getInstance();?>
                    </div>
                </div>
            </section>
            <footer id="footer-container" data-ng-class="{
                                  boxed: (1 == contentLayout.boxedFooter) && ('full' != contentLayout.width )
                              }" class="">
                <div class="row">

                    <div class="large-12 columns" data-ng-if="settings.footer.sidebars[1] || settings.footer.sidebars[2] || settings.footer.sidebars[3] || settings.footer.sidebars[4]">
                        <div id="footerWidgets">
                            <div class="row">
                                <div class="large-3 medium-3 columns" data-ng-include="('sidebar&amp;id=' + settings.footer.sidebars[1]) | partial"></div>
                                <div class="large-3 medium-3 columns" data-ng-include="('sidebar&amp;id=' + settings.footer.sidebars[2]) | partial"></div>
                                <div class="large-3 medium-3 columns" data-ng-include="('sidebar&amp;id=' + settings.footer.sidebars[3]) | partial"></div>
                                <div class="large-3 medium-3 columns" data-ng-include="('sidebar&amp;id=' + settings.footer.sidebars[4]) | partial"></div>
                            </div>
                        </div>
                    </div>

                    <div class="large-12 columns">
                        <div data-ng-include="'menu&amp;location=footer_menu' | partial" onload="ready.footerMenuLoaded = true"></div>
                    </div>

                    <div class="large-6 medium-6 columns" data-ng-if="settings.footer.copyright">
                        <p class="copyright" data-ng-bind-html="settings.footer.copyright"></p>
                    </div>

                    <div class="large-6 medium-6 columns" data-ng-if="(1 == settings.footer.showSocialIcons)">
                        <div class="socialicons fr monstro-toggleable-element" data-ng-if="1 == settings.footer.showSocialIcons"
                             data-ng-include="tdu + '/templates/header/social-icons.html'"></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php
    if ( ! isset( $content_width ) ){
        $content_width = 600;
    }
    ?>

    <noscript data-remove-when-js>
        <?php get_template_part('noscript/index');?>
    </noscript>

    <?php if(isset($monstrotheme->settings->general->googleAnalytics)){?>
        <?php echo $monstrotheme->settings->general->googleAnalytics;?>
    <?php } ?>
    <?php wp_footer();?>
    </body>
</html>