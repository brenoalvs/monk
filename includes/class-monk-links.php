<?php
/**
 * The engine that changes links to use the apprpriate language
 *
 * @since      0.2.0
 *
 * @package    Monk
 * @subpackage Monk/Includes
 */

/**
 * This class holds all functions related to links transformation
 * the permalinks are filtered to return content related to
 * the language defined by the user.
 *
 * @package    Monk
 * @subpackage Monk/Links
 */
class Monk_Links {

	/**
	 * The plugin ID.
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initializes the class and set its properties.
	 *
	 * @since    0.2.0
	 * @param    string $monk       The name and ID of the plugin.
	 * @param    string $version    The plugin version.
	 */
	public function __construct( $monk, $version ) {
		$this->version	      = $version;
		$this->plugin_name    = $monk;
		$this->index	      = 'index.php';
		$this->site_home      = home_url();
		$this->$structure     = get_option( 'permalink_structure', false );
		$this->$site_language = get_option( 'monk_default_language', false );
		$this->site_root      = preg_match( '#^/*' . $this->index . '#', $this->$structure ) ? $this->index . '/' : '';
	}

	/**
	 * Function to retrieve all user active languages from the option
	 * These languages are defined under the Monk Settings page
	 *
	 * @return    array active_languages.
	 */
	public function monk_get_active_languages() {
		return get_option( 'monk_active_languages', false );
	}

	/**
	 * Adds the set of custom rewrite rules for pretty permalinks
	 * This function is used inside rewrite_rules filter,
	 * which retrieves the array containing every rule
	 *
	 * @since  0.2.0
	 * @param  array $rules rewrite rules.
	 * @return array $monk_rules + $rules.
	 */
	public function monk_create_rewrite_functions( $rules ) {
		// get the filter being applied during the rules creation, except the root_rewrite_rules.
		$filter = str_replace( '_rewrite_rules', '', current_filter() );

		global $wp_rewrite;

		$monk_rules      = array();
		$language_codes = array();
		$monk_languages = $this->monk_get_active_languages();

		// constructs the array to hold all language codes.
		foreach ( $monk_languages as $codes ) {
			$language_codes[ $codes ] = $codes;
		}

		// the $slug i.e (en|pt|es).
		$slug = $wp_rewrite->root . '(' . implode( '|', $language_codes ) . ')/';

		/**
		 * The following foreach takes the rules as name => value,
		 * count every 'match[ $counter ]' and replace them with match[ $counter + 1 ]
		 * also add the lang parameter in place of '?' symbol
		 */
		foreach ( $rules as $name => $rule ) {

			$old_matches = array();
			$new_matches = array();
			$max         = preg_match_all( '/(\$matches\[[1-9]{1,}\])/', $rule, $matches );

			if ( $max ) {
				for ( $old = $max; $old >= 1; $old-- ) {
					$new = $old + 1;
					array_push( $old_matches, '[' . $old . ']' );
					array_push( $new_matches, '[' . $new . ']' );
				}
			}

			array_push( $old_matches, '?' );
			array_push( $new_matches, '?lang=$matches[1]&' );

			if ( isset( $slug ) ) {
				$monk_rules[ $slug . str_replace( $wp_rewrite->root, '', $name ) ] = str_replace(
					$old_matches,
					$new_matches,
					$rule
				);
			}
		}

		if ( 'root' === $filter ) {
			return;
		}

		return $monk_rules + $rules;
	}

	/**
	 * Adds the rewrite rule for the home page
	 * Uses global $wp_rewrite
	 *
	 * @since  0.2.0
	 * @return void
	 */
	public function monk_add_home_rewrite_rule() {
		add_rewrite_rule( '^(([a-z]){2}\_([A-Z]){2})', 'index.php?lang=$matches[1]', 'top' );
	}

	/**
	 * Reinitializes the rewrite functions array whenever the option
	 * 'monk_active_languages' gets updated
	 *
	 * @since  0.2.0
	 * @return void
	 */
	public function monk_flush_on_update() {
		flush_rewrite_rules();
	}

	/**
	 * Checks whether the pretty permalinks are active or not
	 * if it is, return the structure
	 *
	 * @return bool|string $structure
	 */
	public function monk_using_permalinks() {
		return ( empty( $this->$structure ) ) ? false : $this->$structure;
	}

	/**
	 * Changes the link to the home page using the current language.
	 *
	 * @since  0.2.0
	 * @param  string $url home link during query.
	 * @param  string $path path requested.
	 * @return string $url.
	 */
	public function monk_add_language_home_permalink( $url, $path ) {
		// these cases are exceptions, do not filter.
		if ( is_admin() || ! ( did_action( 'login_init' ) || did_action( 'template_redirect' ) ) ) {
			return $url;
		}

		$url_language  = get_query_var( 'lang' );
		$language      = ( empty( $url_language ) ) ? $this->$site_language : $url_language;

		if ( $language && '/' === $path ) {
			if ( $this->monk_using_permalinks() ) {
				return trailingslashit( $url . '/' . $language );
			} else {
				return add_query_arg( 'lang', $language, $url );
			}
		}

		return $url;
	}

