<?php
/*
Plugin Name: Responsive Slides
Plugin URI: http://www.osclass.org/
Description: Responsive Slides is a tiny jQuery plugin that creates a responsive slider and works with wide range of browsers including all IE versions from IE6 and up.
Version: 1.5.0
Author: FrinWeb, RajaSekar and trains58554
Author URI: http://www.frinweb.com
Short Name: rslides
Plugin update URI: responsive-slides
*/
require_once( osc_plugins_path() . 'responsive_slides/ModelSlides.php' ) ;

function rslides_install() {
	ModelSlides::newInstance()->import('responsive_slides/struct.sql') ;
	$aFields = array(
	's_internal_name' => 'rslides'
	);
	osc_set_preference('backgroundcolor', '0','rslides','STRING');
	osc_set_preference('bordercolor', '0','rslides','STRING');
	osc_set_preference('borderwidth', '0','rslides','STRING');
	osc_set_preference('shadowcolor', '#333333','rslides','STRING');
	osc_set_preference('width', '0','rslides','STRING');
	osc_set_preference('height', '280','rslides','STRING');
	osc_set_preference('caption', '1','rslides','BOOLEAN');
	osc_set_preference('description', '1','rslides','BOOLEAN');
	osc_set_preference('link', '0','rslides','BOOLEAN');
	osc_set_preference('openin', '0','rslides','BOOLEAN');		
	osc_set_preference('auto', '1','rslides','BOOLEAN');
	osc_set_preference('pager', '0','rslides','BOOLEAN');
	osc_set_preference('navigation', '1','rslides','BOOLEAN');
	osc_set_preference('speed', '500','rslides','STRING');
}

