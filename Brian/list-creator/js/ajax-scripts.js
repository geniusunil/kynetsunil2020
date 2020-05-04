function load_graph(listID,duration)
{
    console.log(listID);
    var data = {
        'action': 'get_graph_data',
        'listID':listID,
        'duration':duration
    };
    jQuery.post(ajax_object.ajax_url, data, function(redata) {
        if (redata.trim()) {
            //console.log(redata);
            var d = jQuery.parseJSON(redata);
            var footer = d['fdata'];
            var votes = d['votes'];
            var label = d['labels'];
            console.log(d);
            new Morris.Bar({
                element: 'morrisjs_graph',
                data: d['gdata'],
                xkey: 'y',
                ykeys: ['a'],
                labels: ['%'],
                xLabelMargin :2,
                resize:true,
                gridTextColor: '#d9e8f3',

                xLabelFormat: function(x){
                    // console.log(label[x.x])
                    return x.label;
                    return '';
                },

                hoverCallback: function(index, options, content) {
                    //return options.data[index]['a']+' of all users<br> recommend  <br>'+options.data[index]['y']+'<br><img src="'+imagepath+'" style="width:30px;height:30px;margin-bottom: -28px;" />';
					/*jQuery('rect').each(function(){
					 jQuery(this).attr('data-tooltip','Value');
					 });*/
                    var tool = options.data[index]['a']+' of all users<br> recommend  <br>'+options.data[index]['y'];
                    return '<span class"tooltiptext">'+tool+'</span><span class="tooltiptextspan"></span>';
                    //return '';
                },
            });

            jQuery('#morrisjs_graph_data').html(footer);
            jQuery('.votes_calculatr').html(votes);

            resize_bars();

        }
    });


}