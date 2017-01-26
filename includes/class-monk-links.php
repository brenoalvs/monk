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
	 * Initializes the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $monk       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $monk, $version ) {
		$structure = get_option( 'permalink_structure' );

		$this->plugin_name = $monk;
		$this->version	   = $version;
		$this->index	   = 'index.php';
		$this->home		   = home_url();
		$this->root		   = preg_match( '#^/*' . $this->index . '#', $structure ) ? $this->index . '/' : '';
	}

	/**
	 * Function to retrieve all user active languages
	 *
	 * @return array active_languages.
	 */
	public function monk_get_active_languages() {
		return get_option( 'monk_active_languages', false );
	}

	/**
	 *  Adds all custom structures for pretty-permalinks
	 *
	 * @param array $rules rewrite rules.
	 * @since 0.0.1
	 */
	public function monk_create_rewrite_functions( $rules ) {
		// get the filter being applied during the rules creation.
		$filter = str_replace( '_rewrite_rules', '', current_filter() );

		global $wp_rewrite;

		$monkrules      = array();
		$language_codes = array();
		$monk_languages = $this->monk_get_active_languages();

		foreach ( $monk_languages as $codes ) {
			$language_codes[ $codes ] = $codes;
		}

		$slug = $wp_rewrite->root . '(' . implode( '|', $language_codes ) . ')/';

		foreach ( $rules as $key => $rule ) {

			$old_order = array();
			$new_order = array();
			$max = preg_match_all( '/(\$matches\[[1-9]\])/', $rule, $matches );

			if ( $max ) {
				for ( $i = $max; $i >= 1; $i-- ) {
					array_push( $old_order, '[' . $i . ']' );
					$j           = $i + 1;
					array_push( $new_order, '[' . $j . ']' );
				}
			}

			array_push( $old_order, '?' );
			array_push( $new_order, '?lang=$matches[1]&' );

			if ( isset( $slug ) ) {
				$monkrules[ $slug . str_replace( $wp_rewrite->root, '', $key ) ] = str_replace(
					$old_order,
					$new_order,
					$rule
				);
			}
		}

		if ( 'root' === $filter ) {
			return;
		}

		return $monkrules + $rules;
	}

	/**
	 * Adds the rewrite rule for the home page
	 * Uses global $wp_rewrite
	 *
	 * @since 0.0.1
	 */
	public function monk_add_home_rewrite_rule() {
		global $wp_rewrite;
		add_rewrite_rule( '^(([a-z]){2}\_([A-Z]){2})', 'index.php?lang=$matches[1]', 'top' );
	}

	/**
	 * Reinitializes the rewrite functions array whenever the option
	 * 'monk_active_languages' gets updated
	 *
	 * @since 0.0.1
	 */
	public function monk_flush_on_update() {
		flush_rewrite_rules();
	}

	/**
	 * Checks whether the pretty permalinks are active or not
	 *
	 * @return bool|string $structure
	 */
	public function monk_using_permalinks() {
		$structure = get_option( 'permalink_structure', false );
		if ( ( empty( $structure ) ) ) {
			return false;
		} else {
			return $structure;
		}
	}

	/**
	 * Function to check for the usage of a trailing slash
	 *
	 * @return bool
	 */
	public function monk_apply_slash() {
		if ( $this->monk_using_permalinks() ) {
			$structure = $this->monk_using_permalinks();
			return ( '/' === substr( $structure, -1, 1 ) ) ? true : false;
		}
	}

	/**
	 * Changes the post permalinks to add its language
	 *
	 * @since 0.0.1
	 * @param string $permalink Post link during query.
	 * @param object $post Post object.
	 */
	public function monk_add_language_post_permalink( $permalink, $post ) {
		global $wp_rewrite;

		$site_default_language = get_option( 'monk_default_language', false );
		$post_language		   = get_post_meta( $post->ID, '_monk_post_language', true );
		$url_language		   = get_query_var( 'lang' );
		$language		       = ( empty( $post_language ) ) ? $site_default_language : $post_language;

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $permalink );
			$url = trailingslashit( $wp_rewrite->root ) . $language . $path;
			return $url;
		} else {
			$url = add_query_arg( 'lang', $language, $permalink );
			return $url;
		}
	}

	/**
	 *  Changes the page links to add its language
	 *
	 * @since 0.0.1
	 * @param string  $link Post link during query.
	 * @param integer $post_id Post object.
	 * @return string $url Altered url.
	 */
	public function monk_add_language_page_permalink( $link, $post_id ) {

		$site_default_language = get_option( 'monk_default_language', false );
		$page_language		   = get_post_meta( $post_id, '_monk_post_language', true );
		$language			   = ( empty( $page_language ) ) ? $site_default_language : $page_language;

		if ( empty( $language ) ) {
			return $link;
		}

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $link );
			$url = trailingslashit( site_url() ) . $language . $path;
			return $url;
		} else {
			$url = add_query_arg( 'lang', $language, $link );
			return $url;
		}
	}

	/**
	 * Changes the date archive links adding the language to filter results
	 *
	 * @since 0.0.1
	 * @param string $link Url to the requested date archive.
	 */
	public function monk_add_language_date_permalink( $link ) {

		$date_language = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : get_option( 'monk_default_language', false );

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $link );
			$url = trailingslashit( site_url() ) . $date_language . $path;
			return $url;
		} else {
			$url = add_query_arg( 'lang', $language, $link );
			return $url;
		}
	}

	/**
	 *  Changes the term permalinks adding the language
	 *
	 * @since 0.0.1
	 * @param string $url Term link during query.
	 * @param object $term Term object.
	 * @param string $taxonomy The taxonomy of each queried term.
	 */
	public function monk_add_language_term_permalink( $url, $term, $taxonomy ) {

		$site_default_language = get_option( 'monk_default_language', false );
		$term_language         = get_term_meta( $term->term_id, '_monk_term_language', true );
		$language              = ( empty( $term_language ) ) ? $site_default_language : $term_language;

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $url );
			$permalink = trailingslashit( $wp_rewrite->root ) . $language . $path;
			return $permalink;
		} else {
			$link = add_query_arg( 'lang', $language, $url );
			return $link;
		}
	}

	/**
	 * Changes the language inside a given url
	 *
	 * @param string $url provided url.
	 * @param string $lang the correct language to use.
	 */
	public function monk_change_language_url( $url, $lang ) {
		if ( empty( $lang ) ) {
			return $url;
		} else {

			$active_languages = $this->monk_get_active_languages();

			if ( $this->monk_using_permalinks() ) {

				if ( ! empty( $active_languages ) ) {

					$pattern = str_replace( '/', '\/', $this->home . '/' . $this->root );
					$pattern = '#' . $pattern . '(' . implode( '|', $active_languages ) . ')(\/|$)#';
					$url = preg_replace( $pattern,  $this->home . '/' . $this->root, $url );
					$slug = $lang . '/';

					return str_replace( $this->home . '/' . $this->root, $this->home . '/' . $this->root . $slug, $url );
				}
			} else {
				$url = remove_query_arg( 'lang', $url );
				$url = ( empty( $lang ) ) ? $url : add_query_arg( 'lang', $lang, $url );
				return $url;
			}
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

		// get the correct url and scheme.
		if ( empty( $requested_url ) ) {
			$requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		// then decide what type of content being requested.
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

			$language     = get_option( 'monk_default_language', false );
			$redirect_url = $requested_url;

		} else {
			$_redirect_url = ( ! $_redirect_url = redirect_canonical( $requested_url, false ) ) ? $requested_url: $_redirect_url;
			$redirect_url  = ( ! $redirect_url = redirect_canonical( $_redirect_url, false ) ) ? $_redirect_url: $redirect_url;

			$redirect_url = $this->monk_change_language_url( $redirect_url, $language );
		}

		if ( $do_redirect && $redirect_url && $requested_url !== $redirect_url ) {
			wp_safe_redirect( $redirect_url, 301 );
			exit;
		}

		return $redirect_url;
	}
}
