<?php

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UniForms Meta Box Class
 *
 * @since 1.0.0
 */
class UniForms_Meta_Box {
    
    /**
     * Class Constructor
     */
    function __construct() {
        if( is_admin() ) {
            
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
            add_action( 'admin_head', [ $this, 'init_tabs' ] );
            add_action( 'load-post.php',     [ $this, 'init_metabox' ] );
            add_action( 'load-post-new.php', [ $this, 'init_metabox' ] );
            
        }
    }
    
    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        
        add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
        add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );
        
    }
    
    /**
     * Enqueue Scripts
     */
    function enqueue_scripts() {
        
        wp_enqueue_script( 'jquery-ui-tabs' );
        
    }
    
    /**
     * Init Tabs
     */
    function init_tabs() { ?>
        <script>
        jQuery(function($){
            $("#uniforms-editor-tabs").tabs();
        });
        </script>
    <?php
    }
    
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'uniforms-meta-box',
            esc_html__( 'UniForms', 'uniforms' ),
            array( $this, 'render_metabox' ),
            'uniforms',
            'advanced',
            'default'
        );
    }
    
    /**
     * Message Body
     *
     * @since 1.0.0
     */
    function get_mail( $field = null ) {
        global $post;
        
        $site_name      = esc_attr( get_option( 'blogname' ) );
        $site_url       = esc_url( site_url() );
        $admin_email    = esc_attr( get_option( 'admin_email' ) );
        
        $defaults = array(
            'recipient' => $admin_email,
            'sender' => '[your-name] <'. $admin_email .'>',
            'subject' => $site_name . ' [your-subject]',
            'additional_headers' => 'Reply-To: [your-email]',
            'message_body' => 'From: [your-name] <[your-email]>&#13;&#10;Subject: [your-subject]&#13;&#10;&#13;&#10;Message Body:&#13;&#10;[your-message]&#13;&#10;&#13;&#10;--&#13;&#10;This e-mail was sent from a contact form on '. $site_name .' ('. $site_url .')'
        );
        
        $output = $defaults[ $field ];
        $mail   = get_post_meta( $post->ID, '_uniforms_mail', true );
        $mail   = unserialize( $mail );
        
        if( ! empty( $mail[ $field ] ) ) {
            $output = $mail[ $field ];
        }
        
         echo $output;
    }
    
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        global $post;
        
        $title = $post->post_title ? ' title="'. esc_html( $post->post_title ) .'"': '';
        
        $value = '[uniforms id="'. $post->ID .'"'. $title .']';
        
        // Add nonce for security and authentication.
        wp_nonce_field( 'uniforms_meta_nonce_action', 'uniforms_meta_nonce' ); 
        
        ?>

        <div id="uniforms-shortcode">
            <?php esc_html_e( 'Copy this shortcode and paste it into your post, page or text widget content:', 'uniforms' ); ?>: <input name="uniforms_shortcode" type="text" value="<?php echo esc_attr( $value ); ?>" class="large-text code" readonly>
        </div>
        
        <!-- UniForms Editor Tabs -->
        <div id="uniforms-editor-tabs">
            
            <ul>
                <li><a href="#uniforms-editor"><?php esc_html_e( 'Form', 'uniforms' ); ?></a></li>
                <li><a href="#mail-panel"><?php esc_html_e( 'Mail', 'uniforms' ); ?></a></li>
            </ul>
            
            <!-- UniForms Editor Panel -->
            <div id="uniforms-editor" class="uniforms-editor-panel"></div><!-- UniForms Editor Panel End -->
        
            <!-- UniForms Mail Panel -->
            <div id="mail-panel" class="uniforms-editor-panel">
                
                <h2><?php esc_html_e( 'Mail', 'uniforms' ); ?></h2>
                
                <p><?php esc_html_e( 'You can edit the mail template here.', 'uniforms' ); ?></p>
                <p><?php esc_html_e( 'In the following fields, you can use these mail-tags:', 'uniforms' ); ?><br>
                    <span class="mailtag code">[your-name]</span>
                    <span class="mailtag code">[your-email]</span>
                    <span class="mailtag code">[your-subject]</span>
                    <span class="mailtag code">[your-message]</span>
                </p>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="uniforms-mail-recipient"><?php esc_html_e( 'To', 'uniforms' ); ?></label>
                            </th>
                            <td>
                                <input id="uniforms-mail-recipient" name="uniforms-mail[recipient]" type="text" class="large-text code" value="<?php $this->get_mail( 'recipient' ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="uniforms-mail-sender"><?php esc_html_e( 'From', 'uniforms' ); ?></label>
                            </th>
                            <td>
                                <input id="uniforms-mail-sender" name="uniforms-mail[sender]" type="text" class="large-text code" value="<?php $this->get_mail( 'sender' ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="uniforms-mail-subject"><?php esc_html_e( 'Subject', 'uniforms' ); ?></label>
                            </th>
                            <td>
                                <input id="uniforms-mail-subject" name="uniforms-mail[subject]" type="text" class="large-text code" value="<?php $this->get_mail( 'subject' ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="uniforms-mail-additional-headers"><?php esc_html_e( 'Additional Headers', 'uniforms' ); ?></label>
                            </th>
                            <td>
                                <textarea id="uniforms-mail-additional-headers" name="uniforms-mail[additional_headers]" type="text" class="large-text code" cols="100" rows="4"><?php
                                    $this->get_mail( 'additional_headers' );
                                ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="uniforms-mail-message-body"><?php esc_html_e( 'Message Body', 'uniforms' ); ?></label>
                            </th>
                            <td>
                                <textarea id="uniforms-mail-message-body" name="uniforms-mail[message_body]" type="text" class="large-text code" cols="100" rows="18"><?php
                                    $this->get_mail( 'message_body' );
                                ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
            </div><!-- UniForms Mail Panel End -->
            
        </div><!-- UniForms Editor Tabs -->

        <script>
        var $ = jQuery;
        $(function() {
            var options = {
                <?php if( ! empty( $post->post_content ) ): ?>
                defaultFields: <?php echo $post->post_content; ?>
                <?php endif; ?>
            };
            
            var ufeditor        = document.getElementById('uniforms-editor');
            var UniFormsBuilder = $(ufeditor).formBuilder(options);
            
            document.getElementById('publish').addEventListener('click', function() {
                tinymce.activeEditor.setContent('');
                tinymce.activeEditor.execCommand( 'mceInsertContent', false, UniFormsBuilder.actions.getData('json', true) );
            });
        });
        </script>
    <?php
    }
    
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['uniforms_meta_nonce'] ) ? $_POST['uniforms_meta_nonce'] : '';
        $nonce_action = 'uniforms_meta_nonce_action';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        
        $mail = array();
        $mail = $_POST['uniforms-mail'];
        
        // Sanitize Fields
        $mail['recipient'] = isset( $mail['recipient'] ) ? esc_attr( $mail['recipient'] ) : '';
        $mail['sender'] = isset( $mail['sender'] ) ? esc_attr( $mail['sender'] ) : '';
        $mail['subject'] = isset( $mail['subject'] ) ? esc_attr( $mail['subject'] ) : '';
        $mail['additional_headers'] = isset( $mail['additional_headers'] ) ? esc_attr( $mail['additional_headers'] ) : '';
        $mail['message_body'] = isset( $mail['message_body'] ) ? esc_attr( $mail['message_body'] ) : '';
        
        // Serialize data.
        $mail = serialize( $mail );
        
        // Save data to wp_postmeta.
        update_post_meta( $post_id, '_uniforms_mail', $mail );
    }
    
}
new UniForms_Meta_Box();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
