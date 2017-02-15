<?php
/**
 * Links Engine: The Monk_Links class
 *
 * @package    Monk
 * @subpackage Monk/Includes
 * @since      0.2.0
 */

/**
 * Holds all functions to modify links.
 *
 * The permalinks are filtered to return the content related to
 * the language defined by the user.
 *
 * @since      0.2.0
 *
 * @package    Monk
 * @subpackage Monk/Links
 */
class Monk_Links {

	/**
	 * The plugin ID.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $monk    The ID of this plugin.
	 */
	private $monk;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Refers to the index file, the fallback for every query.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $index    Index file.
	 */
	private $index;

	/**
	 * Link for the home set by the user.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $site_home    The link  for the home.
	 */
	private $site_home;

	/**
	 * The structure for the permalinks used across the site.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $structure    The current permalink structure.
	 */
	private $structure;

	/**
	 * Language the user is using as the primary for the site.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $site_language    The main site language set by user.
	 */
	private $site_language;

	/**
	 * The root for the site urls, comes from the permalink structure.
	 *
	 * @since    0.2.0
	 *
	 * @access   private
	 * @var      string    $site_root    The site root.
	 */
	private $site_root;

	/**
	 * Initializes the class and set its properties.
	 *
	 * @since    0.2.0
	 *
	 * @param    string $monk       The name and ID of the plugin.
	 * @param    string $version    The plugin version.
	 */
	public function __construct( $monk, $version ) {
		$this->plugin_name   = $monk;
		$this->version	     = $version;
		$this->index	     = 'index.php';
		$this->site_home     = home_url();
		$this->structure     = get_option( 'permalink_structure', false );
		$this->site_root     = preg_match( '#^/*' . $this->index . '#', $this->structure ) ? $this->index . '/' : '';

		$default_language    = get_option( 'monk_default_language', false );

		if ( $default_language ) {
			$this->site_language = $monk_languages[ $default_language ]['slug'];
		}
	}

	/**
	 * Retrieves all user active languages.
	 *
	 * Gets values from the option 'monk_active_languages'
	 * or return false if the user did not configure the plugin yet
	 * These languages are defined under the Monk Settings page
	 *
	 * @since    0.2.0
	 *
	 * @return array The active languages.
	 */
	public function monk_get_active_languages() {
		global $monk_languages;

		$active_languages = array();
		$languages        = get_option( 'monk_active_languages', false );

		if ( $languages ) {
			foreach ( $languages as $lang_code ) {
				$active_languages[] = $monk_languages[ $lang_code ]['slug'];
			}
		}

		return $active_languages;
	}

