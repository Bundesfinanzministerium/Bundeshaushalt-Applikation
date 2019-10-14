##############
#### PAGE ####
##############
page = PAGE
page {
    typeNum = 0

    #shortcutIcon = fileadmin/de.bundeshaushalt/development/Images/icons/favicon.ico
    shortcutIcon = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Images/icons/favicon.ico

    headerData.10 = TEXT
    headerData.10.value (
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link rel="apple-touch-icon" href="fileadmin/de.bundeshaushalt/development/Images/icons/touch-icon-iphone.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="fileadmin/de.bundeshaushalt/development/Images/icons/touch-icon-ipad.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="fileadmin/de.bundeshaushalt/development/Images/icons/touch-icon-iphone4.png" />
    )

}

config.noPageTitle = 2

page.headerData = COA
page.headerData {
    20 = TEXT
    20 {
        field = subtitle // title
        #value = some text in here
        wrap= <title>{$pageTitle}:&nbsp;|</title>
    }
}