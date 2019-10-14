page {

    includeJSLibs {
        file_01 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/modernizr.custom.81557.js
        # file_02 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery-1.12.4.js
        file_02 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery-1.12.4.min.js
        file_03 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery-migrate-1.0.0.js
        file_04 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/superfish/superfish_mod_hh.js
        file_05 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/superfish/supersubs.js
        file_06 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.mobileselect.js
        file_07 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/smartmenus/jquery.smartmenus.js
        file_08 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/smartmenus/addons/keyboard/jquery.smartmenus.keyboard.js
        #file_45 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.address-1.5.js
        #file_44 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.address-1.5.min.js
        #file_47 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.scrollTo.js
        #file_48 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.tablesorter.js
        #file_50 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.ppPageflip.js
        file_09 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.ppContact.js
        file_10 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.ppGlossar.js
        file_11 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/jquery.toggleAttr.js
        file_12 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/accordion.js
        file_13 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/slick/slick.js
        file_14 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/slickslider.js
        file_15 = EXT:bmf_page_bundeshaushalt_info/Resources/Public/Scripts/global.inc.js
    }

}

[{$config.piwik.enable} == "1"]
    page {
        996 = USER
        996 {
            userFunc = PPKOELN\BmfPageBundeshaushaltInfo\Tracking\Tracker->showTrackingSnippet
        }
    }
[end]
