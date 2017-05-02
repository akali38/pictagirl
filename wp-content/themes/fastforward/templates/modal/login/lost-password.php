<div class="monstro-modal" ng-controller="LoginCtrl" ng-submit="lostPassword()" ng-keydown="closeLoginModalEsc($event)">
    <div class="modal-content">
        <div class="login">
            <a href="javascript:void(0);" class="icon-close fr" ng-click="closeLoginModal()"></a>
            <div class="login-image-icon">&nbsp;</div>
            <?php do_action( 'lost_password' );?>
            <form name="lostpasswordform" id="monstro-login" method="post" class="lostpasswordform">
                <div data-alert class="alert-box warning" ng-show="status.error" ng-bind-html="status.error"></div>
                <fieldset>
                    <div class="login_inside">
                        <p>
                    <input ng-model="log" name="log" type="text" tabindex="1" autofocus placeholder="{{'Username or E-mail'|translate}}">
                        </p>
                        <p class="submit">
                            <input type="submit" id="login_button" tabindex="2" value="{{'Get New Password'|translate}}" class="button">
                        </p>
                    </div>
                    <div class="lost">
                        <p class="pswd">
                            <span>
                                <a href="<?php echo wp_login_url();?>">{{'Log in'|translate}}</a>
                            </span>
                            <?php if ( get_option('users_can_register') ){;?>
                            <span>
                                <a href="<?php echo wp_registration_url();?>">{{'Register'|translate}}</a>
                            </span>
                            <?php } ?>
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
            </form>
        </div>
    </div>
</div>