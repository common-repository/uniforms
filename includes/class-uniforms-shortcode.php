<?php

if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UniForms Shortcode Class
 *
 * @since 1.0.0
 */
class UniForms_Shortcode {
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        add_shortcode( 'uniforms', array( $this, 'render' ) );
        
    }
    
    /**
     * Send Mail
     *
     * @since 1.0.0
     */
    function send_mail() {
        global $post;
        
        $form = isset( $_POST['uniforms'] ) ? array_filter( $_POST['uniforms'] ) : '';
        
        $sender['name'] = '';
        $sender['email'] = '';
        $sender['subject'] = '';
        $sender['message'] = '';
        
        if( $form && ! empty( $form ) ) {
            foreach( $form as $key => $value ) {
                
                // Detect sender name.
                if( strpos( $key, 'sender-name' ) !== false ) {
                    $sender['name'] = esc_attr( $value );
                }
                
                // Detect sender email address.
                if( strpos( $key, 'sender-email' ) !== false ) {
                    $sender['email'] = esc_attr( $value );
                }
                
                // Detect sender subject.
                if( strpos( $key, 'sender-subject' ) !== false ) {
                    $sender['subject'] = esc_attr( $value );
                }
                
                // Detect sender message.
                if( strpos( $key, 'sender-message' ) !== false ) {
                    $sender['message'] = esc_html( $value );
                }
                
            }
            
            $recipient = get_post_meta( $post->ID, '_uniforms_mail', true );
            $recipient = $recipient ? unserialize( $recipient ) : '';
            
            $site_name = esc_attr( get_option( 'blogname' ) );
            $admin_email = esc_attr( get_option( 'admin_email' ) );
            
            if( ! empty( $recipient ) ) {
                $to = esc_attr( $recipient['recipient'] );
                
                $from = esc_attr( $recipient['sender'] );
                $from = str_replace( '[your-name]', $site_name, $from );
                
                $subject = esc_attr( $recipient['subject'] );
                $subject = str_replace( '[your-subject]', '"[your-subject]"', $subject );
                $subject = str_replace( '[your-subject]', $sender['subject'], $subject );
                
                $headers = esc_html( $recipient['additional_headers'] );
                
                $additional_headers[] = 'MIME-Version: 1.0';
                $additional_headers[] = 'Content-type:text/plain; charset=UTF-8';
                
                // If sender name is set add it to headers.
                if( ! empty( $sender['name'] ) && ! empty( $sender['email'] ) ) {
                    $additional_headers[] = 'From: ' . $sender['name'] . ' <' . $sender['email'] . '>';
                }
                
                $additional_headers[] = str_replace( '[your-email]', $sender['email'], $headers );
                
                $message_body = esc_html( $recipient['message_body'] );
                $message_body = str_replace( '&lt;', '<', $message_body );
                $message_body = str_replace( '&gt;', '>', $message_body );
                $message_body = str_replace( '[your-name]', $sender['name'], $message_body );
                $message_body = str_replace( '[your-email]', $sender['email'], $message_body );
                $message_body = str_replace( '[your-subject]', $sender['subject'], $message_body );
                $message_body = str_replace( '[your-message]', $sender['message'], $message_body );
                
                // If email send successfuly output message.
                if( wp_mail( $to, $subject, $message_body, $additional_headers ) ) {
                    echo '<p>'. esc_html__( 'Your email was successfuly sent.', 'uniforms' ) .'</p>';
                }
               
            }
        }
    }
    
    /**
     * Render Content
     *
     * @since 1.0.0
     */
    function render( $atts ) {
        extract( $atts );
        
        $args = array(
            'p'         => esc_attr( $id ),
            'post_type' => esc_attr( 'uniforms' )
        );
        
        $the_query = new WP_Query( $args );
        
        if( $the_query->have_posts() ) {
            $the_query->the_post();
            
            if( get_the_title() ) {
                $id = strtolower( get_the_title() );
                $id = str_replace( ' ', '-', $id );
                $id = 'uniforms-'. esc_attr( $id );
            } else {
                $id = esc_attr( 'uniform-form' );
            }
            
            $array  = json_decode( get_the_content() );
            
            echo '<form id="'. esc_attr( $id ) .'" method="post">';
            
                foreach( $array as $options ) {
                    UniForms::build( $options );
                }
            
            echo '</form>';
            
            $this->send_mail();
        }
    }
    
}
new UniForms_Shortcode();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
