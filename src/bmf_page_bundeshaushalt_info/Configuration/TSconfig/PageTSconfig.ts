TCEMAIN {
    # Owner be_users UID for new pages: "pixelpark-adm"
    permissions.userid = 3
    # Owner be_groups UID for new pages: "Redaktion"
    permissions.groupid = 1
}

TCEFORM {
    tt_content.layout.types.textmedia {
        removeItems = 1,2,3
        addItems {
            10 = Headline-Overlay
            30 = Spezial-Rendering
            40 = Teaser
        }
    }
    tt_content.layout {
        addItems.20 = Akkordion
    }
}

RTE.default.preset = bundeshaushalt
