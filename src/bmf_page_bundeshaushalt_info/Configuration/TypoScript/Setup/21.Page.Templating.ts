lib.subheader = TEXT
lib.subheader.data = page:subtitle

lib.pidHome = TEXT
lib.pidHome.value = {$globalPids.mainHome}

lib.pidArticle = TEXT
lib.pidArticle.value = {$globalPids.mainArticle}

lib.footerCopyright = COA
lib.footerCopyright {
    10 = TEXT
    10.value = ©

    20 = TEXT
    20.data = date:U
    20.strftime = %Y
    20.noTrimWrap = | | |

    30 = TEXT
    30.value = {$footer.copyright}
}

lib.pidLogo < lib.pidHome
[page["uid"] == {$globalPids.mainHome}]
    lib.pidLogo >
[end]


lib.pageTyp = CASE
lib.pageTyp {
    key.data = levelfield:-1, backend_layout_next_level, slide
    key.override.field = backend_layout
    default = TEXT
    default.value = none
    file__10_Startseite = TEXT
    file__10_Startseite.value = home
    file__20_Artikelseite = TEXT
    file__20_Artikelseite.value = article
    file__30_Zweispaltig = TEXT
    file__30_Zweispaltig.value = article
}

page.10 = FLUIDTEMPLATE
page.10 {

    layoutRootPath  = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Layouts/
    partialRootPath = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Partials/

    variables {

        pageTyp         = < lib.pageTyp
        pidHome         = < lib.pidHome
        pidArticle      = < lib.pidArticle
        pidLogo         = < lib.pidLogo
        primenu         = < lib.nav_primary
        primenustart    = < lib.nav_primary_home
        access          = < lib.nav_accessibility
        accessmobile    = < lib.nav_accessibilityMobile
        subheader       = < lib.subheader
        contact         = < lib.contact
        copyright       = < lib.footerCopyright
        metaNav         = < lib.metanav
        searchbox       = < lib.searchbox
        searchboxm      = < lib.searchboxm
        bcArticle       = < lib.breadcrcumbArticle

        homeData    < styles.content.get
        homeData.select.where = colPos=22

        content < styles.content.get
        content.select.where = colPos=30
        content2 < styles.content.get
        content2.select.where = colPos=31

        main < styles.content.get
        main.select.where = colPos=10

        col1 < styles.content.get
        col1.select.where = colPos=11
        col2 < styles.content.get
        col2.select.where = colPos=12
        col3 < styles.content.get
        col3.select.where = colPos=13
        col4 < styles.content.get
        col4.select.where = colPos=14

        col5 < styles.content.get
        col5.select.where = colPos=15
        col6 < styles.content.get
        col6.select.where = colPos=16
        col7 < styles.content.get
        col7.select.where = colPos=17
    }
}

page.10.variables.teaser = COA
page.10.variables.teaser {
    11 < styles.content.get
    11 {
        select.where = colPos=11
        select.pidInList = {$globalPids.teaser}
        stdWrap.wrap = <div class="grid_3">|</div>
    }
    12 < .11
    12.select.where = colPos=12
    13 < .11
    13.select.where = colPos=13
    14 < .11
    14.select.where = colPos=14

    stdWrap.wrap = <div class="row">|</div>
}


page.10.file.stdWrap.cObject = CASE
page.10.file.stdWrap.cObject {

    key.data           = levelfield:-1, backend_layout_next_level, slide
    key.override.field = backend_layout

    default = TEXT
    default.value = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Templates/Artikelseite.html

    file__10_Startseite = TEXT
    file__10_Startseite.value = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Templates/Startseite.html

    file__20_Artikelseite = TEXT
    file__20_Artikelseite.value = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Templates/Artikelseite.html

    file__30_Zweispaltig = TEXT
    file__30_Zweispaltig.value = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Templates/Zweispaltig.html
}

lib.pageid = TEXT
lib.pageid.data = page:uid
