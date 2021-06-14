<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Tweets_Search
 * @subpackage Tweets_Search/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div class="content">
        <h2><?php echo __('Opciones', 'tweets-search') ?></h2>
        <form method="post">
            <table class="table-form">
                <tr>
                    <th scope="row"><?php echo __('Bearer Token', 'tweets-search') ?></th>
                    <td><input type="text" name="twitter_token" value="<?php echo get_option('twitter_token')?>" class="regular-text" /></td>
                </tr>
            </table>
            <p class="submit">
            <button type="submit" name="guardar" class="button button-primary"><?php echo __('Guardar', 'tweets-search') ?></button>
            </p>
        </form>
    </div>
</div>