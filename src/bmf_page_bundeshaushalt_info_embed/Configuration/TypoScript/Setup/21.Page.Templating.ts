page.10 = FLUIDTEMPLATE
page.10 {
    layoutRootPath  = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Private/Layouts/
    partialRootPath = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Private/Partials/

    variables {
        content     < styles.content.get
        content.select.where = colPos=0
    }
}

page.10.file.stdWrap.cObject = CASE
page.10.file.stdWrap.cObject {

    key.data           = levelfield:-1, backend_layout_next_level, slide
    key.override.field = backend_layout

    default = TEXT
    default.value = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Private/Templates/Embed.html

    1 = TEXT
    1.value = EXT:bmf_page_bundeshaushalt_info_embed/Resources/Private/Templates/Embed.html


}
