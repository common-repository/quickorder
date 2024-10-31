<?php
/* 
	Plugin Name: Quickorder
	Version: 1.0
	Plugin URI: http://quickorder.lrbdesign.com
	Description: A plugin to order pages a bit faster than the built in Wordpress way.
	Author: Lance Becker
	Author URI: http://www.lrbdesign.com
*/
/*
	Copyright 2008  Lance R Becker.  (email : lancerbecker@gmail.com)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'create_pages');


function create_pages() {
    // Add a new submenu under Manage page titled Quickorder:
    add_management_page('Quickorder', 'Order pages', 8, 'qo', 'Quickorder');
}

function Quickorder() {
	//Access to WP database class
	global $wpdb;

	//Rendering out the html for the page.
	$html = '<div class="wrap">';
	$html .= '<h2>Quickorder</h2>';
	$html .= '<form action="' . $_SERVER['POST'] . '" method="post">';
	$html .= '<table class="widefat">';
	$html .= '<thead>';
	$html .= '<tr>';
	$html .= '<th scope="col">Page Name</th>';
	$html .= '<th scope="col">Order</th>';
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	echo $html;
		
		//SQL that selects all the posts in the posts tables with
		//the post_type of page then places them in an array.
		$table_name = $wpdb->prefix . "posts";
		$sql = "SELECT * FROM $table_name WHERE post_type = 'page'";
		$results = $wpdb->get_results($sql);
	
		//Looping through the results array and rendering out all the posts
		//that are pages.
		foreach ($results as $result) {
		
			$form = "<tr>";
			$form .="<td>$result->post_title</td>";
			$form .="<td><input type=\"text\" value=\"$result->menu_order\" name=\"menu_order[".$result->ID."]\"></td>";
			$form .="</tr>";
			echo $form;
		}

	$html = '</table>';
	$html .= '<input class="button" type="submit" name="submit" value="Change pages">';
	$html .= '</form>';
	$html .= '</div>';
	echo $html;
	

	//Checks to see if the submit button [Change pages] was pressed
	//then loops through the array menu_order[] and finally the
	//database is accessed and updated to reflect the changes
	if ($_POST['submit']) {
		$menu_array = $_POST['menu_order'];
		while (list ($id, $menu_order) = each ($menu_array)) {
	
			$sql = "UPDATE $table_name SET menu_order = $menu_order WHERE ID = $id";
			$check = $wpdb->query($sql);
			//If the SQL query is successful refresh the broswer to reflect the changes
			if ($check) {
				echo '<meta http-equiv="refresh" content="0.2">';
			}
		}
	}
//end of Quickorder() function
}
?>