jQuery(document).ready(function ($) {
    /**
     * Handle tabs
     */
    $('.wpann-tab-btn').click(function(e){
        e.preventDefault();

        //active style
        $('.wpann-tab-btn').removeClass('wpann-bg-slate-100')
        $(this).addClass('wpann-bg-slate-100')

        //handle boxes
        $('.data-box').hide();
        $('#'+$(this).attr('data-box')).show();
    });

    /**
     * Handle layout fields
     */
    $('.layout_input').change(function(e){
        const selector = '.'+e.target.value;
        const layout = $(selector);
        if(layout){
            $('.dynamic_fields_container').addClass('wpann-hidden');
            $(selector).removeClass('wpann-hidden');
        }
    });

    /**
     * Dynamic field for sliding messages
     */
    $('.add_msg_field_btn').click(function(e){
        e.preventDefault();
        const item = $(this).parent().find('.msg_item').first().clone();
        item.find('input').first().val('');
        $(this).parent().find('.msg_fields_container').append(item);
    });
    
    $('.msg_fields_container').on('click', '.delete_msg_field_btn',function(e){
        e.preventDefault();
        if($(this).parent().parent().children().length > 1){
            $(this).parent().remove();
        } else {
            alert("You can't delete the last field.")
        }
    });
});