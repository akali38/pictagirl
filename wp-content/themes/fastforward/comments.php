<?php
$partial = MonstroThemePartial::getInstance();
?>
<div id="content-wp" class="comments-content">
    <div class="row">
        <div id="comments" class="large-12 columns">
            <ol class="monstro-comment-list monstro-comment-plain">
                <?php wp_list_comments( array(
                    'callback' => array($partial, 'comment')
                ));?>
            </ol>
            <?php if(get_comments_number() > get_option('comments_per_page')){?>
            <div class="monstro-pagination">
                <?php
                paginate_comments_links( array(
                    'base' => add_query_arg('cpage', '%#%', remove_query_arg(MONSTROTHEME_PARTIAL_ENDPOINT)),
                    'prev_text' => '&laquo; Prev',
                    'next_text' => 'Next &raquo;',
                    'echo' => true,
                    'current' => get_query_var('cpage') ? get_query_var('cpage') : 1
                ) );
                ?>
            </div>
            <?php
            }
            $fields =  array(
                'author' => '<div class="large-6 columns"><p class="comment-form-author input">' . '<input class="required" placeholder="' . __( 'Your name','monstrotheme' ) . '" id="author" name="author" type="text" value="" size="30"  />' .
                    '</p>',
                'email'  => '<p class="comment-form-email input"><input  class="required" id="email" name="email" placeholder="' . __( 'Your email','monstrotheme' ) . '" type="text" value="" size="30" />' .
                    '</p>',
            );

            $leave_a_reply = __("Leave a reply",'monstrotheme');
            $add_comment = __("Add comment",'monstrotheme' );

            /*add URL for comments*/
            $fields['url'] = '<p class="comment-form-url input"><input id="url" name="url" type="text" value="" placeholder="' . __( 'Website','monstrotheme' ) . '" size="30" />' .
                '</p></div>';

            if(comments_open()){
                $args = array(
                    'title_reply' => '<span>'. $leave_a_reply .'</span>' ,
                    'comment_notes_after' =>'',
                    'comment_notes_before' =>'<div class="large-12 columns"><p class="comment-notes st">' . __( 'Your email address will not be published.' , 'monstrotheme' ) . '</p></div>',
                    //'comment_notes_before' =>'',
                    'logged_in_as' =>'<div class="large-12 columns"><p class="logged-in-as">' . __( 'Logged in as' ,'monstrotheme' ) . ' <a href="' . home_url('/wp-admin/profile.php') . '">' . get_the_author_meta( 'nickname' , get_current_user_id() ) . '</a>. <a href="' . wp_logout_url( get_permalink( $post -> ID ) ) .'" title="' . __( 'Log out of this account' , 'monstrotheme' ) . '">' . __( 'Log out?' , 'monstrotheme' ) . ' </a></p></div>',
                    'fields' => apply_filters( 'comment_form_default_fields', $fields),
                    'comment_field' => '<div class="large-6 columns"><p class="comment-form-comment textarea"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div>',
                    'label_submit' => $add_comment
                );

                comment_form( $args );
            }
            ?>
        </div>
    </div>
</div>