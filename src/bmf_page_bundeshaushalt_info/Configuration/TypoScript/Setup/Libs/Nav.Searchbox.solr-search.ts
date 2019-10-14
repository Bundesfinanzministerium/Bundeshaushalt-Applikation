# Create the searchbox in TypoScript, so we can easily include in on every page
env.searchbox.solr = COA

# Make the searchbox remember the search term on the search result page
# TODO: Magic number
[page["uid"] == 23]
    env.searchbox.solr = COA_INT
[end]

env.searchbox.solr {
    stdWrap.prefixComment = 2 | lib.searchbox
    10 = TEXT
    10.typolink.parameter = {$globalPids.search}
    10.typolink.returnLast = url
    10.wrap = <form action="|" method="get"><label for="inputText" class="sr-hint">Stichwortsuche</label>
    20 = COA
    20 {
        10 = TEXT
        10.data = GP : q
        10.htmlSpecialChars = 1
        10.wrap = <input name="q" value="|" id="inputText" type="text" placeholder="Suchbegriff" />
        20 = COA
        20 {
            10 = TEXT
            10.value = <input type="image" src="/typo3conf/ext/bmf_page_bundeshaushalt_info/Resources/Public/Images/lupe.png" alt="Suchen" title="Suchen" class="search" id="inputButton" value="honk" />
            # 10.value = <input type="submit" alt="Suchen" title="Suchen" class="search" id="inputButton" value="Suchen" />
            20 = TEXT
            20.value = <input type="hidden" name="id" value="23" />
            30 = TEXT
            30.value = <input type="hidden" name="L" value="0" />
        }
    }
    wrap = |</form>
}