	/**
	 * This function performs a redirect from the home url
	 * when the user try to access it with no language
	 *
	 * @since  0.2.0
	 * @return void
	 */
	public function monk_redirect_home_url() {
		// url is wether site.com/en or site.com/?lang=en.
		if ( is_home() && ! ( get_query_var( 'lang' ) || get_query_var( 's' ) ) ) {

			$url_language  = get_query_var( 'lang' );
			$language      = ( empty( $url_language ) ) ? $this->$site_language : $url_language;

			if ( $this->monk_using_permalinks() ) {
				wp_safe_redirect( trailingslashit( home_url( '/' . $language ) ) );
				exit();
			} else {
				wp_safe_redirect( add_query_arg( 'lang', $language, trailingslashit( home_url() ) ), 301 );
				exit();
			}
		} else {
			return;
		}
	}

	/**
	 * Changes the post permalinks to add its language
	 *
	 * @since  0.2.0
	 * @param  string $permalink Post link during query.
	 * @param  object $post Post object.
	 * @return string $permalink
	 */
	public function monk_add_language_post_permalink( $permalink, $post ) {
		global $wp_rewrite;

		$post_language = get_post_meta( $post->ID, '_monk_post_language', true );
		$url_language  = get_query_var( 'lang' );
		$language      = ( empty( $post_language ) ) ? $this->$site_language : $post_language;

		if ( empty( $language ) ) {
			return $permalink;
		}

		if ( $this->monk_using_permalinks() ) {
			$permalink = $this->monk_change_language_url( $permalink, $language );
			return $permalink;
		} else {
			$permalink = add_query_arg( 'lang', $language, $permalink );
			return $permalink;
		}
	}

	/**
	 *  Changes the page permalinks to add its language
	 *
	 * @since  0.2.0
	 * @param  string  $link Post link during query.
	 * @param  integer $post_id Post object.
	 * @return string $url Altered url.
	 */
	public function monk_add_language_page_permalink( $link, $post_id ) {
		$page_language = get_post_meta( $post_id, '_monk_post_language', true );
		$language	   = ( empty( $page_language ) ) ? $this->$site_language : $page_language;

		if ( empty( $language ) ) {
			return $link;
		}

		if ( $this->monk_using_permalinks() ) {
			$url = $this->monk_change_language_url( $permalink, $language );
			return $url;
		} else {
			$url = add_query_arg( 'lang', $language, $link );
			return $url;
		}
	}

