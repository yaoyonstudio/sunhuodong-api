<?php
/**
Plugin Name: sunhuodong-api
Description: response a posts list with id,title,date,thumbnail,featuredimgurl(without content field), response a single post with id,title,date,thumbnail,featuredimgurl,content
Author: ken yao
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

function my_rest_prepare_post( $data, $post, $request ) {
	$_data = $data->data;

	$params = $request->get_params();
	if ( ! isset( $params['id'] ) ) {
		unset( $_data['content'] );
	}
    
    $article = array();
    $article['id'] = $_data['id'];
    $article['title']['rendered'] = $_data['title']['rendered'];
    $article['date'] = $_data['date'];
    
    //添加缩略图路径和特色图片路径
    if ( has_post_thumbnail() ) {
        $thumbnail_id = get_post_thumbnail_id( $_data['id'] );
        $thumbnail = wp_get_attachment_image_src( $thumbnail_id , 'thumbnail' );
        $featuredimg = wp_get_attachment_image_src( $thumbnail_id , 'full' );
        $thumbnailurl = $thumbnail[0];
        $featuredimgurl = $featuredimg[0];
        if( ! empty($thumbnailurl)){
            $article['thumbnailurl'] = $thumbnailurl;
        }
        if( ! empty($featuredimgurl)){
            $article['featuredimgurl'] = $featuredimgurl;
        }
    }else{
        $article['thumbnailurl'] = null;
        $article['featuredimgurl'] = null;
    }
    
    $_data['content'] ? $article['content']['rendered'] = $_data['content']['rendered'] : null;
    
	// $data->data = $_data;
	// return $data;
    return $article;
}
add_filter( 'rest_prepare_post', 'my_rest_prepare_post', 10, 3 );
 