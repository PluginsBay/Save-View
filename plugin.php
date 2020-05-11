<?php

/**
 * Plugin Name: Save & View
 * Plugin URI:  http://wporb.com/
 * Description: Adds a "Save&View" button on the WordPress post edit screen.
 * Version:     PluginsBay
 * Author URI:  http://pluginsbay.com/
 * License:     GPL2
 */

add_action('admin_init', array('SaveView', 'init'));

class SaveView {

	public static function init() {
		add_action('post_submitbox_misc_actions', array('SaveView', 'add_button'));
		add_filter('redirect_post_location', array('SaveView', 'redirect'), '99');

	}

	public static function add_button() {
		$post_id = (int) $_GET['post'];
		$status = get_post_status($post_id);

		$button_label = ($status == 'publish' || $status == 'private') ? 'Update & View' : 'Publish & View';

		?>

		<div id="major-publishing-actions" style="overflow:hidden">
			<div id="publishing-action">
				<input type="submit" tabindex="5" value="<?php echo $button_label ?>" class="button-primary" id="SaveView" name="SaveView" />
			</div>
		</div>

		<?php
	}

	public static function redirect($location) {
		if (!isset($_POST['SaveView'])) return $location;

		$post_status = 'publish';
		$post_id = (int) $_POST['post_ID'];

		if (get_post_status_object($_POST['original_post_status']) && ($_POST['original_post_status'] == 'publish' || $_POST['original_post_status'] == 'private')) {
			$post_status = sanitize_text_field($_POST['post_status']);
		}
		if (get_post_status_object($_POST['post_status']) && $_POST['post_status'] == 'private') {
			$post_status = 'private';
		}

		wp_update_post(array('ID' => $post_id, 'post_status' => $post_status));
		return get_permalink($post_id);

	}

}
