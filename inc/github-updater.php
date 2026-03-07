<?php
/**
 * GitHub Theme Updater
 * 
 * Automatische Updates für WordPress Themes von GitHub Releases
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Understrap_GitHub_Updater' ) ) {

	/**
	 * Class Understrap_GitHub_Updater
	 */
	class Understrap_GitHub_Updater {

		/**
		 * GitHub Username
		 *
		 * @var string
		 */
		private $username;

		/**
		 * GitHub Repository Name
		 *
		 * @var string
		 */
		private $repository;

		/**
		 * Theme Slug
		 *
		 * @var string
		 */
		private $theme_slug;

		/**
		 * Current Theme Version
		 *
		 * @var string
		 */
		private $version;

		/**
		 * GitHub Access Token (optional für private Repos)
		 *
		 * @var string
		 */
		private $access_token;

		/**
		 * Constructor
		 */
		public function __construct() {
			$theme = wp_get_theme();
			
			// Theme Informationen aus style.css Header auslesen
			$github_uri = $theme->get( 'GitHub Theme URI' );
			
			if ( empty( $github_uri ) ) {
				return;
			}

			// Parse GitHub URI
			preg_match( '/github\.com\/([^\/]+)\/([^\/]+)\/?/', $github_uri, $matches );
			
			if ( count( $matches ) < 3 ) {
				return;
			}

			$this->username    = $matches[1];
			$this->repository  = $matches[2];
			$this->theme_slug  = get_template();
			$this->version     = $theme->get( 'Version' );
			$this->access_token = ''; // Optional: GitHub Personal Access Token hier eintragen

			// WordPress Hooks registrieren
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
			add_filter( 'themes_api', array( $this, 'theme_api_call' ), 10, 3 );
		}

		/**
		 * Check for Theme Updates
		 *
		 * @param object $transient Update transient.
		 * @return object
		 */
		public function check_for_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			// GitHub API Daten holen
			$remote_version = $this->get_remote_version();

			if ( $remote_version && version_compare( $this->version, $remote_version->tag_name, '<' ) ) {
				$transient->response[ $this->theme_slug ] = array(
					'theme'       => $this->theme_slug,
					'new_version' => $remote_version->tag_name,
					'url'         => $remote_version->html_url,
					'package'     => $this->get_download_url( $remote_version ),
				);
			}

			return $transient;
		}

		/**
		 * Get Remote Version Info from GitHub
		 *
		 * @return object|bool
		 */
		private function get_remote_version() {
			$api_url = sprintf(
				'https://api.github.com/repos/%s/%s/releases/latest',
				$this->username,
				$this->repository
			);

			$args = array(
				'headers' => array(
					'Accept' => 'application/vnd.github.v3+json',
				),
			);

			// Optional: Access Token für private Repos
			if ( ! empty( $this->access_token ) ) {
				$args['headers']['Authorization'] = 'token ' . $this->access_token;
			}

			$response = wp_remote_get( $api_url, $args );

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body );

			if ( ! empty( $data->tag_name ) ) {
				return $data;
			}

			return false;
		}

		/**
		 * Get Download URL for Theme Package
		 *
		 * @param object $release Release object from GitHub API.
		 * @return string
		 */
		private function get_download_url( $release ) {
			// Prüfe ob ein Release Asset (ZIP) vorhanden ist
			if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
				foreach ( $release->assets as $asset ) {
					if ( strpos( $asset->name, '.zip' ) !== false ) {
						return $asset->browser_download_url;
					}
				}
			}

			// Fallback: Nutze den Source Code ZIP Download
			return sprintf(
				'https://github.com/%s/%s/archive/refs/tags/%s.zip',
				$this->username,
				$this->repository,
				$release->tag_name
			);
		}

		/**
		 * Theme API Call Handler
		 *
		 * @param false|object|array $result Result.
		 * @param string             $action Action.
		 * @param object             $args   Arguments.
		 * @return object
		 */
		public function theme_api_call( $result, $action, $args ) {
			if ( 'theme_information' !== $action ) {
				return $result;
			}

			if ( $args->slug !== $this->theme_slug ) {
				return $result;
			}

			$remote_version = $this->get_remote_version();

			if ( ! $remote_version ) {
				return $result;
			}

			$theme = wp_get_theme();

			$result = new stdClass();
			$result->name = $theme->get( 'Name' );
			$result->slug = $this->theme_slug;
			$result->version = $remote_version->tag_name;
			$result->author = $theme->get( 'Author' );
			$result->homepage = $theme->get( 'ThemeURI' );
			$result->download_link = $this->get_download_url( $remote_version );
			$result->sections = array(
				'description' => $theme->get( 'Description' ),
			);

			if ( ! empty( $remote_version->body ) ) {
				$result->sections['changelog'] = $this->parse_changelog( $remote_version->body );
			}

			return $result;
		}

		/**
		 * Parse Changelog from GitHub Release Notes
		 *
		 * @param string $body Release body.
		 * @return string
		 */
		private function parse_changelog( $body ) {
			// Konvertiere Markdown zu HTML (basic)
			$changelog = wpautop( $body );
			return $changelog;
		}
	}

	// Initialisiere Updater
	new Understrap_GitHub_Updater();
}
