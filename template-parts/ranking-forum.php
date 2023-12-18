<?php 

global $wpdb, $post, $curauth;
	$comment_id = $commentID;
	$comment = get_comment( $comment_id );
	$comment_author_id = $comment -> user_id;
	
	$userId = $comment_author_id;

	$where = 'WHERE comment_approved = 1 AND user_id = ' . $userId ;
	$comment_count = $wpdb->get_var("SELECT COUNT( * ) AS total 
		FROM {$wpdb->comments}
		{$where}");

	$nivel = getNivel($comment_count);
?>

