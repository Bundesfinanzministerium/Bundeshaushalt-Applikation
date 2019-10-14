lib.nav_accessibility = COA
lib.nav_accessibility {
    wrap = <div id="meta-accessibility"><ul>|</ul></div>

    10 = HMENU
    10 {
        special = list
        special.value = {$bmfPage.acessibilitylinks}
        includeNotInMenu = 1

        1 = TMENU
        1 {
            expAll = 0
            NO.ATagTitle.field = abstract
            NO.wrapItemAndSub = <li class="gebaerdensprache">|</li>||<li class="leichtesprache">|</li>
            NO.stdWrap.htmlSpecialChars = 1

            CUR < .NO
            CUR = 1
            CUR.allWrap = <strong>|</strong>
            CUR.stdWrap.htmlSpecialChars = 1
            CUR.doNotLinkIt = 1
        }
    }
}