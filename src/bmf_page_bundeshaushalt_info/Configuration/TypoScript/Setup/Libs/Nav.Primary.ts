lib.nav_primary = COA
lib.nav_primary {
    wrap = <nav role="navigation"><h2 class="sr-hint">Hauptnavigation</h2><ul id="main-menu" class="sm sm-clean startmenue"><li><a href="/">Bundeshaushalt</a><ul><li><a href="/#/2018/soll/einnahmen/einzelplan.html">Einnahmen</a></li><li><a href="/#/2018/soll/ausgaben/einzelplan.html">Ausgaben</a></li></ul></li>|</ul></nav>

    10 = HMENU
    10 {
        special = list
        special.value = {$bmfPage.NavPrimary}
        includeNotInMenu = 1
        excludeUidList = 4

        1 = TMENU
        1 {
            expAll = 1
            NO.ATagTitle.field = abstract
            NO.wrapItemAndSub = <li>|</li>
            NO.stdWrap.htmlSpecialChars = 1

            CUR < .NO
            CUR = 1
            CUR.allWrap = <strong>|</strong>
            CUR.stdWrap.htmlSpecialChars = 1

            ACT < .NO
            ACT = 1
            ACT.allWrap = <em>|</em>

            IFSUB < .NO
            IFSUB = 1

            ACTIFSUB < .NO
            ACTIFSUB = 1
            ACTIFSUB.allWrap = <em>|</em>
        }
        2 < .1
        2.wrap = <ul>|</ul>
        3 < .2
        4 < .2
    }
}


lib.nav_primary_home = COA
lib.nav_primary_home {
    wrap = <nav role="navigation"><h2 class="sr-hint">Hauptnavigation</h2><ul id="main-menu" class="sm sm-clean startmenue home"><li><strong><a href="/">Bundeshaushalt</a></strong><ul><li><a href="/#/2018/soll/einnahmen/einzelplan.html">Einnahmen</a></li><li><a href="/#/2018/soll/ausgaben/einzelplan.html">Ausgaben</a></li></ul></li>|</ul></nav>

    10 = HMENU
    10 {
        special = list
        special.value = {$bmfPage.NavPrimaryHome}
        includeNotInMenu = 1

        1 = TMENU
        1 {
            expAll = 1
            NO.ATagTitle.field = abstract
            NO.wrapItemAndSub = <li>|</li>
            NO.stdWrap.htmlSpecialChars = 1

            CUR < .NO
            CUR = 1
            CUR.allWrap = <strong>|</strong>
            CUR.stdWrap.htmlSpecialChars = 1

            ACT < .NO
            ACT = 1
            ACT.allWrap = <em>|</em>

            IFSUB < .NO
            IFSUB = 1

            ACTIFSUB < .NO
            ACTIFSUB = 1
            ACTIFSUB.allWrap = <em>|</em>
        }
        2 < .1
        2.wrap = <ul>|</ul>
        3 < .2
        4 < .2
    }
}
