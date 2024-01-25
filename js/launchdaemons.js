var format_launchdaemons_startinterval = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();

    if(colvar) {
        col.text(moment.duration(parseInt(colvar), "seconds").humanize())
    } else {
        col.text("")
    }
}
