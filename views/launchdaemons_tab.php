<div id="launchdaemons-tab"></div>

<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/launchdaemons/launchdaemons" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/launchdaemons/launchdaemons_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2><i class="fa fa-rocket"></i> <span data-i18n="launchdaemons.launchdaemons"></span></h2>
<div style="clear: both"></div>

<!-- Sub-tabs for Launch Daemons and Launch Agents -->
<div id="launchdaemons-tab-content">
    <style>
        /* Prevent text selection cursor on tabs */
        .nav-tabs > li > a {
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" data-target="#launchdaemons-subtab" title="View system-wide daemons that run as root">Launch Daemons</a>
        </li>
        <li>
            <a data-toggle="tab" data-target="#launchagents-subtab" title="View per-user agents that run in user context">Launch Agents</a>
        </li>
    </ul>
    <div class="tab-content" style="margin-top: 15px;">
        <div id="launchdaemons-subtab" class="tab-pane fade in active"></div>
        <div id="launchagents-subtab" class="tab-pane fade"></div>
    </div>
</div>

<script>
$(document).on('appReady', function(){
    // Get the original hash when the page loads
    const originalHash = window.location.hash || '#tab_launchdaemons-tab';

    // Use a more specific selector for just our tab container
    $('#launchdaemons-tab-content .nav-tabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
        // Restore the original hash
        if (window.location.hash !== originalHash) {
            history.pushState(null, null, originalHash);
        }
    });

    // Cache common selectors (if needed)
    const $launchdaemonsCnt = $('#launchdaemons-cnt');
    
    // Define properties to skip and boolean properties
    const skipThese = ['id', 'serial_number', 'label'];
    const booleanProps = ['disabled', 'ondemand', 'runatload', 'startonmount', 'keepalive'];
    
    $.getJSON(appUrl + '/module/launchdaemons/get_tab_data/' + serialNumber, function(data){
        // Store data globally for modal access
        window.launchdaemonsData = data;
        
        // Update record count
        $launchdaemonsCnt.text(data.length);
        
        // Prepare two separate arrays – one for daemons and one for agents
        const daemonChunks = [];
        const agentChunks = [];
        
        for (let i = 0, len = data.length; i < len; i++) {
            const d = data[i];
            const isDaemon = d.path.includes('LaunchDaemons');
            
            // Build record header and content
            let recordChunk = `<h4><i class="fa fa-paper-plane"></i> ${d.label}</h4><ul class="list-group">`;
            
            // Add record properties one by one
            for (const prop in d) {
                if (skipThese.includes(prop)) continue;
                if ((d[prop] === '' || d[prop] === null) && d[prop] !== "0") continue;
                
                let item = `<li class="list-group-item"><strong>${i18n.t('launchdaemons.' + prop)}:</strong> `;
                if (prop === "startinterval" && +d[prop] >= 60) {
                    const duration = moment.duration(+d[prop], "seconds").humanize();
                    item += `<span title="${d[prop]} ${i18n.t('launchdaemons.seconds')}">${duration}</span>`;
                }
                else if (booleanProps.includes(prop)) {
                    const boolVal = (d[prop] == 1 || d[prop] === true);
                    item += i18n.t(boolVal ? 'yes' : 'no');
                }
                else if (prop === 'daemon_json') {
                    item += `<button type="button" class="btn btn-info btn-xs view-daemon" data-index="${i}">${i18n.t('launchdaemons.view_button')}</button>`;
                }
                else {
                    item += d[prop];
                }
                recordChunk += item + '</li>';
            }
            recordChunk += '</ul>';
            
            // Split into the two sub-tabs based on path
            if (isDaemon) {
                daemonChunks.push(recordChunk);
            } else {
                agentChunks.push(recordChunk);
            }
        }
        
        // Insert the dynamic content into the respective sub-tabs
        $('#launchdaemons-subtab').html(daemonChunks.join(''));
        $('#launchagents-subtab').html(agentChunks.join(''));
    });
});

// Handle the "view" button clicks to show modal details (code omitted for brevity)
$(document).on('click', '.view-daemon', function(e){
    e.preventDefault();
    const index = parseInt($(this).attr('data-index'), 10);
    const record = window.launchdaemonsData[index];
    if (record?.daemon_json) {
        const $modal = $('#myModal');
        const $dialog = $modal.find('.modal-dialog');
        const $title = $modal.find('.modal-title');
        const $body = $modal.find('.modal-body');
        const $okBtn = $modal.find('button.ok');
        $dialog.addClass('modal-lg');
        $title.empty().append(record.label);
        $body.empty().append(record.daemon_json.replace(/\n/g, '<br>'));
        $okBtn
            .text(i18n.t("dialog.close"))
            .off()
            .click(() => $modal.modal('hide'));
        $modal.modal('show');
    }
    return false;
});
</script>

