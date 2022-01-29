<?php

/**
 * Plugin Name: Temps de lecture
 * Description: Affiche le temps de lecture estimé d'un article.
 * Version: 1.0.0
 *
 * @package TempsLecture
 * @category Core
 */


function capitaine_reading_time( $post_id, $post, $update )  {

	if( ! $update ) { return; }
	if( wp_is_post_revision( $post_id ) ) { return; }
	if( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) { return; }
	if( $post->post_type != 'post' ) { return; }

	// Calculer le temps de lecture
	$word_count = str_word_count( strip_tags( $post->post_content ) );

	// On prend comme base 250 mots par minute
	$minutes = ceil( $word_count / 250 );
	
	// On sauvegarde la meta
	update_post_meta( $post_id, 'reading_time', $minutes );
}
add_action('save_post', 'capitaine_reading_time', 10, 3);
add_filter('the_title', 'titleTime', 10, 2);



function titleTime($title, $id,)
{
    if (!get_post_meta($id, 'reading_time', true)) {
        return $title;
    }

    return $title.' | Lecture : '.get_post_meta($id, 'reading_time', true).' minutes';
}