	/**
	 * Adds the set of custom rewrite rules for pretty permalinks.
	 *
	 * This function is used inside rewrite_rules filter,
	 * which retrieves the array containing every rule and its regex to process.
	 *
	 * @since    0.2.0
	 *
	 * @global   class $wp_rewrite.
	 *
	 * @param    array $rules The rewrite rules.
	 * @return array $monk_rules + $rules.
	 */
	public function monk_create_rewrite_functions( $rules ) {
		// Do not execute if the current filter applied is referent to root.
		if ( 'root_rewrite_rules' === current_filter() ) {
			return $rules;
		}

		global $wp_rewrite;

		$monk_rules      = array();
		$language_codes = array();
		$monk_languages = $this->monk_get_active_languages();

		// Constructs the array to hold all language codes.
		foreach ( $monk_languages as $codes ) {
			$language_codes[ $codes ] = $codes;
		}

		// The $slug i.e (en|pt|es).
		$slug = $wp_rewrite->root . '(' . implode( '|', $language_codes ) . ')/';

		/*
		 * Takes the rules as name => value,
		 * count every 'match[ $counter ]' and replace them with match[ $counter + 1 ]
		 * also add the lang parameter in place of '?' symbol.
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

		return $monk_rules + $rules;
	}

	/**
	 * Adds the home page rewrite rule.
	 *
	 * The rule checks for a word with 2 letters, a '_' and other 2 capital letters
	 * i.e 'en_US', 'pt_BR'. The mached value is translated to the parameter 'lang'
	 *
	 * @since    0.2.0
	 *
	 * @return void
	 */
	public function monk_add_home_rewrite_rule() {
		add_rewrite_rule( '([a-z]{2}-[a-z]{2})', 'index.php?lang=$matches[1]', 'top' );
		add_rewrite_rule( '([a-z]{2})', 'index.php?lang=$matches[1]', 'top' );
	}

	/**
	 * Reinitializes the rewrite rules array when the option 'monk_active_languages' is updated.
	 *
	 * The array of rewrite functions is constructed using the active languages,
	 * using the filter 'pre_update_{option_name}'
	 * we can tell when the user adds or removes a language
	 *
	 * @since    0.2.0
	 *
	 * @return void
	 */
	public function monk_flush_on_update() {
		flush_rewrite_rules();
	}

	/**
	 * Checks if pretty permalinks are active.
	 *
	 * Gets the permalink_structure option, which is used to build the links suiting the user preference
	 * when it is empty, the functions run using query parameters
	 *
	 * @since    0.2.0
	 *
	 * @return bool|string $structure.
	 */
	public function monk_using_permalinks() {
		return ( empty( $this->structure ) ) ? false : $this->structure;
	}

	/**
	 * Changes the home page links to use the current language.
	 *
	 * The link for the home page, in every place needed,
	 * should take the site to the language the user is choose or the default site language
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link home link during query.
	 * @param    string $path path requested.
	 * @return string $link.
	 */
	public function monk_add_language_home_permalink( $link, $path ) {
		// These cases are exceptions, do not filter.
		if ( is_admin() || ! ( did_action( 'login_init' ) || did_action( 'template_redirect' ) ) ) {
			return $link;
		}

		$url_language  = get_query_var( 'lang' );
		$language      = ( empty( $url_language ) ) ? $this->site_language : $url_language;

		if ( $language && '/' === $path ) {
			if ( $this->monk_using_permalinks() ) {
				$link = trailingslashit( $link . '/' . $language );
			} else {
				$link = add_query_arg( 'lang', $language, $link );
			}
		}

		return $link;
	}

	/**
	 * Changes the language in the given url.
	 *
	 * @since    0.2.0
	 *
	 * @param    string $url provided url.
	 * @param    string $lang the correct language to use.
	 * @return $url The changed link.
	 */
	public function monk_change_language_url( $url, $lang ) {
		global $monk_languages;

		$active_languages = $this->monk_get_active_languages();

		if ( in_array( $lang, $active_languages, true ) ) {
			$language = $lang;
		} else {
			$language = $monk_languages[ $lang ]['slug'];
		}

		if ( $this->monk_using_permalinks() ) {

			if ( ! empty( $active_languages ) ) {

				$base    = $this->site_home . '/' . $this->site_root;
				$slug    = $language . '/';
				$pattern = str_replace( '/', '\/', $base );
				$pattern = '#' . $pattern . '(' . implode( '|', $active_languages ) . ')(\/|$)#';
				$url     = preg_replace( $pattern, $base, $url );
				$url     = str_replace( $base, $base . $slug, $url );
			}
		} else {
			$url = remove_query_arg( 'lang', $url );
			$url = ( empty( $language ) ) ? $url : add_query_arg( 'lang', $language, $url );
		}

		return $url;
	}

	/**
	 * Changes the post permalinks to add its language.
	 *
	 * The language is retrieved from post_meta usind its id
	 * and, in case the post has no language, use the site default language
	 *
	 * @since    0.2.0
	 *
	 * @global    class $wp_rewrite.
	 *
	 * @param    string $link Post link during query.
	 * @param    object $post Post object.
	 * @return string $link.
	 */
	public function monk_add_language_post_permalink( $link, $post ) {
		global $wp_rewrite;

		$post_language = get_post_meta( $post->ID, '_monk_post_language', true );
		$url_language  = get_query_var( 'lang' );
		$language      = ( empty( $post_language ) ) ? $this->site_language : $post_language;

		$link = $this->monk_change_language_url( $link, $language );
		return $link;
	}

	/**
	 * Changes the page permalinks to add its idiom.
	 *
	 * Gets the language from post_meta usind the page's id
	 * and, in case no language is found, uses the site default language
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link Post link during query.
	 * @param    int    $post_id Post object.
	 * @return string $url Altered url.
	 */
	public function monk_add_language_page_permalink( $link, $post_id ) {
		$page_language = get_post_meta( $post_id, '_monk_post_language', true );
		$language	   = ( empty( $page_language ) ) ? $this->site_language : $page_language;

		$link = $this->monk_change_language_url( $link, $language );
		return $link;
	}

	/**
	 * Changes the date archive links to add the language.
	 *
	 * The generated link comes with the current idiom
	 * set by the language switcher widget or the default language
	 * this languege is used to filter the date results
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link Url to the requested date archive.
	 * @return string $link.
	 */
	public function monk_add_language_date_permalink( $link ) {
		$language = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->site_language;

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $link );
			$link = trailingslashit( site_url() ) . $language . $path;
		} else {
			$link = add_query_arg( 'lang', $language, $link );
		}
		return $link;
	}

	/**
	 * Changes the term permalinks to add the idiom.
	 *
	 * @since    0.2.0
	 *
	 * @global    class $wp_rewrite.
	 *
	 * @param    string $link Term link during query.
	 * @param    object $term Term object.
	 * @param    string $taxonomy The taxonomy of each queried term.
	 * @return string $link.
	 */
	public function monk_add_language_term_permalink( $link, $term, $taxonomy ) {
		global $wp_rewrite;

		$term_language = get_term_meta( $term->term_id, '_monk_term_language', true );
		$language      = ( empty( $term_language ) ) ? $this->site_language : $term_language;

		if ( $this->monk_using_permalinks() ) {
			$path = wp_make_link_relative( $link );
			$link  = trailingslashit( $wp_rewrite->root ) . $language . $path;
		} else {
			$link = add_query_arg( 'lang', $language, $link );
		}
		return $link;
	}

	/**
	 * Changes the author archive link to use the current language.
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link Url to author archive.
	 * @param    int    $author_id current query author id.
	 * @return string $link.
	 */
	public function monk_add_language_author_permalink( $link, $author_id ) {
		$language = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->site_language;

		if ( $this->monk_using_permalinks() ) {
			$link = str_replace( $this->site_home . '/' . $this->site_root, $this->site_home . '/' . $this->site_root . $language . '/', $link );
		} else {
			$link = add_query_arg( 'lang', $language, home_url() );
			$link = add_query_arg( 'author', $author_id, $link );
		}
		return $link;
	}

	/**
	 * Changes the link used in searches.
	 *
	 * The query_var retrieved is the idiom activated by the visitor, therefore every function
	 * that uses the search link will be filtered using that language
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link provided url.
	 * @return string $link.
	 */
	public function monk_add_language_search_permalink( $link ) {
		$language = get_query_var( 'lang' ) ? get_query_var( 'lang' ) : $this->site_language;

		$link = $this->monk_change_language_url( $link, $language );
		return $link;
	}

	/**
	 * Adds a hidden field with the value of the current idiom.
	 *
	 * Only used when permalinks are disabled
	 * Using the get_search_form action the function gets the html about to be used
	 * in searches
	 *
	 * @since    0.2.0
	 *
	 * @param    string $form provided url.
	 * @return string|html $form.
	 */
	public function monk_change_search_form( $form ) {
		if ( $form ) {
			$page_language = get_query_var( 'lang' );
			$language	   = ( empty( $page_language ) ) ? $this->site_language : $page_language;

			// Replace the closing form tag with the hidden field.
			if ( $this->monk_using_permalinks() ) {
				return $form;
			} else {
				$form = str_replace( '</form>', '<input type="hidden" name="lang" value="' . esc_attr( $language ) . '" /></form>', $form );
			}
		}
		return $form;
	}

	/**
	 * Redirects the incoming url when a wrong ( or lacking ) language is detected.
	 *
	 * This function prevents duplicated content and improves SEO performance
	 * beacause is executed along with the wordpress redirect_canonical.
	 *
	 * @since    0.2.0
	 *
	 * @global    object $wp_query.
	 * @global    object $post.
	 * @global bool $is_IIS.
	 *
	 * @return void|string $redirect_url The correct link.
	 */
	public function monk_canonical_redirection() {
		global $wp_query, $post, $is_IIS;

		$active_languages = $this->monk_get_active_languages();

		/*
		 * Only use this when the home is being displayed and there is no lang parameter
		 * or if a non-active language is requested.
		 */
		if ( is_home() && ( ! ( get_query_var( 'lang' ) || get_query_var( 's' ) ) || ! in_array( get_query_var( 'lang' ), $active_languages, true ) ) ) {

			$url_language = get_query_var( 'lang' );
			$language     = ( empty( $url_language ) ) ? $this->site_language : $url_language;
			$language     = in_array( $language, $active_languages, true ) ? $language : $this->site_language;

			if ( $this->monk_using_permalinks() ) {
				wp_safe_redirect( trailingslashit( home_url( '/' . $language ) ) );
			} else {
				wp_safe_redirect( add_query_arg( 'lang', $language, trailingslashit( home_url() ) ), 301 );
			}
			exit();
		}

		// We do not want to redirect in these cases.
		if ( is_search() || is_admin() || is_robots() || is_preview() || is_trackback() || ( $is_IIS && ! iis7_supports_permalinks() ) ) {
			return;
		}

		// When a media is being displayed, we do not want the redirection.
		if ( is_attachment() && filter_input( INPUT_GET, 'attachment_id' ) ) {
			return;
		}

		// The customizer functionality uses the admin link, do not filter either.
		if ( filter_input( INPUT_GET, 'wp_customize' ) || filter_input( INPUT_GET, 'customized' ) ) {
			return;
		}

		// First get the correct url and scheme.
		$requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		/*
		 * Then decide what type of content is being requested
		 * before retrieving its language.
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

		// If is not any of the above content cases, the fallback is the default language.
		if ( empty( $language ) ) {

			$language     = $this->site_language;
			$redirect_url = $requested_url;

		} else {
			/*
			 * Uses the redirect_canonical to check the canonical url that wordpress evaluates.
			 * Is used twice to correct a bug in which the port is incorrect at the first try
			 * and returns correct in the second try.
			 */
			$_redirect_url = ( ! $_redirect_url = redirect_canonical( $requested_url, false ) ) ? $requested_url: $_redirect_url;
			$redirect_url  = ( ! $redirect_url = redirect_canonical( $_redirect_url, false ) ) ? $_redirect_url: $redirect_url;

			$redirect_url = $this->monk_change_language_url( $redirect_url, $language );
		}

		// If the incoming url has a wrong language, redirect.
		if ( $redirect_url && $requested_url !== $redirect_url ) {
			wp_safe_redirect( $redirect_url, 301 );
			exit;
		}

		return $redirect_url;
	}
}
