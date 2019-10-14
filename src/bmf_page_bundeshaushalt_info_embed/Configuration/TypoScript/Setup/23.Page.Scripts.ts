page {
    includeJSLibs {
        file_10 = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Public/Scripts/Vendor/modernizr.custom.81557.js
        file_20 = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Public/Scripts/Vendor/jquery-1.8.3.min.js
        file_40 = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Public/Scripts/ppBh.piwik.js
    }
}

[{$config.piwik.enable} == 1]
page {
    996 = USER
    996 {
        userFunc = PPKOELN\BmfPageBundeshaushaltInfo\Tracking\Tracker->showTrackingSnippet
    }
}
[end]
