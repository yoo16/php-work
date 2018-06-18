var is_show_sortable = false;

/**
 * hide sortable
 *
 * @param
 * @return
 */
 $(function(){
    $('.update-sortable').hide();
    $('.close-sortable').hide();
});

//TODO model
var sortable_table_tr_selector
var sortable_table_id;
var sortable_selector;
var before_sort_orders;
var after_sort_orders;
var is_show_sortable = false;

/**
 * change slortable
 *
 * @param
 * @return
 */
 $(document).on('click', '.change-sortable', function() {
    if (is_show_sortable) return;

    sortable_table_id = $(this).attr('table_id');
    if (!sortable_table_id) sortable_table_id = 'sortable-table';
    sortable_selector = '#' + sortable_table_id + ' tbody';
    sortable_table_tr_selector = sortable_selector + ' tr';

    $(sortable_selector).sortable({
        cursor: 'move',
        opacity: 0.8,
        placeholder: 'sortable-selected',
        scroll: true,
        axis: 'y',
        delay: 100
    });
    $(sortable_selector).sortable('enable');
    $(sortable_selector).sortable({
        update: function(ev, ui) {
            after_sort_orders = $(sortable_selector).sortable('toArray');
        }
    });

    before_sort_orders = $(sortable_selector).sortable('toArray');
    after_sort_orders = before_sort_orders;

    $('.update-sortable').show();
    $('.close-sortable').show();
    $('.sortable-control').show();

    showSortableControl(sortable_table_id);

    /**
     * sortable control
     * 
     * @return void
     */
    function showSortableControl(table_id) {
        is_show_sortable = true;
        var header_selector = '#' + table_id + ' tr';
        var header_tr = $(header_selector).first();

        var sortable_control_header_tag = '<th class="sortable-control"></th>';
        if (header_tr) header_tr.prepend(sortable_control_header_tag);

        var sortable_control_tag = '<td class="sortable-control" nowrap="nowrap"></td>';
        $(sortable_table_tr_selector).map(function() {
            $(this).prepend(sortable_control_tag);
        });

        var link_tag = '';
        link_tag+= '<a><i class="fa fa-align-justify"></i></a>';
        link_tag+= '<a class="change-sortable-top btn btn-sm"><i class="fa fa-angle-double-up"></i></a>';
        link_tag+= '<a class="change-sortable-bottom btn btn-sm"><i class="fa fa-angle-double-down"></i></a>';
        $('td.sortable-control').html(link_tag);
        $('.sortable-control').show();
    }

});


$(document).on('click', '.change-sortable-top', function() {
     var first_tr = $(sortable_table_tr_selector).first();
     if (first_tr) {
         var row = $(this).closest('tr');
         row.insertBefore(first_tr);
         after_sort_orders = $(sortable_selector).sortable('toArray');
     }
});

$(document).on('click', '.change-sortable-bottom', function() {
     var last_tr = $(sortable_table_tr_selector).last();
     if (last_tr) {
         var row = $(this).closest('tr');
         row.insertAfter(last_tr);
         after_sort_orders = $(sortable_selector).sortable('toArray');
     }
});

$(document).on('click', '.update-sortable', function() {
    if (!before_sort_orders) return;

    var sort_orders = {};
    $.each(before_sort_orders, function(index, value){
        var update_id = after_sort_orders[index];
        //if (value != update_id) {
            sort_orders[update_id] = index;
        //}
    });
    if (!sort_orders) return;
    
    showLoading();

    var params = {sort_order: sort_orders};
    pw_app.post(this, params, callback);

    function callback(data) {
        $(sortable_selector).sortable('refresh');
        before_sort_orders = after_sort_orders;

        is_show_sortable = false;
        $(sortable_selector).sortable('disable');
        $('.update-sortable').hide();
        $('.close-sortable').hide();
        $('.sortable-control').hide();
        $('.sortable-control').remove();
        hideLoading();
    } 
});

$(document).on('click', '.close-sortable', function() {
    is_show_sortable = false;
    $(sortable_selector).sortable('disable');
    $('.update-sortable').hide();
    $('.close-sortable').hide();
    $('.sortable-control').hide();
    $('.sortable-control').remove();
    hideLoading();
});