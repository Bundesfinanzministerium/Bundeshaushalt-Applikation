config {

    // Administrator settings
    admPanel = {$config.adminPanel}
    debug    = {$config.debug}

    doctype = html5

    #htmlTag_setParams = none

    // Character sets
    renderCharset    = utf-8
    metaCharset      = utf-8

    // Cache settings
    cache_period     = 43200
    sendCacheHeaders = 1

    // Language Settings
    uniqueLinkVars          = 1
    linkVars               := addToList(L(1),type(3))
    sys_language_uid        = 0
    sys_language_overlay    = 1
    sys_language_mode       = content_fallback
    # pageTitleFirst          = 1
    language                = de
    locale_all              = de_DE.UTF-8
    htmlTag_langKey         = de

    // Link settings
    # absRefPrefix            = /
    prefixLocalAnchors      = all

    // Remove targets from links
    intTarget =
    extTarget =

    // Code cleaning
    disablePrefixComment  = 1

    // Move default CSS and JS to external file
    removeDefaultJS        = external
    inlineStyle2TempFile   = 1

    // compress and concatinate js / css
    compressJs     = {$config.compressJs}
    concatenateJs  = {$config.concatenateJs}
    compressCss    = {$config.compressCss}
    concatenateCss = {$config.concatenateCss}

    // Protect mail addresses from spamming
    spamProtectEmailAddresses = -3
    spamProtectEmailAddresses_atSubst = @<span style="display:none;">remove-this.</span>

    // Comment in the <head> tag
    #headerComment (
    #    TYPO3 Bundeshaushalt
    #    - - - - - - - - - - - -
    #)

    // RealUrl
    simulateStaticDocuments = 0
    tx_realurl_enable = 1

    moveJsFromHeaderToFooter = 0

    message_page_is_being_generated = <h1>Die Seite ###TITLE###<br />wird gerade generiert.</h1><p>Bitte habe ca. 10 Sekunden Geduld!</p>

    // Include Boilerplate handling for IE browsers
    htmlTag_stdWrap {
        setContentToCurrent = 1
        cObject = COA
        cObject {
            10 = LOAD_REGISTER
            10 {
                newLine.char = 10
                tagEnd {
                    current = 1
                    split.max = 2
                    split.token = <html
                    split.returnKey = 1
                }
            }
            20 = TEXT
            20.value = <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{register:tagEnd} <![endif]-->
            20.wrap = |{register:newLine}
            20.insertData = 1
            30 < .20
            30.value = <!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"{register:tagEnd} <![endif]-->
            40 < .20
            40.value = <!--[if IE 8]> <html class="no-js lt-ie9"{register:tagEnd} <![endif]-->
            50 < .20
            50.value = <!--[if gt IE 8]> <!--><html class="no-js"{register:tagEnd} <!--<![endif]-->
            90 = RESTORE_REGISTER
        }
    }
}

# Set baseURL setting for http or https
config.baseURL = http://{$config.domain}/
[globalString = IENV:TYPO3_SITE_URL=https://{$config.domain}/]
config.baseURL = https://{$config.domain}/
[end]

[browser = msie]
config.additionalHeaders = X-UA-Compatible: IE=Edge,chrome=1
[end]

[browser = msie6]
    config.baseURL >
    config.headerComment  = x -->  <base href="http://{$config.domain}/"></base> <!-- x
    config.xhtml_cleaning = 0
[end]
