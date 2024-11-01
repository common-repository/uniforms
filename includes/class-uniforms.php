<?php

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UniForms Class
 *
 * @since 1.0.0
 */
class UniForms {
    
    /**
     * Build Form Fields
     *
     * @since 1.0.0
     */
    public static function build( $options ) { //print_r( $options );
        $defaults = array(
            'id'            => '',
            'min'           => '',
            'max'           => '',
            'step'          => '',
            'type'          => '',
            'name'          => $options->type .'-'. esc_attr( uniqid() ),
            'rows'          => '',
            'label'         => '',
            'value'         => '',
            'other'         => '',
            'values'        => array(),
            'toggle'        => false,
            'access'        => false,
            'inline'        => false,
            'subtype'       => '',
            'multiple'      => false,
            'required'      => '',
            'helptext'      => false,
            'maxlength'     => '',
            'className'     => '',
            'description'   => '',
            'placeholder'   => ''
        );
        
        $args       = wp_parse_args( $options, $defaults );
        $args['id'] = esc_attr( $args['name'] );
        
        if( $args['min'] ) {
            $args['min'] = ' min="'. esc_attr( $args['min'] ) .'"';
        }
        
        if( $args['max'] ) {
            $args['max'] = ' max="'. esc_attr( $args['max'] ) .'"';
        }
        
        if( $args['step'] ) {
            $args['step'] = ' step="'. esc_attr( $args['step'] ) .'"';
        }
        
        if( $args['required'] ) {
            $args['required'] = ' required';
        }
        
        if( $args['helptext'] ) {
            
        }
        
        if( $args['inline'] ) {
            $args['inline'] = ' uniforms-inline';
        }
        
        if( $args['multiple'] ) {
            $args['multiple'] = ' multiple';
        }
        
        if( $args['type'] !== 'button' && $args['label'] && 
            $args['type'] !== 'header' && $args['label'] && 
            $args['type'] !== 'paragraph' && $args['label'] ) {
            $args['label'] = '<label for="'. $args['id'] .'">'. esc_html( $args['label'] ) .'</label>';
        } 
                                              
        if( $args['description'] ) { }
        
        if( $args['placeholder'] ) {
            $args['placeholder'] = ' placeholder="'. esc_html( $args['placeholder'] ) .'"';
        }
                                              
        if( $args['className'] ) {
            $args['className'] = ' class="'. esc_attr( $args['className'] ) .'"';
        }
                                              
        if( $args['name'] ) {
            $args['name'] = ' name="uniforms['. esc_attr( $args['name'] ) .']"';
        }
                                              
        if( $args['access'] ) { }
                                              
        if( $args['value'] && $args['subtype'] !== 'textarea' ) {
            $args['value'] = ' value="'. esc_attr( $args['value'] ) .'"';
        }
                                              
        if( $args['type'] !== 'header' && $args['subtype'] && 
            $args['type'] !== 'paragraph' && $args['subtype'] ) {
            $args['subtype'] = ' type="'. esc_attr( $args['subtype'] ) .'"';
        }
        
        if( $args['maxlength'] ) {
            $args['maxlength'] = ' maxlength="'. esc_attr( $args['maxlength'] ) .'"';
        }          
                                              
        if( $args['rows'] ) {
            $args['rows'] = ' rows="'. esc_attr( $args['rows'] ) .'"';
        }
        
        switch( $args['type'] ) {
            case 'autocomplete':
                self::autocomplete( $args );
            break;
            case 'button':
                self::button( $args );
            break;
            case 'checkbox-group':
                self::checkbox_group( $args );
            break;
            case 'date':
                self::date( $args );
            break;
            case 'file':
                self::file_upload( $args );
            break;
            case 'header':
                self::header( $args );
            break;
            case 'hidden':
                self::hidden( $args );
            break;
            case 'number':
                self::number( $args );
            break;
            case 'paragraph':
                self::paragraph( $args );
            break;
            case 'radio-group':
                self::radio_group( $args );
            break;
            case 'select':
                self::select( $args );
            break;
            case 'text':
                self::text( $args );
            break;
            case 'textarea':
                self::textarea( $args );
            break;
        }
    }
    
    /**
     * Build Autocomplete Field
     *
     * @since 1.0.0
     */
    public static function autocomplete( $args ) {
        return;
    }
    
