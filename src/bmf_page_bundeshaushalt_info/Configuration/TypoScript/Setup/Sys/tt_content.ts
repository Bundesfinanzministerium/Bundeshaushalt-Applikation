# Only required for the "old" form element, not use for the new form wizard
tt_content.mailform.20.RADIO.layout = <div class="csc-mailform-field">###LABEL### ###FIELD###</div>
tt_content.mailform.20.radioWrap.accessibilityWrap = <fieldset###RADIO_FIELD_ID### class="csc-mailform-radio"><legend>###RADIO_GROUP_LABEL###</legend>|</fieldset>

tt_content {
    # Disable image rendering and jumpurl for filelist element
    uploads.20 {
        linkProc.combinedLink = 0
        itemRendering.10.data = register:linkedLabel
        linkProc.jumpurl >
    }

    # Make some modifications to the rendering of the standard MAIL form records
    mailform.20 {
        accessibility = 1
    }

    # Remove the ancient onfocus="blurLink(this);" from sitemap links
    # Unfortunately this hasn't been fully implemented in css_styled_content yet
    # See also bug 11367
    menu.20 {
        default.1.noBlur = 1
        1.1.noBlur = 1
        4.1.noBlur = 1
        5.1.noBlur = 1
        6.1.noBlur = 1
        7.1.noBlur = 1
        7.2.noBlur = 1
    }

    stdWrap.innerWrap.cObject = CASE
    stdWrap.innerWrap.cObject {
        key.field = layout
        default=TEXT
        default.value = |
        1 = TEXT
        1.value = <div class="box">|</div>
        2 = TEXT
        2.value = <div class="box news">|</div>
        3 = TEXT
        3.value = <div class="box download">|</div>
        4 = TEXT
        4.value = <div class="box link-list">|</div>
        5 = TEXT
        5.value = <div class="box info">|</div>
        6 = TEXT
        6.value = <blockquote class="box citation">|</blockquote>
        7 = TEXT
        7.value = <div class="link-list-only">|</div>
    }

    # Change rendering for CType "textpic" when "layout" has changed to "Headline-Overlay" (10)

    textpic.20 {
        text.wrap.stdWrap.override = <div class="csc-textpic-text copy">|</div>
        text.wrap.stdWrap.override.if < temp.headline-overlay-if

        rendering.singleNoCaption.allStdWrap.stdWrap {
            dataWrap = <h3>{field:header}</h3>|
            outerWrap = <div class="image">|</div>

            dataWrap.stdWrap.if < temp.headline-overlay-if
            outerWrap.stdWrap.if < temp.headline-overlay-if
        }
        stdWrap {
            wrap = <div class="teaser">|</div>
            wrap.stdWrap.if < temp.headline-overlay-if
        }
    }

}

temp.headline-overlay-if {
    value.field = layout
    equals = 10
}
