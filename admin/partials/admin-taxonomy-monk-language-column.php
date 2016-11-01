<?php 
	$base_url         = admin_url( 'term.php?action=edit' );
	$monk_language = get_term_meta( $term_id, 'monk-language', true );
	$taxonomies = get_taxonomies();

	foreach ( $taxonomies as $taxonomy ) :

		if ( $monk_language && $_GET['taxonomy'] === $taxonomy ) :
			$term_url = get_edit_term_link( $term_id, $taxonomy, 'post' );
			
			?>
				<a href="<?php echo esc_url( $term_url ); ?>">
					<span class="monk-selector-flag flag-icon <?php echo esc_attr( 'flag-icon-' . $monk_language ); ?>"></span>
				</a>
			<?php
		endif;
	endforeach;