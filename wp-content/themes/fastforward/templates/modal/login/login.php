<div class="monstro-modal" ng-controller="LoginCtrl" ng-submit="login()" ng-keydown="closeLoginModalEsc($event)">
    <div class="modal-content">
        <div class="login">
            <a href="javascript:void(0);" class="icon-close fr" ng-click="closeLoginModal()"></a>
            <div class="login-image-icon">&nbsp;</div>
            <form name="loginForm" id="monstro-login" method="post" class="loginform">
                <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                <div data-alert class="alert-box warning" ng-show="status.error" ng-bind-html="status.error"></div>
                <fieldset>
                    <div class="login_inside">
                        <?php echo apply_filters( 'login_form_top', '', array() );?>
                        <p>
                            <input ng-model="log" name="log" type="text" tabindex="1" autofocus placeholder="{{'Username'|translate}}">
                        </p>
                        <p>
                            <input ng-model="pwd" name="pwd" type="password" tabindex="2" placeholder="{{'Password'|translate}}">
                        </p>
                        <?php echo apply_filters( 'login_form_middle', '', array() );?>
                        <?php do_action('login_form');?>
                        <p class="rememberme">
                            <label class="remember">
                                <input ng-model="rememberme" name="remember" type="checkbox" value="forever" tabindex="3">
                                {{'Remember Me'|translate}}
                            </label>
                        </p>
                        <p class="submit">
                            <input type="submit" id="login_button" value="{{'Login'|translate}}" class="button" tabindex="4">
                        </p>
                        <?php echo apply_filters( 'login_form_bottom', '', array() );?>
                    </div>
                    <div class="lost">
                        <p class="pswd">
                            <span>
                                <a href="<?php echo wp_lostpassword_url();?>">{{'Lost your password?'|translate}}</a>
                            </span>
                            <?php if ( get_option('users_can_register') ){;?>
                            <span>
                                <a href="<?php echo wp_registration_url();?>">{{'Register'|translate}}</a>
                            </span>
                            <?php } ?>
                        </p>
                        <?php //if ( get_option('lammers_use_facebook') ){;?>
                        <!--TODO facebook login
                        <div class="other-providers">
                            <p class="fb-login">
                                <a href=""><i class="icon-facebook"></i>Facebook</a>
                            </p>
                        </div>
                        -->
                        <?php //} ?>
                    </div>
                </fieldset>
                <input type="hidden" name="testcookie" value="1">
            </form>
        </div>
    </div>
</div>