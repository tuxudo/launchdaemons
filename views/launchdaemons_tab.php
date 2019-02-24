<div id="launchdaemons-tab"></div>
<h2 data-i18n="launchdaemons.launchdaemons"></h2>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/launchdaemons/get_tab_data/' + serialNumber, function(data){
        // Set count of launchdaemons
		$('#launchdaemons-cnt').text(data.length);
		var skipThese = ['id','serial_number','label'];
		$.each(data, function(i,d){

			// Generate rows from data
			var rows = ''
			for (var prop in d){
				// Skip skipThese
				if(skipThese.indexOf(prop) == -1){
                    // Do nothing for empty values to blank them
                    if (d[prop] == '' || d[prop] == null){
                        rows = rows

                    // Format seconds
                    } else if((prop == "startinterval") && +d[prop] >= 60){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td><span title="'+d[prop]+' '+i18n.t('launchdaemons.seconds')+'">'+moment.duration(+d[prop], "seconds").humanize()+'</span></td></tr>';

                    // Format Yes booleans
                    } else if((prop == 'disabled' || prop == 'ondemand' || prop == 'runatload' || prop == 'startonmount' || prop == 'keepalive') && d[prop] == 1){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        
                    // Format No booleans
                    } else if((prop == 'disabled' || prop == 'ondemand' || prop == 'runatload' || prop == 'startonmount' || prop == 'keepalive') && d[prop] == 0){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        
                    // Format returns
                    } else if(prop == 'daemon_json' ){
					   rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop].replace(/\n/g,'<br>')+'</td></tr>';
                    
                    // Else, build out rows from items
                    } else {
                        rows = rows + '<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
					}
				}
			}
			$('#launchdaemons-tab')
				.append($('<h4>')
					.append($('<i>')
						.addClass('fa fa-paper-plane'))
					.append(' '+d.label))
				.append($('<div>')
					.append($('<table>')
						.addClass('table table-striped table-condensed')
						.append($('<tbody>')
							.append(rows))))
		})
	});
});
</script>
