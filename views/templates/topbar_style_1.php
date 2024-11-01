<?php

// Prepare the data
$id = get_the_ID();

// generate css for position
$position_css_prop = $template_field('position') == 'top' ? 'top: 0;' : 'bottom: 0;';
$is_sticky = $template_field('is_sticky');
if ($is_sticky == 'yes' && $template_field('position') == 'top') {
    $is_sticky = 'sticky';
} elseif ($is_sticky == 'yes' && $template_field('position') == 'bottom') {
    $is_sticky = 'fixed';
} else {
    $is_sticky = 'relative';
}

$is_sticky_mobile = $template_field('is_sticky_mobile');
if ($is_sticky_mobile == 'yes' && $template_field('position') == 'top') {
    $is_sticky_mobile = 'sticky';
} elseif ($is_sticky_mobile == 'yes' && $template_field('position') == 'bottom') {
    $is_sticky_mobile = 'fixed';
} else {
    $is_sticky_mobile = 'relative';
}
?>

<!-- Styles -->
<style>
    .wpann_notice_container {
        display: none;
        overflow: hidden;
        width: 100%;
        height: <?php echo esc_attr($template_field('height', 'auto')); ?>;
        z-index: 999;
        position: <?php echo esc_attr($is_sticky); ?>;
        <?php echo esc_attr($position_css_prop); ?>left: 0;
        justify-content: center;
        align-items: center;
        padding: 5px 15px;
        background: <?php echo esc_attr($template_field('bg_color', '#fff')); ?>;
        color: <?php echo esc_attr($template_field('text_color')); ?>;
        font-size: <?php echo esc_attr($template_field('text_font_size', 'inherit')); ?>;
    }

    .wpann_notice_container .wpann_notice_msg {
        position: relative;
        top: 0;
        left: 0;
        text-align: center;
    }

    .wpann_notice_container a {
        text-decoration: none;
        font-size: <?php echo esc_attr($template_field('btn_text_font_size', 'inherit')); ?>;
        border-radius: 3px;
        margin-left: 15px;
        padding: 5px 15px;
        background: <?php echo esc_attr($template_field('btn_bg_color')); ?>;
        color: <?php echo esc_attr($template_field('btn_text_color')); ?>;
    }

    .wpann_notice_container #deadline {
        margin-left: 10px;
        font-size: <?php echo esc_attr($template_field('text_font_size', 'inherit')); ?>;
    }

    .wpann_notice_container .wpann_notice_btn_close {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        position: absolute;
        border: none;
        background-color: <?php echo esc_attr($template_field('close_btn_bg_color', '#FFFFFF')); ?>;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 100%;
        top: 50%;
        right: 20px;
        color: <?php echo esc_attr($template_field('close_btn_text_color', '#FFFFFF')); ?>;
        transform: translateY(-50%);
    }

    @media only screen and (max-width: 800px) {
        .wpann_notice_container {
            position: <?php echo esc_attr($is_sticky_mobile); ?>;
            height: auto;
            flex-direction: column;
            padding: 30px 10px 20px;
        }

        .wpann_notice_container a {
            margin: 10px 0;
        }

        .wpann_notice_container #deadline {
            margin-left: 0;
        }

        .wpann_notice_container .wpann_notice_btn_close {
            top: 101%;
            right: 50%;
            transform: translateX(50%);
        }
    }
</style>

<!-- show only if the deadline is not over yet -->
<?php if (((strtotime($common_field('deadline')) >= strtotime('today')) || $common_field('deadline') == '') && ((strtotime($common_field('start_date')) <= strtotime('today')) || $common_field('start_date') == '')) :; ?>
    <!-- Banner/Notice section start -->
    <div class="wpann_notice_container" data-id="<?php echo esc_attr($id)?>" style="display: none;">
        <div class="wpann_notice_msg"><?php echo esc_html(json_decode($template_field('msg'))[0]); ?></div>

        <a href="<?php echo esc_url($common_field('cta_url')); ?>"><?php echo esc_html($common_field('cta_text')); ?></a>

        <?php if ($common_field('deadline') != '') : ?>
            <span id="deadline"></span>
        <?php endif; ?>
        <?php if ($common_field('hide_close_btn') != 'yes') : ?><button class="wpann_notice_btn_close">X</button><?php endif; ?>
    </div>
    <!-- Banner/Notice section end -->
<?php endif; ?>

<!-- Javascript code -->
<script>
    addEventListener('load', async function() {
        let notice_id = '<?php echo esc_attr($id); ?>';
        let notice_msg_arr = JSON.parse(JSON.stringify(<?php echo $template_field('msg'); ?>));
        let notice_msg_interval = (Number(JSON.parse('<?php echo wp_json_encode($template_field('msg_interval')); ?>') || 3) * 1000) || '';
        let hide_mobile = '<?php echo esc_attr($common_field('hide_mobile')); ?>'

        let deadline = '<?php echo esc_attr($common_field('deadline')); ?>'
        let position = '<?php echo esc_attr($template_field('position')); ?>'
        let fixed_header_selector = '<?php echo esc_attr($template_field('fixed_header_selector')); ?>'

        //delay
        let startup_delay = (Number('<?php echo esc_attr($common_field('startup_delay')); ?>') * 1000) || ''
        let auto_close_delay = (Number('<?php echo esc_attr($common_field('auto_close_delay')); ?>') * 1000) || ''

        //geolocation data
        let user_ip = '<?php echo esc_attr($_SERVER['REMOTE_ADDR']); ?>'
        let allowed_countries = JSON.parse(JSON.stringify(<?php echo $common_field('allowed_countries'); ?>) || '[]');
        let allowed_days = JSON.parse(JSON.stringify(<?php echo $common_field('allowed_days'); ?>) || '[]');

        const shouldVisible = await shouldVisibleFilter(user_ip, allowed_countries, allowed_days)
        if(shouldVisible) {
            //init
            setData('notice_id', notice_id)
            setData('position', position)
            setData('fixed_header_selector', fixed_header_selector)
            setData('hide_mobile', hide_mobile)

            // handle startup delay
            startUpDelay(startup_delay)

            // handle auto close delay
            autoCloseDelay(startup_delay, auto_close_delay)


            //handle sliding msg with custom siding animation
            handleSlidingMessage(notice_msg_arr, notice_msg_interval);

            // Handle countdown timer
            handleCountDown(deadline);

            //show the notice section
            handleNoticeVisibility()

            //close notice section
            handleClose()

            // call function to calculate and fix header issue
            fixHeaderIssue()

            //handle resize
            handleResize()
        }
    });
</script>