function rslides_uninstall() {
	try {
		Page::newInstance()->deleteByInternalName('rslides');
		ModelSlides::newInstance()->uninstall();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	osc_delete_preference('backgroundcolor','rslides');
	osc_delete_preference('bordercolor','rslides');
	osc_delete_preference('borderwidth','rslides');
	osc_delete_preference('shadowcolor','rslides');
	osc_delete_preference('width','rslides');
	osc_delete_preference('height','rslides');
	osc_delete_preference('caption','rslides');
	osc_delete_preference('description','rslides');
	osc_delete_preference('link','rslides');
	osc_delete_preference('openin','rslides');
	osc_delete_preference('auto','rslides');
	osc_delete_preference('pager','rslides');
	osc_delete_preference('navigation','rslides');
	osc_delete_preference('speed','rslides');
}

function rslides_admin_menu() {
	echo '<h3><a href="#">'.__('Responsive Slides','rslides').'</a></h3>
	<ul>
		<li><a href="'.osc_admin_render_plugin_url("responsive_slides/admin/create.php").'">'. __('Create Slide', 'rslides').'</a></li>
		<li><a href="'.osc_admin_render_plugin_url("responsive_slides/admin/list.php").'">'. __('Manage Slides', 'rslides').'</a></li>
		<li><a href="'.osc_admin_render_plugin_url("responsive_slides/admin/settings.php").'">'. __('Settings', 'rslides').'</a></li>
		<li><a href="'.osc_admin_render_plugin_url("responsive_slides/admin/help.php").'">'. __('Help', 'rslides').'</a></li>
	</ul>';
}

function getSliderBackgroundColor() {
	return(osc_get_preference('backgroundcolor', 'rslides')) ;
}
function getSliderBorderColor() {
	return(osc_get_preference('bordercolor', 'rslides')) ;
}
function getSliderBorderWidth() {
	return(osc_get_preference('borderwidth', 'rslides')) ;
}
function getSliderShadowColor() {
	return(osc_get_preference('shadowcolor', 'rslides')) ;
}	
function getSliderWidth() {
	return(osc_get_preference('width', 'rslides')) ;
}
function getSliderHeight() {
	return(osc_get_preference('height', 'rslides')) ;
}	
function getSliderCaption() {
	return(osc_get_preference('caption', 'rslides')) ;
}
function getSliderDescription() {
	return(osc_get_preference('description', 'rslides')) ;
}
function getSliderLink() {
	return(osc_get_preference('link', 'rslides')) ;
}
function getSliderOpenIn() {
	return(osc_get_preference('openin', 'rslides')) ;
}
function getSliderAuto() {
	return(osc_get_preference('auto', 'rslides')) ;
}	
function getSliderPager() {
	return(osc_get_preference('pager', 'rslides')) ;
}	
function getSliderNavigation() {
	return(osc_get_preference('navigation', 'rslides')) ;
}
function getSliderSpeed() {
	return(osc_get_preference('speed', 'rslides')) ;
}

function responsive_slides() { ?>
	<div id="slider">	
		<div class="rslides_container">
			<ul class="rslides" id="slides">
			<?php $slides = ModelSlides::newInstance()->getSlides(); ?>
			<?php foreach($slides as $slide) { ?>
			<li>
			<?php if(getSliderLink() ==1){ ?>
			<a href="<?php echo $slide['link'];?>" <?php if(getSliderOpenIn() ==1) { echo 'target="_blank"';  }  ?>>
			<img src="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/media/'.$slide['uniqname']; ?>" alt="<?php echo osc_esc_html($slide['imagename']); ?>">
			</a>
			<?php } else {?>	 
			<img src="<?php echo osc_base_url().'oc-content/plugins/responsive_slides/media/'.$slide['uniqname']; ?>" alt="<?php echo osc_esc_html($slide['imagename']); ?>">
			<?php } ?>
				<?php if(getSliderCaption() == 1){ ?>
					<div class="carousel-caption">
		            	<h1><?php echo $slide['caption']; ?></h1>
		                <?php if($slide['description'] != null){ ?>
		                <p><?php echo $slide['description']; ?></p>
		                <?php } ?>
		            </div>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php }

function rslides_head() {
	osc_enqueue_style('responsiveslides', osc_base_url().'oc-content/plugins/responsive_slides/assets/css/responsiveslides.css');	
	osc_register_script('responsivesidesjs',osc_base_url().'oc-content/plugins/responsive_slides/assets/js/responsiveslides.min.js');
	osc_enqueue_script('responsivesidesjs');
}

function rslides_js_Head() { ?>
<style type="text/css">
	#slider {
		background: <?php echo getSliderBackgroundColor(); ?>;
		border: <?php echo getSliderBorderWidth(); ?>px solid <?php echo getSliderBorderColor(); ?>;
		box-shadow: 1px 1px 4px <?php echo getSliderShadowColor(); ?>;
		<?php if(getSliderWidth() != 0){ echo 'width: '.getSliderWidth().'px'; } ?>
	}
	.rslides img {
		<?php if(getSliderHeight() == 0){ echo 'height: 100%;'; } else { echo 'height: '.getSliderHeight().'px'; }?>
	}
</style>
<script type='text/javascript'>
	jQuery(document).ready(function() {
		$("#slides").responsiveSlides({
			<?php if(getSliderAuto() == 0){ echo 'auto: false,'; }?>
			<?php if(getSliderPager() == 1){ echo 'pager: true,'; }?>
			<?php if(getSliderNavigation() == 1){ echo 'nav: true,'; }?>
			speed: <?php echo getSliderSpeed(); ?>,
			namespace: "rslides"
		});
	});
</script>
<?php }		

osc_register_plugin(osc_plugin_path(__FILE__), 'rslides_install');
osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'rslides_uninstall');

//Display hook
osc_add_hook('responsive_slider', 'responsive_slides');

//Header hook
osc_add_hook('header', 'rslides_head');
osc_add_hook('header', 'rslides_js_Head', 10);

// Admin menu
osc_add_hook('admin_menu', 'rslides_admin_menu', 6);
?>