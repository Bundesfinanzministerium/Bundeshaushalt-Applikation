lib.breadcrcumbArticle = HMENU
lib.breadcrcumbArticle {
    special = rootline
    special.range = 0 | -1
    includeNotInMenu = 0

    1 = TMENU
    1 {
        wrap = <ul class="bcArticle">|</ul>

        NO.stdWrap.override = Bundeshaushalt ||
        NO.allWrap =  <li class="breadcrumb-item">|</li> |*| <li class="breadcrumb-item">|</li> |*| <li class="breadcrumb-item"><strong>|</strong></li>
        NO.doNotLinkIt = 0 |*| 0 |*| 1
    }
}
