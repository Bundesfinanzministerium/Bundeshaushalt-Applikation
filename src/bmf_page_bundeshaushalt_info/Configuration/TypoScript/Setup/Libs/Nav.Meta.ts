lib.metanav = HMENU
lib.metanav {

    wrap = <ul role="tablist"><li><a href="#feedback" id="feedbackSwitch" role="tab" aria-controls="contact">Feedback<span class="sr-hint"> Formular öffnen</span></a></li>|</ul>

    special = directory
    special.value = {$globalPids.metaNavigation}

    1 = TMENU

    1 {

        noBlur = 1

        NO = 1
        NO.linkWrap = <li>|</li>
        NO.stdWrap.wrap = |

        CUR = 1
        CUR < .NO
        CUR.linkWrap = <li>|</li>
        CUR.stdWrap.wrap = <span class="selected"><dfn class="sr-hint">Aktuelle Seite: </dfn>|</span>
        CUR.doNotLinkIt = 1

        ACT = 1
        ACT < .NO
        ACT.ATagParams = class="selected"

    }
}
