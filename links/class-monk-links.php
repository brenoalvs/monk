<?php
/**
 * The engine to change links according to language
 *
 * @since      0.2.0
 *
 * @package    Monk
 * @subpackage Monk/Links
 */

/**
 * Class to hold all functions related to links
 * the permalinks are filtered to return content due to languages
 *
 * @package    Monk
 * @subpackage Monk/Links
 */
class Monk_Links {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $monk       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $monk, $version ) {

		$this->plugin_name = $monk;
		$this->version = $version;

	}

	/**
	 *  Adds all custom structures for pretty-permalinks
	 *
	 * @param array $rules rewrite rules.
	 * @since 0.0.1
	 */
	public function monk_create_rewrite_functions( $rules ) {
		$filter = str_replace( '_rewrite_rules', '', current_filter() );

		global $wp_rewrite;

		$monkrules      = array();
		$language_codes = array();
		$monk_languages = get_option( 'monk_active_languages', false );

		foreach ( $monk_languages as $codes ) {
			$language_codes[ $codes ] = $codes;
		}

		$slug = $wp_rewrite->root . '(' . implode( '|', $language_codes ) . ')/';

		foreach ( $rules as $key => $rule ) {
			if ( isset( $slug ) ) {
				$monkrules[ $slug . str_replace( $wp_rewrite->root, '', $key ) ] = str_replace(
					array( '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]', '[1]', '?' ),
					array( '[9]', '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]', '?lang=$matches[1]&' ),
					$rule
				);
			}
		}

		if ( 'root' === $filter && isset( $slug ) ) {
			$monkrules[ $slug . '?$' ] = $wp_rewrite->index . '?lang=matches[1]';
		}

		return $monkrules + $rules;
	}

	/**
	 * Reinitialize the rewrite functions array whenever the option
	 * 'monk_active_languages' gets updated
	 *
	 * @since 0.0.1
	 */
	public function monk_flush_on_update() {
		flush_rewrite_rules();
	}

	/**
	 *  Change the post permalinks adding the language
	 *
	 * @since 0.0.1
	 * @param string $permalink Post link during query.
	 * @param object $post Post object.
	 */
	public function monk_add_language_post_permalink( $permalink, $post ) {
		global $wp_rewrite;

		$site_default_language = get_option( 'monk_default_language', false );
		$post_language		   = get_post_meta( ( is_page() ) ? get_the_id() : $post->ID, '_monk_post_language', true );
		$url_language		   = get_query_var( 'lang' );
		$structure		       = get_option( 'permalink_structure', false );
		$language		       = ( empty( $post_language ) ) ? $site_default_language : $post_language;

		if ( empty( $structure ) ) {
			$url = add_query_arg( 'lang', $language, $permalink );
			return $url;
		} else {
			$path = wp_make_link_relative( $permalink );
			$url = trailingslashit( $wp_rewrite->root ) . $language . $path;
			return $url;
		}
	}

	/**
	 *  Change the page links adding the language
	 *
	 * @since 0.0.1
	 * @param string  $link Post link during query.
	 * @param integer $post_id Post object.
	 * @return string $url Altered url.
	 */
	public function monk_add_language_page_permalink( $link, $post_id ) {

		$site_default_language = get_option( 'monk_default_language', false );
		$page_language		   = get_post_meta( $post_id, '_monk_post_language', true );
		$structure			   = get_option( 'permalink_structure', false );
		$language			   = ( empty( $page_language ) ) ? $site_default_language : $page_language;

		if ( empty( $language ) ) {
			return $link;
		}

		if ( empty( $structure ) ) {
			$url = add_query_arg( 'lang', $language, $link );
			return $url;
		} else {
			$path = wp_make_link_relative( $link );
			$url = trailingslashit( site_url() ) . $language . $path;
			return $url;
		}
	}

	/**
	 *  Change the term permalinks adding the language
	 *
	 * @since 0.0.1
	 * @param string $url Term link during query.
	 * @param object $term_id Term object.
	 * @param string $taxonomy The taxonomy of the current term.
	 */
	public function monk_add_language_term_permalink( $url, $term_id, $taxonomy ) {
		$site_default_language = get_option( 'monk_default_language', false );
		$term_language         = get_term_meta( $term_id, '_monk_term_language', true );
		$structure             = get_option( 'permalink_structure', false );
		$language              = ( empty( $term_language ) ) ? $site_default_language : $term_language;

		if ( empty( $structure ) ) {
			$link = add_query_arg( 'lang', $language, $url );
			return $link;
		} else {
			$path = wp_make_link_relative( $url );
			$permalink = trailingslashit( $wp_rewrite->root ) . $language . $path;
			return $permalink;
		}
	}

	/**
	 * Redirects the incoming url when a wrong language is detected
	 * preventing duplicated content
	 *
	 * @since 0.0.1
	 * @param string $requested_url the incoming url.
	 * @param bool   $do_redirect whether to redirect or not.
	 */
	public function monk_need_canonical_redirect( $requested_url = '', $do_redirect = true ) {
		global $wp_query, $post, $is_IIS;

		// we do not want to redirect in these cases.
		if ( is_search() || is_admin() || is_robots() || is_preview() || is_trackback() || ( $is_IIS && ! iis7_supports_permalinks() ) ) {
			return;
		}

		if ( is_attachment() && isset( $_GET['attachment_id'] ) ) {
			return;
		}

		if ( isset( $_POST['wp_customize'], $_POST['customized'] ) ) {
			return;
		}

		// get the correct url.
		if ( empty( $requested_url ) ) {
			$requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		// decidind what type of content is this.
		if ( is_single() || is_page() ) {

			if ( isset( $post->ID ) ) {
				$language = get_post_meta( $post->ID, '_monk_post_language', true );
			}
		} elseif ( is_category() || is_tax() || is_tag() ) {

			$obj = $wp_query->get_queried_object();
			$language = get_term_meta( $obj->ID, '_monk_term_language', true );

		} elseif ( $wp_query->is_posts_page ) {

			$obj = $wp_query->get_queried_object();
			$language = get_post_meta( $obj->ID, '_monk_post_language', true );

		} elseif ( is_404() && ! empty( $wp_query->query['page_id'] ) && $id = get_query_var( 'page_id' ) ) {

			$language = get_post_meta( $id, '_monk_post_language', true );
		}

		if ( empty( $language ) ) {

			$redirect_url = $requested_url;
			$language     = get_option( 'monk_default_language', false );

		} else {
			$_redirect_url = ( ! $_redirect_url = redirect_canonical( $requested_url, false ) ) ? $requested_url: $_redirect_url;
			$redirect_url  = ( ! $redirect_url = redirect_canonical( $_requested_url, false ) ) ? $_requested_url: $redirect_url;

			$redirect_url = $this->monk_change_language_url( $redirect_url, $language );
		}

		if ( $do_redirect && $redirect_url && $requested_url != $redirect_url ) {
			wp_safe_redirect( $redirect_url, 301 );
			exit;
		}

		return $redirect_url;
	}
}
