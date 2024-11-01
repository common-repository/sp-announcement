<?php

function wpann_metabox_input_field($label, $type = 'text', $name, $placeholder = '', $value = '', $options = [], $in_row = false, $note = '')
{ ?>
    <div class="<?php echo $in_row ? 'wpann-flex wpann-items-center' : ''; ?> wpann-mb-8 wpann-mt-4">
        <label for="<?php echo esc_attr($name); ?>" class="wpann-inline-block wpann-mb-2 wpann-font-bold <?php echo $in_row ? 'md:wpann-w-1/2' : ''; ?>"><?php echo esc_html($label); ?></label>
        <?php switch ($type):
            case ($type == 'select'): ?>
                <select name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="<?php echo $in_row ? 'md:wpann-w-1/2' : ''; ?> wpann-w-full wpann-py-2 wpann-px-4">
                    <?php
                    foreach ($options as $option_value => $option_label) : ?>
                        <option value="<?php echo esc_attr($option_value) ?>" <?php selected($value, $option_value); ?>><?php echo esc_html($option_label) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php
                break;
            case ($type == 'multiselect'): ?>
                <select name="<?php echo esc_attr($name); ?>[]" multiple id="<?php echo esc_attr($name); ?>" class="<?php echo $in_row ? 'md:wpann-w-1/2' : ''; ?> wpann-w-full wpann-py-2 wpann-px-4">
                    <?php
                    foreach ($options as $option_value => $option_label) : ?>
                        <option value="<?php echo esc_attr($option_value) ?>" <?php echo $value && in_array($option_value, json_decode($value)) ? 'selected' : '' ?>><?php echo esc_html($option_label) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php
                break;
            case 'loop_text': ?>
                <div>
                    <div class="msg_fields_container wpann-space-y-2">
                        <?php
                        if ($value) :
                            $value = json_decode($value);
                            foreach ($value as $msg_item) : ?>
                                <div class="msg_item wpann-flex wpann-space-x-2">
                                    <input type="text" name="<?php echo esc_attr($name); ?>[]" id="<?php echo esc_attr($name); ?>" class="wpann-w-full wpann-py-2 wpann-px-4" placeholder="Write here." value="<?php echo esc_attr($msg_item); ?>">
                                    <button class="delete_msg_field_btn wpann-bg-red-500 wpann-p-1 wpann-text-xs wpann-rounded wpann-text-white wpann-cursor-pointer wpann-border-none"><span class="dashicons dashicons-trash"></span></button>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="msg_item wpann-flex wpann-space-x-2">
                                <input type="text" name="<?php echo esc_attr($name); ?>[]" id="<?php echo esc_attr($name); ?>" class="wpann-w-full wpann-py-2 wpann-px-4" placeholder="Write here." value="<?php echo esc_attr($value); ?>">
                                <button class="delete_msg_field_btn wpann-bg-red-500 wpann-p-1 wpann-text-xs wpann-rounded wpann-text-white wpann-cursor-pointer wpann-border-none"><span class="dashicons dashicons-trash"></span></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="add_msg_field_btn wpann-bg-green-500 wpann-p-1 wpann-text-xs wpann-rounded wpann-text-white wpann-cursor-pointer wpann-border-none wpann-mt-2"><span class="dashicons dashicons-plus-alt2"></span></button>
                </div>
            <?php
                break;
            case 'multi_dropdown_pages':
                $args = array(
                    'name'             => esc_attr($name) . '[]',
                    'id'               => esc_attr($name) . '',
                    'class'            => 'widefat multi-select-input',
                    'echo'             => 0,
                    'show_option_none' => 'Select',
                    'option_none_value' => '',
                );
                echo wp_dropdown_pages($args); ?>
            <?php
                break;
            case 'image_selector': ?>
                <?php
                foreach ($options as $option_value => $option_settings) :
                    if ($option_settings['type'] == 'text') : ?>
                        <label class="wpann-mb-8 wpann-mt-4 wpann-flex wpann-items-center">
                            <div class="wpann-my-auto wpann-mr-4">
                                <input <?php echo isset($option_settings['disable']) ? 'disabled': '';?> class="layout_input" type="radio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_value) ?>">
                            </div>
                            <?php echo esc_html($option_settings['data']); ?>
                        </label>

                    <?php elseif ($option_settings['type'] == 'image') : ?>
                        <label class="wpann-mb-8 wpann-mt-4 wpann-flex wpann-items-center">
                            <div class="wpann-my-auto wpann-mr-4">
                                <input <?php echo isset($option_settings['disable']) ? 'disabled': '';?> class="layout_input" type="radio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option_value) ?>">
                            </div>
                            <img class="wpann-max-w-full wpann-max-h-72" src="<?php echo esc_url($option_settings['data']); ?>" />
                        </label>
                    <?php elseif ($option_settings['type'] == 'content') : ?>
                        <?php echo $option_settings['data']?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php
                break;
            default: ?>
                <input type="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="wpann-w-full min-h-[32px] <?php echo $in_row ? 'md:wpann-w-1/2' : ''; ?> <?php echo $type != 'color' ? 'wpann-py-2 wpann-px-4' : ''; ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>">
        <?php
                break;
        endswitch; ?>
        <?php if ($note != '') : ?>
            <div><?php echo esc_html($note); ?></div>
        <?php endif; ?>
    </div>
<?php }