    /**
     * Build Button
     *
     * @since 1.0.0
     */
    public static function button( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-button-wrapper uniforms-row">';
        $output .= '<button';
        $output .= ' id="'. $id .'"';
        $output .= $subtype;
        $output .= $className;
        $output .= $name;
        $output .= $value;
        $output .= '>'. esc_html( $label );
        $output .= '</button>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Checkbox Group
     *
     * @since 1.0.0
     */
    public static function checkbox_group( $args ) {
        extract( $args );
        
        if( is_array( $values ) ) {
            echo '<div class="uniforms-checkbox-group-wrapper uniforms-row">';
            echo $label;
            foreach( $values as $value ) {
                
                $id         = strtolower( $name ) .'-'. strtolower( $value->label );
                $checked    = '';
                $output     = '';
                
                if( isset( $value->selected ) ) {
                    $checked = ' checked';
                }
                
                $output .= '<div class="uniforms-checbox-group'. esc_attr( $inline ) .'">';
                
                if( $value->label ) {
                    $output .= '<label ';
                    $output .= 'for="'. esc_attr( strtolower( $name ) ) .'-'. esc_attr( strtolower( $value->label ) ).'">';
                    $output .= esc_html( ucfirst( $value->label ) );
                    $output .= '</label>';
                }
                
                if( $value->value ) {
                    $val = ' value="'. esc_attr( $value->value ) .'"';
                }
                
                $output .= '<input';
                $output .= ' id="'. esc_attr( $id ) .'"';
                $output .= ' type="checkbox"';
                $output .= $name;
                $output .= $val;
                $output .= $className;
                $output .= $checked;
                $output .= $required .'>';
                
                $output .= '</div>';
                
                echo $output;
            }
            echo '</div>';
        }
    }
    
    /**
     * Build Date Input Field
     *
     * @since 1.0.0
     */
    public static function date( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-date-wrapper uniforms-row">';
        $output .= $label;
        $output .= '<input';
        $output .= ' id="'. $id .'"';
        $output .= ' type="date"';
        $output .= $name;
        $output .= $value;
        $output .= $placeholder;
        $output .= $className;
        $output .= $required;
        $output .= '>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build File Upload Field
     *
     * @since 1.0.0
     */
    public static function file_upload( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-file-wrapper uniforms-row">';
        $output .= $label;
        $output .= '<input';
        $output .= ' id="'. $id .'"';
        $output .= $name;
        $output .= $subtype;
        $output .= $placeholder;
        $output .= $className;
        $output .= $multiple;
        $output .= '>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Heading Field
     *
     * @since 1.0.0
     */
    public static function header( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-heading-wrapper uniforms-row">';
        $output .= '<';
        $output .= esc_attr( $subtype );
        $output .= $className;
        $output .= '>';
        $output .= esc_html( $label );
        $output .= '</';
        $output .= esc_attr( $subtype );
        $output .= '>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Hidden Input Field
     *
     * @since 1.0.0
     */
    public static function hidden( $args ) {
        return;
    }
    
    /**
     * Build Number Input Field
     *
     * @since 1.0.0
     */
    public static function number( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-number-wrapper uniforms-row">';
        $output .= $label;
        $output .= '<input';
        $output .= ' id="'. $id .'"';
        $output .= $name;
        $output .= ' type="number"';
        $output .= $className;
        $output .= $value;
        $output .= $min;
        $output .= $max;
        $output .= $step;
        $output .= $required;
        $output .= '>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Paragraph Field
     *
     * @since 1.0.0
     */
    public static function paragraph( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-paragraph-wrapper uniforms-row">';
        $output .= '<'.$subtype;
        $output .= $className;
        $output .= '>';
        $output .= esc_html( $label );
        $output .= '</'. $subtype .'>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Radio Group Fields
     *
     * @since 1.0.0
     */
    public static function radio_group( $args ) {
        extract( $args );
        
        $output  = '';
        $output .= '<div class="uniforms-radio-group-wrapper uniforms-row">';
        $output .= $label;
        foreach( $values as $object ) {
            $output .= '<div class="uniforms-radio-group'. esc_attr( $inline ) .'">';
            $output .= '<label>'. esc_html( $object->label ) . '</label>';
            $output .= '<input';
            $output .= ' type="radio"';
            $output .= $name;
            $output .= $className;
            $output .= '>';
            $output .= '</div>';
        }
        $output .= '</div>';
        
        echo $output;
    }
    
    
    /**
     * Build Select Dropdown Field
     *
     * @since 1.0.0
     */
    public static function select( $args ) {
        extract( $args );
        
        $output .= '<div class="uniforms-checkbox-group-wrapper uniforms-row">';
        $output .= '<select';
        $output .= ' id="'. $id .'"';
        $output .= $className;
        $output .= ' name="'. esc_attr( $name ) .'"';
        $output .= $multiple;
        $output .= $required;
        $output .= '>';
        $output .= $placeholder;
        foreach( $values as $object ) {
            $output .= '<option';
            $output .= ' value="'. esc_attr( $object->value ) .'"';
            $output .= isset( $object->selected ) ? ' selected' : '';
            $output .= '>';
            $output .= esc_html( $object->label );
            $output .= '</option>';
        }
        $output .= '</select>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Text Field
     *
     * @since 1.0.0
     */
    public static function text( $args ) {
        extract( $args );
        
        $output  = '<div class="uniforms-text-wrapper uniforms-row">';
        $output .= $label;
        $output .= '<input';
        $output .= ' id="'. esc_attr( $id ) .'"';
        $output .= $subtype;
        $output .= $name;
        $output .= $value;
        $output .= $className;
        $output .= $maxlength;
        $output .= $required;
        $output .= '>';
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Build Textarea Field
     *
     * @since 1.0.0
     */
    public static function textarea( $args ) {
        extract( $args );

        $output  = '<div class="uniforms-textarea-wrapper">';
        $output .= $label;
        $output .= '<textarea';
        $output .= ' id="'. esc_attr( $id ) .'"';
        $output .= $subtype;
        $output .= $name;
        $output .= $placeholder;
        $output .= $className;
        $output .= $maxlength;
        $output .= $rows;
        $output .= $required;
        $output .= '>';
        $output .= $value;
        $output .= '</textarea>';
        $output .= '</div>';
        
        echo $output;
    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
