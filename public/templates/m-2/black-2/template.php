<?php
/**
 *
 * id: black-2
 * base: m-2
 * title: Black
 *
 */
$main_plugin = ChChPopUpScroll::get_instance();
?>

<?php $overlay = $template_options['overlay']; ?>
<?php  if(!$overlay['hide'] || is_admin()): ?>
<div class="cc-pu-bg m-2 black-2"></div>
<?php endif;?>

<article class="pop-up-cc m-2 black-2 <?php echo $this-> get_template_option('size','size'); ?>">
	<div class="modal-inner">
		<?php $main_plugin->get_close_button($this->lp_post_id); ?>
		<?php $content = $template_options['contents']; ?>
		<div class="cc-pu-header-section">
			<?php echo wpautop($content['header']);?>
		</div>

		<div class="cc-pu-subheader-section">
			<?php echo wpautop($content['subheader']);?>
		</div>

		<div class="cc-pu-content-section cc-pu-content-styles">
			<?php echo wpautop($content['content']);?>
		</div>

		<?php $main_plugin->get_newsletter_form($template_options, $this->lp_post_id, $this);	 ?>
		<footer class="cc-pu-privacy-info">
			<?php if(!empty($content['privacy_link']) || is_admin()):?>
				<a href="<?php echo $content['privacy_link'];?>"><?php echo $this-> get_template_option('contents','privacy_link_label'); ?></a>
			<?php endif;?>
			<div class="cc-pu-privacy-section cc-pu-content-styles">
				<?php echo wpautop($content['privacy_message']);?>
			</div>
		</footer>
	</div>
</article>
