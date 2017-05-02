<div class="monstro-pagination">
<?php
echo paginate_links( array(
    'base' => str_replace( 999999999, '%#%', get_pagenum_link(999999999) ),
    'current' => max( 1, get_query_var('paged') ),
    'total' => $wp_query->max_num_pages,
    'mid_size' => 5
) );
?>
</div>