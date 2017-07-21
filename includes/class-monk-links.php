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
		$monk_languages = monk_get_available_languages();

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
		$monk_languages = monk_get_available_languages();

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

		$monk_rules       = array();
		$language_codes   = array();
		$active_languages = $this->monk_get_active_languages();

		// Constructs the array to hold all language codes.
		foreach ( $active_languages as $codes ) {
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
			if ( ! strpos( $rule, '?lang=$matches[1]' ) ) {
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
		$active_languages = $this->monk_get_active_languages();

		// Constructs the array to hold all language codes.
		foreach ( $active_languages as $codes ) {
			$language_codes[ $codes ] = $codes;
		}

		// The $slug i.e (en|pt-br|es).
		$regex = '(' . implode( '|', $language_codes ) . ')/?$';

		add_rewrite_rule( $regex, 'index.php?lang=$matches[1]', 'top' );
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
		$is_monk_settings_page = filter_input( INPUT_GET, 'page' );

		if ( 'monk' === $is_monk_settings_page ) {
			flush_rewrite_rules();
		}
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

		$url_language         = get_query_var( 'lang' );
		$language             = ( empty( $url_language ) ) ? $this->site_language : $url_language;
		$default_language_url = get_option( 'monk_default_language_url', false );

		if ( '/' === $path || '' === $path ) {
			if ( $this->monk_using_permalinks() ) {
				if ( empty( $default_language_url ) && $this->site_language === $language ) {
					$link = $link;
				} else {
					$link = trailingslashit( $link ) . $language;
				}
			} else {
				$monk_languages   = monk_get_available_languages();
				if ( empty( $default_language_url ) && $this->site_language === $language ) {
					$link = $link;
				} else {
					$link = add_query_arg( 'lang', $language, $link );
				}
			}
		}

		return $link;
	}

	/**
	 * Changes the language in the given url.
	 *
	 * @since    0.2.0
	 *
	 * @param    string $url  provided url.
	 * @param    string $lang the correct language to use.
	 *
	 * @return $url The changed link.
	 */
	public function monk_change_language_url( $url, $lang ) {
		$monk_languages       = monk_get_available_languages();

		$default_language     = $this->site_language;
		$default_language_url = get_option( 'monk_default_language_url', false );
		$active_languages     = $this->monk_get_active_languages();

		if ( in_array( $lang, $active_languages, true ) ) {
			$language = $lang;
		} elseif ( array_key_exists( $lang, $monk_languages ) ) {
			$language = $monk_languages[ $lang ]['slug'];
		} else {
			$language = $monk_languages[ $default_language ]['slug'];
		}

		if ( $this->monk_using_permalinks() ) {
			if ( ! empty( $active_languages ) ) {
				$base = trailingslashit( $this->site_home ) . $this->site_root;
				$slug = '';
				if ( ( $default_language_url || ( ! $default_language_url && $language !== $default_language ) ) ) {
					$slug = trailingslashit( $language );
				}
				$pattern = str_replace( '/', '\/', $base );
				$pattern = '#' . $pattern . '(' . implode( '|', $active_languages ) . ')(\/|$)#';
				$url     = preg_replace( $pattern, $base, $url );
				$url     = str_replace( $base, $base . $slug, $url );
			}
		} else {
			if ( ( empty( $default_language_url ) && $language === $default_language ) ) {
				$url = remove_query_arg( 'lang', $url );
			} else {
				$url = remove_query_arg( 'lang', $url );
				$url = ( empty( $language ) ) ? $url : add_query_arg( 'lang', $language, $url );
			}
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
	 * @param    string $link Post link during query.
	 * @param    object $post Post object.
	 * @return string $link.
	 */
	public function monk_add_language_post_permalink( $link, $post ) {
		$post_language = get_post_meta( $post->ID, '_monk_post_language', true );
		$url_language  = get_query_var( 'lang' );
		$language      = ( empty( $post_language ) ) ? $this->site_language : $post_language;
		$link          = $this->monk_change_language_url( $link, $language );

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

		$link          = $this->monk_change_language_url( $link, $language );
		return $link;
	}

	/**
	 * Changes the post type archive permalinks to add its language.
	 *
	 * The language is retrieved from query var, in case the query var
	 * is invalid, use the site default language.
	 *
	 * @since    0.2.0
	 *
	 * @param    string $link Post link during query.
	 * @param    string $post_type Post type.
	 * @return   string $link.
	 */
	public function monk_add_language_post_archive_permalink( $link, $post_type ) {
		$active_languages = $this->monk_get_active_languages();
		$url_language     = get_query_var( 'lang' );
		$language         = ( in_array( $url_language, $active_languages, true ) ) ? $url_language : $this->site_language;

		$link             = $this->monk_change_language_url( $link, $language );
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
		$monk_languages       = monk_get_available_languages();
		$language             = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->site_language;
		$default_language     = get_option( 'monk_default_language', false );
		$default_slug         = $monk_languages[ $default_language ]['slug'];
		$default_language_url = get_option( 'monk_default_language_url', false );
		$link                 = $this->monk_change_language_url( $link, $language );

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
		$monk_languages = monk_get_available_languages();

		$term_language  = get_term_meta( $term->term_id, '_monk_term_language', true );
		$language       = ( empty( $term_language ) ) ? $this->site_language : $monk_languages[ $term_language ]['slug'];

		$link = $this->monk_change_language_url( $link, $language );
		return $link;
	}

	/**
	 * Changes the attachment permalinks to add its idiom.
	 *
	 * Gets the language from the wp_get_attachment_metadata usind the attachment id
	 * and, if no language is found, uses the site language.
	 *
	 * @since    0.2.0
	 *
	 * @param     string $link Attachment link during query.
	 * @param     int    $post_id Attachment id.
	 * @return    string $link Altered url.
	 */
	public function monk_add_language_attachment_permalink( $link, $post_id ) {
		$attachment_language = get_post_meta( $post_id, '_monk_post_language', true );
		$language	         = ( empty( $attachment_language ) ) ? $this->site_language : $attachment_language;
		$link                = $this->monk_change_language_url( $link, $language );

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
		$monk_languages       = monk_get_available_languages();
		$default_language     = get_option( 'monk_default_language', false );
		$default_slug         = $monk_languages[ $default_language ]['slug'];
		$default_language_url = get_option( 'monk_default_language_url', false );
		$language             = ( get_query_var( 'lang' ) ) ? get_query_var( 'lang' ) : $this->site_language;

		if ( $this->monk_using_permalinks() ) {
			$link = $default_language_url || $language !== $default_slug ? str_replace( $this->site_home . '/' . $this->site_root, $this->site_home . '/' . $this->site_root . $language . '/', $link ) : $link;
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
			$monk_languages       = monk_get_available_languages();
			$default_language     = get_option( 'monk_default_language', false );
			$default_slug         = $monk_languages[ $default_language ]['slug'];
			$default_language_url = get_option( 'monk_default_language_url', false );
			$page_language        = get_query_var( 'lang' );
			$language	          = ( empty( $page_language ) ) ? $this->site_language : $page_language;

			// Replace the closing form tag with the hidden field.
			if ( $this->monk_using_permalinks() ) {
				$form = $default_language_url || $language !== $default_slug ? $form : str_replace( home_url() . '/' . $default_slug . '/', home_url() . '/', $form );
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
	 * @return void
	 */
	public function monk_canonical_redirection() {
		$monk_languages       = monk_get_available_languages();
		$default_language_url = get_option( 'default_language_url', false );

		/**
		 * We do not want to redirect in these cases.
		 * TODO: Provide IIS Support using $is_IIS && ! iis7_supports_permalinks().
		 */
		if ( is_search() || is_admin() || is_robots() || is_preview() || is_trackback() ) {
			return;
		}

		// The customizer functionality uses the admin link, do not filter either.
		if ( filter_input( INPUT_GET, 'wp_customize', FILTER_SANITIZE_STRING ) || filter_input( INPUT_GET, 'customized', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		// First get the correct url and scheme.
		$requested_url    = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// Get current active languages.
		$active_languages = $this->monk_get_active_languages();

		// Now get the language from the correct place.
		if ( is_singular() ) {
			// From current post.
			$id       = get_queried_object_id();
			$language = get_post_meta( $id, '_monk_post_language', true );

			if ( ! empty( $language ) ) {
				$slug = $monk_languages[ $language ]['slug'];
			}
		} elseif ( is_tax() || is_tag() || is_category() ) {
			// From current term.
			$id 	  = get_queried_object_id();
			$language = get_term_meta( $id, '_monk_term_language', true );

			if ( ! empty( $language ) ) {
				$slug = $monk_languages[ $language ]['slug'];
			}
		} elseif ( is_home() || is_front_page() || is_archive() && ! ( is_tax() || is_tag() || is_category() ) ) {
			// From valid query var.
			$slug = get_query_var( 'lang', $this->site_language );
		}

		// If is not any of the above content cases, the fallback is the default language.
		if ( empty( $slug ) ) {
			$slug         = $this->site_language;
			$redirect_url = $requested_url;
		} else {
			/*
			 * Uses the redirect_canonical to check the canonical url that wordpress evaluates.
			 * Is used twice to correct a bug in which the port is incorrect at the first try
			 * and returns correct in the second try.
			 */
			$_redirect_url = ( ! redirect_canonical( $requested_url, false ) ) ? $requested_url : redirect_canonical( $requested_url, false );
			$redirect_url  = ( ! redirect_canonical( $_redirect_url, false ) ) ? $_redirect_url : redirect_canonical( $_redirect_url, false );

			$redirect_url = $this->monk_change_language_url( $redirect_url, $slug );
		}

		// If the incoming url has a wrong language, redirect.
		if ( $redirect_url && $requested_url !== $redirect_url ) {
			wp_safe_redirect( $redirect_url, 301 );
			exit();
		}
	}

	/**
	 * Modify the previous and the next post link query
	 *
	 * This function return a Join clause to get the posts with same language
	 * that the current post
	 *
	 * @param  string  $join           The JOIN clause in the SQL.
	 * @param  bool    $in_same_term   Whether post should be in a same taxonomy term.
	 * @param  array   $excluded_terms Array of excluded term IDs.
	 * @param  string  $taxonomy       Taxonomy. Used to identify the term used when `$in_same_term` is true.
	 * @param  WP_Post $post           WP_Post object.
	 *
	 * @since  0.4.0
	 *
	 * @return string  $join
	 */
	public function monk_previous_and_next_posts( $join, $in_same_term, $excluded_terms, $taxonomy, $post ) {
		global $wpdb;
		$post_id          = $post->ID;
		$post_language    = get_post_meta( $post_id, '_monk_post_language', true );
		$language         = get_option( 'monk_default_language', false );

		if ( $post_language ) {
			$language = $post_language;
		} else {
			$language = '';
		}

		$join .= 'JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->postmeta . '.post_id = p.ID AND ' . $wpdb->postmeta . '.meta_key = "_monk_post_language" AND ' . $wpdb->postmeta . '.meta_value = "' . $language . '"';

		return $join;
	}
}
