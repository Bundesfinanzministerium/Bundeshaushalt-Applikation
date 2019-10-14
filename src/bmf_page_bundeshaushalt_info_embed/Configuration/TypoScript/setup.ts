# system
<INCLUDE_TYPOSCRIPT: source="DIR:EXT:bmf_page_bundeshaushalt_info_embed/Configuration/TypoScript/Setup/" extensions="ts">

# extensions
<INCLUDE_TYPOSCRIPT: source="DIR:EXT:bmf_page_bundeshaushalt_info_embed/Configuration/Extensions/css_styled_content/TypoScript/" extensions="ts">
<INCLUDE_TYPOSCRIPT: source="DIR:EXT:bmf_page_bundeshaushalt_info_embed/Configuration/Extensions/piwik/TypoScript/" extensions="ts">


plugin.tx_bmfpagebundeshaushaltinfoembed.view {
    templateRootPaths.0 = {$plugin.tx_bmfpagebundeshaushaltinfoembed.view.templateRootPaths.default}
    partialRootPaths.0 = {$plugin.tx_bmfpagebundeshaushaltinfoembed.view.partialRootPaths.default}
    layoutRootPaths.0 = {$plugin.tx_bmfpagebundeshaushaltinfoembed.view.layoutRootPaths.default}
}
