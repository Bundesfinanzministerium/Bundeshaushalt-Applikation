lib.contact = COA
lib.contact {
    10 = CONTENT
    10.table = tt_content
    10.select {
        pidInList = {$globalPids.kontakt}
        orderBy   = sorting
        where     = colPos=30
        begin     = 0
        max       = 10
    }
    10.wrap = <div class="grid_6">|</div>

    20 < .10
    20 {
        select.where = colPos=31
    }

    wrap = <div class="row">|</div>
}
