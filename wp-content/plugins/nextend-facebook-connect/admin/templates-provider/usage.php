<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProvider */
?>
<div class="nsl-admin-sub-content">

    <h4><?php _e('Shortcode', 'nextend-facebook-connect'); ?></h4>

    <?php
    $shortcodes = array(
        '[nextend_social_login provider="' . $this->getId() . '"]',
        '[nextend_social_login provider="' . $this->getId() . '" style="icon"]',
        '[nextend_social_login provider="' . $this->getId() . '" style="icon" redirect="https://nextendweb.com/"]'
    );
    ?>

    <textarea readonly cols="160" rows="6" class="nextend-html-editor-readonly"
              aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo implode("\n\n", $shortcodes); ?></textarea>


    <h4><?php _e('Simple link', 'nextend-facebook-connect'); ?></h4>

    <?php
    $html = '<a href="' . $this->getLoginUrl() . '" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="' . esc_attr($this->getId()) . '" data-popupwidth="' . $this->getPopupWidth() . '" data-popupheight="' . $this->getPopupHeight() . '">' . "\n\t" . __('Click here to login or register', 'nextend-facebook-connect') . "\n" . '</a>';
    ?>
    <textarea readonly cols="160" rows="6" class="nextend-html-editor-readonly"
              aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo esc_textarea($html); ?></textarea>

    <h4><?php _e('Image button', 'nextend-facebook-connect'); ?></h4>

    <?php
    $html = '<a href="' . $this->getLoginUrl() . '" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="' . esc_attr($this->getId()) . '" data-popupwidth="' . $this->getPopupWidth() . '" data-popupheight="' . $this->getPopupHeight() . '">' . "\n\t" . '<img src="' . __('Image url', 'nextend-facebook-connect') . '" alt="" />' . "\n" . '</a>';
    ?>
    <textarea readonly cols="160" rows="6" class="nextend-html-editor-readonly"
              aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo esc_textarea($html); ?></textarea>

</div>
