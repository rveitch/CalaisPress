<?php
/**
 * Plugin Name: CalaisPress
 * Description: Open Calais metadata tagging test tool for WordPress.
 * Plugin URI:	https://github.com/calaispress
 * Author:			Ryan Veitch
 * Author URI:	http://veitchdigital.com/
 * License:		 GPL v2 or later
 * Version:		 1.16.07.08
 */

/*--------------------------------------------------------------
 # PLUGIN SETUP
 --------------------------------------------------------------*/
/*
 * Include the OpenCalais PHP library
 */
if ( ! class_exists( 'OpenCalais' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/PHP-OpenCalais/opencalais.php' );
}

/*
 * Enqueue scripts and styles
 */
function calaispresstester_style_scripts() {
	wp_enqueue_script( 'eqt_ajax', plugin_dir_url( __FILE__ ) . '/includes/js/cp_ajax.js' );
}
add_action( 'admin_enqueue_scripts', 'calaispresstester_style_scripts' );

/*--------------------------------------------------------------
 # Admin Dashboard Page
 --------------------------------------------------------------*/

/*
 * Create the admin menu item
 */
function cp_ajax_tester_create_admin_page() {
	$icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAzMiAzMiIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNDE0MjE7Ij4gICAgPGcgdHJhbnNmb3JtPSJtYXRyaXgoMC43MjgxNTUsMi4zMTExMmUtMzIsMi4zMTExMmUtMzIsMC43MjgxNTUsMSwtNi4wOTk1MSkiPiAgICAgICAgPGcgaWQ9IkxheWVyXzEiPiAgICAgICAgICAgIDxnPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyLjEiIGN5PSIzMC4zIiByPSIyLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjM5LjEiIGN5PSIzMC4zIiByPSIyLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIuNyIgY3k9IjI1LjUiIHI9IjEuOSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iNC42IiBjeT0iMjEuMSIgcj0iMS43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSI3LjUiIGN5PSIxNy4yIiByPSIxLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjExLjMiIGN5PSIxNC4zIiByPSIxLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE1LjgiIGN5PSIxMi40IiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIwLjYiIGN5PSIxMS44IiByPSIwLjgiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI1LjQiIGN5PSIxMi40IiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI5LjkiIGN5PSIxNC4zIiByPSIxLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjMzLjciIGN5PSIxNy4yIiByPSIxLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjM2LjciIGN5PSIyMS4xIiByPSIxLjciIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjM4LjUiIGN5PSIyNS41IiByPSIxLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjM4LjUiIGN5PSIzNS4xIiByPSIxLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjM2LjciIGN5PSIzOS42IiByPSIxLjciIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjMzLjciIGN5PSI0My40IiByPSIxLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI5LjkiIGN5PSI0Ni40IiByPSIxLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI1LjQiIGN5PSI0OC4yIiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIwLjYiIGN5PSI0OC45IiByPSIwLjgiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE1LjgiIGN5PSI0OC4yIiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjExLjMiIGN5PSI0Ni40IiByPSIxLjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjcuNSIgY3k9IjQzLjQiIHI9IjEuNCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iNC42IiBjeT0iMzkuNiIgcj0iMS43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyLjciIGN5PSIzNS4xIiByPSIxLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjYuMyIgY3k9IjMyLjQiIHI9IjEuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iOS4zIiBjeT0iMzkuMyIgcj0iMS41IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIzNC45IiBjeT0iMjguMiIgcj0iMS41IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIzMiIgY3k9IjIxLjQiIHI9IjEuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iNi4yIiBjeT0iMjguNiIgcj0iMS40IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIzNSIgY3k9IjMyIiByPSIxLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI5LjIiIGN5PSIxOC43IiByPSIxLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEyIiBjeT0iNDEuOSIgcj0iMS40IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSI3LjIiIGN5PSIyNSIgcj0iMS4xIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIzNC4xIiBjeT0iMzUuNyIgcj0iMS4xIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNiIgY3k9IjE2LjkiIHI9IjEuMSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTUuMiIgY3k9IjQzLjgiIHI9IjEuMSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iOSIgY3k9IjIxLjciIHI9IjAuOSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMzIuMiIgY3k9IjM5IiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIyLjMiIGN5PSIxNiIgcj0iMC45IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxOC45IiBjeT0iNDQuNyIgcj0iMC45IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxMS42IiBjeT0iMTkiIHI9IjAuNyIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjkuNiIgY3k9IjQxLjciIHI9IjAuNyIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTQuOSIgY3k9IjE3IiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI2LjMiIGN5PSI0My42IiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE4LjUiIGN5PSIxNiIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMi43IiBjeT0iNDQuNiIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSI3LjMiIGN5PSIzNi4xIiByPSIxLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjMzLjkiIGN5PSIyNC42IiByPSIxLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEzLjEiIGN5PSIzOC4zIiByPSIxLjMiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjExIiBjeT0iMzUuNiIgcj0iMS4yIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSI5LjgiIGN5PSIzMi40IiByPSIxIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSI5LjciIGN5PSIyOC45IiByPSIwLjgiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEwLjciIGN5PSIyNS42IiByPSIwLjciIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEyLjYiIGN5PSIyMi44IiByPSIwLjUiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI4LjYiIGN5PSIzNy45IiByPSIwLjUiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE1LjMiIGN5PSIyMC43IiByPSIwLjciIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjMwLjUiIGN5PSIzNSIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNS45IiBjeT0iNDAiIHI9IjAuNyIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTguNiIgY3k9IjE5LjUiIHI9IjAuOCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMzEuNSIgY3k9IjMxLjciIHI9IjAuOCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjIuNyIgY3k9IjQxLjEiIHI9IjAuOCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjIiIGN5PSIxOS40IiByPSIxIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIzMS40IiBjeT0iMjguMyIgcj0iMSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTkuMiIgY3k9IjQxLjIiIHI9IjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI1LjMiIGN5PSIyMC40IiByPSIxLjIiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjMwLjIiIGN5PSIyNSIgcj0iMS4yIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNS45IiBjeT0iNDAuMyIgcj0iMS4yIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyOC4xIiBjeT0iMjIuMyIgcj0iMS4zIiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMi45IiBjeT0iMjIuNCIgcj0iMSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTguMyIgY3k9IjM4LjIiIHI9IjEiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI1LjMiIGN5PSIyMy41IiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIwLjgiIGN5PSIzOC42IiByPSIwLjkiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE2IiBjeT0iMzcuMSIgcj0iMC45IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMC40IiBjeT0iMjIuMSIgcj0iMC45IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNy44IiBjeT0iMjIuNiIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNy4xIiBjeT0iMjUuMyIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMy40IiBjeT0iMzguMSIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNC4xIiBjeT0iMzUuNCIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyOC40IiBjeT0iMjcuNiIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNS42IiBjeT0iMzYuOCIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNS42IiBjeT0iMjMuOCIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxMy44IiBjeT0iMjUuNiIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNy40IiBjeT0iMzUiIHI9IjAuNiIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTIuNyIgY3k9IjI4IiByPSIwLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI4LjUiIGN5PSIzMi42IiByPSIwLjQiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEyLjQiIGN5PSIzMC42IiByPSIwLjUiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI4LjgiIGN5PSIzMC4xIiByPSIwLjUiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjEyLjkiIGN5PSIzMy4xIiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE3LjgiIGN5PSIyNSIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMy41IiBjeT0iMzUuNiIgcj0iMC44IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxOS41IiBjeT0iMjQuNCIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNi4zIiBjeT0iMjYuMiIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyMS43IiBjeT0iMzYuMiIgcj0iMC43IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNSIgY3k9IjM0LjUiIHI9IjAuNyIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjEuNCIgY3k9IjI0LjQiIHI9IjAuNiIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjYiIGN5PSIzMi45IiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE5LjgiIGN5PSIzNi4zIiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE1LjIiIGN5PSIyNy43IiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjIzLjIiIGN5PSIyNC45IiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjI2LjYiIGN5PSIzMS4xIiByPSIwLjYiIHN0eWxlPSJmaWxsOnJnYigxNjAsMTY1LDE3MCk7Ii8+ICAgICAgICAgICAgICAgIDxjaXJjbGUgY3g9IjE4IiBjeT0iMzUuNyIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIxNC43IiBjeT0iMjkuNSIgcj0iMC42IiBzdHlsZT0iZmlsbDpyZ2IoMTYwLDE2NSwxNzApOyIvPiAgICAgICAgICAgICAgICA8Y2lyY2xlIGN4PSIyNC44IiBjeT0iMjYiIHI9IjAuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjYuNSIgY3k9IjI5LjIiIHI9IjAuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTYuNSIgY3k9IjM0LjciIHI9IjAuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTQuNyIgY3k9IjMxLjQiIHI9IjAuNSIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMjUuOSIgY3k9IjI3LjUiIHI9IjAuNCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICAgICAgPGNpcmNsZSBjeD0iMTUuMyIgY3k9IjMzLjIiIHI9IjAuNCIgc3R5bGU9ImZpbGw6cmdiKDE2MCwxNjUsMTcwKTsiLz4gICAgICAgICAgICA8L2c+ICAgICAgICA8L2c+ICAgIDwvZz48L3N2Zz4=';
	add_menu_page( 'CalaisPress', 'CalaisPress', 'edit_pages', 'cp_ajax_tester_admin/ve-admin.php', 'cp_ajax_tester_admin_page', $icon, 49 );
	add_action( 'admin_init', 'cp_admin_init' );
}
add_action( 'admin_menu', 'cp_ajax_tester_create_admin_page' );

function cp_admin_init() {
	register_setting( 'calaispress-settings-group', 'oc_api_key' );
}

/**
 * Admin Page
 */
function cp_ajax_tester_admin_page() {

	$default_query = "If Yahoo's directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal's value might decline if Microsoft has to take those steps.";

	$html = '<div class="wrap">';
	$html .= '<h2>CalaisPress API Tester</h2><br />';
	$html .= '<span class="wp-ui-text-icon" style="line-height: 2;">Content:</span>';
	$html .= '<textarea name="cp_query" rows="15" id="cp-ajax-option-id" class="large-text code" style="width: 100%;">' . $default_query . '</textarea>';
	$html .= '<button id="cp-wp-ajax-button" class="button-primary">Send Content</button>';
	$html .= '<br><br><hr>';

	// Response Div
	$html .= '<div style="margin-bottom: 10px;">';
	$html .= '<a href="javascript:void(1)" onclick="jQuery(this).parent().next(&#x27;div&#x27;).slideToggle();" style="background: url(&#x27;images/arrows.png&#x27;) no-repeat; padding-left: 15px;">';
	$html .= '<h3 style="display: inline-block;">JSON Response</h3>';
	$html .= '</a>';
	$html .= '</div>';
	$html .= '<div style="display: block;">'; // set to "display: none;" to unhide by default
	$html .= '<textarea id="cp-response" rows="25" style="width: 100%;"></textarea>';
	$html .= '</div>';

	$html .= '</div>';
	echo $html;

	// API Key
	?>
	<div style="margin-bottom: 10px;">
		<a href="javascript:void(1)" onclick="jQuery(this).parent().next(&#x27;div&#x27;).slideToggle();" style="background: url(&#x27;images/arrows.png&#x27;) no-repeat; padding-left: 15px;">
			<h3 style="display: inline-block;">API Key</h3>
		</a>
	</div>
	<div style="display: none;">
			<div class="card">
				<div class="inside">
					<form action="options.php" method="post">
						<?php settings_fields( 'calaispress-settings-group' ); ?>
						<table class="form-table">
							<tr valign="top">
								<th style="width:125px" scope="row">REST API Key: </th>
								<td><input type="text" name="oc_api_key" value="<?php echo get_option( 'oc_api_key' ); ?>" size="50"></td>
							</tr>
						</table>
						<?php submit_button(); ?>
					</form>
				</div>
			</div>
	</div>
	<?php
}

/*--------------------------------------------------------------
 # AJAX
 --------------------------------------------------------------*/

function cp_ajax_tester_ajax_handler() {

	/**
	 * Gets the current configuration setting of magic_quotes_gpc
	 * Returns 0 if magic_quotes_gpc is off, 1 otherwise.
	 * Or always returns FALSE as of PHP 5.4.0.
	 * @link http://php.net/manual/en/function.get-magic-quotes-gpc.php
	 */
	if ( get_magic_quotes_gpc() ) {
		$qry = $_POST['id'];
	} else {
		$qry = stripslashes( $_POST['id'] );
	}

	$apikey = get_option( 'oc_api_key' );
	$oc = new OpenCalais( $apikey );
	$content = $qry;
	$data = $oc->getJSON( $content );
	echo $data;
	wp_die(); // just to be safe

}
add_action( 'wp_ajax_cp_ajax_tester_approal_action', 'cp_ajax_tester_ajax_handler' );
