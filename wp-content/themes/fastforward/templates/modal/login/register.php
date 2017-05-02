<div class="monstro-modal" ng-controller="LoginCtrl" ng-submit="register()" ng-keydown="closeLoginModalEsc($event)">
    <div class="modal-content">
        <div class="login">
            <a href="javascript:void(0);" class="icon-close fr" ng-click="closeLoginModal()"></a>
            <div class="login-image-icon">&nbsp;</div>
            <form name="loginForm" id="monstro-login" method="post" class="registerform">
                <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                <div data-alert class="alert-box warning" ng-show="status.error" ng-bind-html="status.error"></div>
                <fieldset>
                    <div class="login_inside">
                        <?php echo apply_filters( 'login_form_top', '', array() );?>
                        <p>
                            <input ng-model="log" name="log" type="text" tabindex="1" autofocus placeholder="{{'Username'|translate}}">
                        </p>
                        <p>
                            <input ng-model="email" name="email" type="text" tabindex="2" placeholder="{{'E-mail'|translate}}">
                        </p>
                        <?php echo apply_filters( 'login_form_middle', '', array() );?>
                        <?php do_action('login_form');?>
                        <p class="toemail">
                            {{'A password will be e-mailed to you.'|translate}}
                        </p>
                        <p class="submit">
                            <input type="submit" id="register_button" value="{{'Register'|translate}}" class="button" tabindex="3">
                        </p>
                        <?php echo apply_filters( 'login_form_bottom', '', array() );?>
                    </div>
                    <div class="lost">
                        <p class="pswd">
                            <span>
                                <a href="<?php echo wp_login_url();?>">{{'Login'|translate}}</a>
                            </span>
                            <span>
                                <a href="<?php echo wp_lostpassword_url();?>">{{'Lost your password?'|translate}}</a>
                            </span>
                        </p>
                        <!--
                        <?php //if ( get_option('lammers_use_facebook') ){;?>
                        <div class="other-providers">
                            <p class="fb-login">
                                <a href=""><i class="icon-facebook"></i>Facebook</a>
                            </p>
                        </div>
                        <?php //} ?>
                        -->
                    </div>
                </fieldset>
                <input type="hidden" name="testcookie" value="1">
            </form>
        </div>
    </div>
</div>