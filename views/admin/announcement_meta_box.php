<?php require_once(WPANN_PLUGIN_PATH . '/utils/dynamic-input-fields.php'); ?>
<div class="wpann-flex wpann-flex-wrap wpann-box-border">
    <div class="wpann-w-1/5">
        <div class="wpann-flex-col wpann-mr-2 wpann-bg-white wpann-border-solid wpann-border-y-0 wpann-border-r-0 wpann-border-black wpann-rounded wpann-shadow-[rgba(7,_65,_210,_0.1)_0px_9px_30px]">
            <div data-box="wpann_layout" class="wpann-tab-btn wpann-cursor-pointer wpann-transition-all wpann-bg-slate-100 hover:wpann-bg-slate-100 wpann-px-6 wpann-py-3 wpann-space-x-2"><span class="dashicons dashicons-align-wide"></span></i> <span>Layout</span></div>
            <div data-box="wpann_content" class="wpann-tab-btn wpann-cursor-pointer wpann-transition-all hover:wpann-bg-slate-100 wpann-px-6 wpann-py-3 wpann-space-x-2"><span class="dashicons dashicons-welcome-write-blog"></span></i> <span>Content</span></div>
            <div data-box="wpann_styles" class="wpann-tab-btn wpann-cursor-pointer wpann-transition-all hover:wpann-bg-slate-100 wpann-px-6 wpann-py-3 wpann-space-x-2"><span class="dashicons dashicons-art"></span></i> <span>Styles</span></div>
            <div data-box="wpann_settings" class="wpann-tab-btn wpann-cursor-pointer wpann-transition-all hover:wpann-bg-slate-100 wpann-px-6 wpann-py-3 wpann-space-x-2"><span class="dashicons dashicons-admin-generic"></span></span></i> <span>Settings</span></div>
        </div>
    </div>
    <div class="wpann-w-4/5 wpann-px-10 wpann-py-6 wpann-bg-white wpann-shadow-[rgba(7,_65,_210,_0.1)_0px_9px_30px] wpann-rounded wpann-box-border">

        <?php
        foreach ($tabs['dynamic'] as $tab_key => $templates) :;
        ?>
            <!-- Content data start -->
            <div id="wpann_<?php echo esc_attr($tab_key) ?>" class="<?php echo $tab_key == 'layout' ? '' : 'wpann-hidden'; ?> data-box">
                <?php
                foreach ($templates as $template_key => $template) :
                ?>
                    <!-- Template wise fields start -->
                    <div class="dynamic_fields_container <?php echo esc_attr($template_key); ?> wpann-hidden">
                        <?php
                        foreach ($template as $field_key => $field) :
                            $name = $template_key . '_' . $field_key;
                            $value = isset($data[$name]) ? $data[$name] : $field['value'];
                        ?>
                            <!-- Input field start -->
                            <?php wpann_metabox_input_field($field['label'], $field['type'], $name, 'Enter ' . $field['label'], $value, $field['options'] ?? [], $tab_key == 'styles', $field['note'] ?? ''); ?>
                            <!-- Input field end -->
                        <?php endforeach; ?>
                    </div>
                    <!-- Template wise fields end -->
                <?php endforeach; ?>

                <!-- Common content fields start -->
                <?php if (is_array($tabs['common'][$tab_key]) && count($tabs['common'][$tab_key]) > 0) :
                    foreach ($tabs['common'][$tab_key] as $field_key => $field) :
                        $value = isset($data[$field_key]) ? $data[$field_key] : $field['value'];
                ?>
                        <!-- Input field start -->
                        <?php wpann_metabox_input_field($field['label'], $field['type'], $field_key, 'Enter ' . $field['label'], $value, $field['options'] ?? [], $tab_key == 'styles', $field['note'] ?? ''); ?>
                        <!-- Input field end -->
                <?php endforeach;
                endif; ?>
                <!-- Common content fields end -->
            </div>
            <!-- Content data end -->
        <?php endforeach; ?>
    </div>
</div>
<script>
    /**
     * Implement multilayout system
     */
    const layoutValue = '<?php echo isset($data['layout']) ? esc_attr($data['layout']) : '' ?>';
    if (layoutValue) {
        const selectedLayout = document.querySelectorAll('.' + layoutValue);
        if (selectedLayout.length > 0) {
            document.querySelector("input[name=layout][value=" + layoutValue + "]").setAttribute('checked', 'checked');
            selectedLayout.forEach(item => {
                item.classList.remove('wpann-hidden')
            });
        }
    } else {
        const inputField = document.querySelector("input[name=layout]");
        inputField.setAttribute('checked', 'checked');
        document.querySelectorAll('.' + inputField.value).forEach(item => {
            item.classList.remove('wpann-hidden')
        });
    }

    /**
     * Implement multiple page select system
     */
    const multiselectinput = document.querySelectorAll('.multi-select-input')
    if (multiselectinput.length > 0) {
        multiselectinput.forEach(item => {
            item.setAttribute('multiple', 'multiple');
        })

        //select values
        const values = '<?php echo isset($data['page_ids']) ? $data['page_ids'] : '' ?>'
        if (values.length > 0) {
            if (values.indexOf(',') != -1) {
                const optionVals = values.replaceAll('"', '').split(',')
                optionVals.forEach(valItem => {
                    document.querySelector('.multi-select-input option[value="' + valItem + '"]').setAttribute('selected', true)
                })
            } else {
                document.querySelector('.multi-select-input option[value=' + values + ']').setAttribute('selected', true)
            }
        }
    }
</script>