	/**
	 * Changes the date archive links adding the language to further filter results
	 *
	 * @since  0.2.0
	 * @param  string $link Url to the requested date archive.
	 * @return string $url
	 */
	public function monk_add_language_date_permalink( $link ) {
		$language = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->$site_language;

		if ( empty( $language ) ) {
			return $link;
		}

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $link );
			$link  = trailingslashit( site_url() ) . $language . $path;
			return $link;
		} else {
			$link = add_query_arg( 'lang', $language, $link );
			return $link;
		}
	}

	/**
	 * Changes the term permalinks adding the language
	 *
	 * @since  0.2.0
	 * @param  string $url Term link during query.
	 * @param  object $term Term object.
	 * @param  string $taxonomy The taxonomy of each queried term.
	 * @return string $url
	 */
	public function monk_add_language_term_permalink( $url, $term, $taxonomy ) {
		global $wp_rewrite;

		$term_language = get_term_meta( $term->term_id, '_monk_term_language', true );
		$language      = ( empty( $term_language ) ) ? $this->$site_language : $term_language;

		if ( empty( $language ) ) {
			return $url;
		}

		if ( $this->monk_using_permalinks() ) {
			$path      = wp_make_link_relative( $url );
			$url = trailingslashit( $wp_rewrite->root ) . $language . $path;
			return $url;
		} else {
			$url = add_query_arg( 'lang', $language, $url );
			return $url;
		}
	}

	/**
	 * Changes the author archive link, using the current language to filter results
	 *
	 * @since  0.2.0
	 * @param  string $link Url to author archive.
	 * @param  int    $author_id current query author id.
	 * @return string $link
	 */
	public function monk_add_language_author_permalink( $link, $author_id ) {
		$language = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->$site_language;

		if ( $language ) {
			if ( $this->monk_using_permalinks() ) {
				$link = str_replace( $this->site_home . '/' . $this->site_root, $this->site_home . '/' . $this->site_root . $language . '/', $link );
				return $link;
			} else {
				$link = add_query_arg( 'lang', $language, home_url() );
				$link = add_query_arg( 'author', $author_id, $link );
				return $link;
			}
		} else {
			$link = add_query_arg( 'author', $author_id, home_url() );
			return $link;
		}
	}

	/**
	 * Changes link to use in searches and functions ( i.e get_search_link() ).
	 *
	 * @param  string $link provided url.
	 * @return string $link
	 */
	public function monk_add_language_search_permalink( $link ) {
		$language = get_query_var( 'lang' ) ? get_query_var( 'lang' ) : $this->$site_language;

		if ( empty( $language ) ) {
			return $link;
		}

		if ( $this->monk_using_permalinks() ) {
			$link = $this->monk_change_language_url( $link, $language );
			return $link;
		} else {
			$link = add_query_arg( 'lang', $language, $link );
			return $link;
		}
	}

	/**
	 * Function to add a hidden field with the value of the users language
	 * only used when permalinks are disabled.
	 *
	 * @param  string $form provided url.
	 * @return string|html $form
	 */
	public function monk_change_search_form( $form ) {
		if ( $form ) {
			$page_language = get_query_var( 'lang' );
			$language	   = ( empty( $page_language ) ) ? $this->$site_language : $page_language;

			/**
			 * Replace the closing form tag with the hidden field
			*/
			if ( $this->monk_using_permalinks() ) {
				return $form;
			} else {
				$form = str_replace( '</form>', '<input type="hidden" name="lang" value="' . esc_attr( $language ) . '" /></form>', $form );
			}
		}
		return $form;
	}

	/**
	 * Changes the language inside the given url
	 *
	 * @param  string $url provided url.
	 * @param  string $lang the correct language to use.
	 * @return $url
	 */
	public function monk_change_language_url( $url, $lang ) {
		if ( empty( $lang ) ) {
			return $url;
		} else {

			$active_languages = $this->monk_get_active_languages();

			if ( $this->monk_using_permalinks() ) {

				if ( ! empty( $active_languages ) ) {

					$slug    = $lang . '/';
					$pattern = str_replace( '/', '\/', $this->site_home . '/' . $this->site_root );
					$pattern = '#' . $pattern . '(' . implode( '|', $active_languages ) . ')(\/|$)#';
					$url     = preg_replace( $pattern, $this->site_home . '/' . $this->site_root, $url );

					return str_replace( $this->site_home . '/' . $this->site_root, $this->site_home . '/' . $this->site_root . $slug, $url );
				}
			} else {
				$url = remove_query_arg( 'lang', $url );
				$url = ( empty( $lang ) ) ? $url : add_query_arg( 'lang', $lang, $url );
				return $url;
			}
		}
	}

	/**
	 * Redirects the incoming url when a wrong ( or lacking ) language is detected
	 * preventing duplicated content for SEO performance
	 *
	 * @since  0.2.0
	 * @return $redirect_url
	 */
	public function monk_need_canonical_redirect() {
		global $wp_query, $post, $is_IIS;

		// we do not want to redirect in these cases.
		if ( is_search() || is_admin() || is_robots() || is_preview() || is_trackback() || ( $is_IIS && ! iis7_supports_permalinks() ) ) {
			return;
		}

		if ( is_attachment() && filter_input( INPUT_GET, 'attachment_id' ) ) {
			return;
		}

		if ( filter_input( INPUT_GET, 'wp_customize' ) || filter_input( INPUT_GET, 'customized' ) ) {
			return;
		}

		// first get the correct url and scheme.
		$requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		/**
		 * Then decide what type of content is being requested
		 * prior to get its language
		 */
		if ( is_single() || is_page() ) {
			if ( isset( $post->ID ) ) {
				$language = get_post_meta( $post->ID, '_monk_post_language', true );
			}
		} elseif ( is_category() || is_tax() || is_tag() ) {

			$obj 	  = $wp_query->get_queried_object();
			$language = get_term_meta( $obj->ID, '_monk_term_language', true );

		} elseif ( $wp_query->is_posts_page ) {

			$obj 	  = $wp_query->get_queried_object();
			$language = get_post_meta( $obj->ID, '_monk_post_language', true );

		} elseif ( is_404() && ! empty( $wp_query->query['page_id'] ) && $id = get_query_var( 'page_id' ) ) {

			$language = get_post_meta( $id, '_monk_post_language', true );
		}

		if ( empty( $language ) ) {

			$language     = $this->$site_language;
			$redirect_url = $requested_url;

		} else {
			$_redirect_url = ( ! $_redirect_url = redirect_canonical( $requested_url, false ) ) ? $requested_url: $_redirect_url;
			$redirect_url  = ( ! $redirect_url = redirect_canonical( $_redirect_url, false ) ) ? $_redirect_url: $redirect_url;

			$redirect_url = $this->monk_change_language_url( $redirect_url, $language );
		}

		if ( $redirect_url && $requested_url !== $redirect_url ) {
			wp_safe_redirect( $redirect_url, 301 );
			exit;
		}

		return $redirect_url;
	}
}
