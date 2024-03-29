<?php
$output = $css_class ='';

extract(shortcode_atts(array(
	'type'=>'date',
	'control_label'=>'',
	'control_name'=>'',
	'placeholder'=>'',
	'help_text'=>'',
	'required'=>'',
	'readonly'=>'',
	'attributes'=>'',
	'el_class'=> '',
), $atts));

wp_enqueue_style('dhvc-form-datetimepicker');
wp_enqueue_script('dhvc-form-datetimepicker');

global $dhvc_form;

$name = esc_attr($control_name);
$label = esc_html($control_label);

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $this->settings['base'].' '. $el_class,$this->settings['base'],$atts );

$picker_class = $type=='date' ? 'dhvc-form-datepicker' : 'dhvc-form-timepicker';
$picker_attrs = '';
if($type == 'date'){
	$picker_attrs = ' data-year-start="'. apply_filters('dhvc_form_datepicker_year_start', '1950', $dhvc_form, $name).'" data-year-end="'. apply_filters('dhvc_form_datepicker_year_end', '2050', $dhvc_form, $name).'" data-min-date="'. apply_filters('dhvc_form_datepicker_min_date', 'false', $dhvc_form, $name).'" data-max-date="'. apply_filters('dhvc_form_datepicker_max_date', 'false', $dhvc_form, $name).'"';
}else {
	$picker_attrs = ' data-min-time="'. apply_filters('dhvc_form_timepicker_min_time', 'false', $dhvc_form, $name).'" data-max-time="'. apply_filters('dhvc_form_timepicker_max_time', 'false', $dhvc_form, $name).'"';
}

$output .='<div class="dhvc-form-group dhvc-form-'.$name.'-box '.$css_class.'">'."\n";

if(!empty($label)){
	$output .='<label class="dhvc-form-label" for="dhvc_form_control_'.$name.'">'.$label.(!empty($required) ? ' <span class="required">*</span>':'').'</label>' . "\n";
}
$output .='<div class="dhvc-form-input dhvc-form-has-add-on">'."\n";
$output .= '<input type="text" id="dhvc_form_control_'.$name.'" '.$picker_attrs.' name="'.$name.'" '
		.' class="dhvc-form-control dhvc-form-value dhvc-form-control-'.$name.' '.$picker_class.' dhvc-form-control'.(!empty($required) ? ' dhvc-form-required-entry':'').'" '.(!empty($required) ? ' required aria-required="true"':'').' '.(!empty($readonly) ? ' readonly':'').' placeholder="'.$placeholder.'" '.$attributes.'>' . "\n";
if($type =='date'){
$output .='<span class="dhvc-form-add-on"><i class="fa fa-calendar"></i></span>'."\n";
}else{
$output .='<span class="dhvc-form-add-on"><i class="fa fa-clock-o"></i></span>'."\n";
}
$output .='</div>'."\n";

if(!empty($help_text)){
	$output .='<span class="dhvc-form-help">'.$help_text.'</span>' . "\n";
}
$output .='</div>'."\n";

echo $output;
