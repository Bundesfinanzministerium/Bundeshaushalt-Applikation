page.meta {

  // http://blog.sbtheke.de/web-development/typo3/meta-keywords-und-description

  description {
    data = page:description
    ifEmpty.data = levelfield :-1, description, slide
  }

  keywords {
    data = page:keywords
    ifEmpty.data = levelfield :-1, keywords, slide
  }

  title {
    data = page:title
  }

  author {
    data = levelfield :-1, author, slide
  }

  date {
    data = page:SYS_LASTCHANGED
    date = Y-m-d
  }

  robots        = {$bmfPage.meta.robots}
  copyright     = {$bmfPage.meta.copyright}
  distribution  = {$bmfPage.meta.distribution}
  rating        = {$bmfPage.meta.rating}
  revisit-after = {$bmfPage.meta.revisit}

}# Add Open Graph meta image
page.headerData {
  1 = COA
  1 {
    10 = FILES
    10 {
      references {
        table = pages
        uid.data = page:uid
        fieldName = media
      }
      renderObj = TEXT
      renderObj {
        typolink {
          parameter.data = file:current:publicUrl
          forceAbsoluteUrl = 1
          returnLast = url
        }
        wrap = |,
      }
      stdWrap {
        listNum = 0
        ifEmpty.cObject = TEXT
        ifEmpty.cObject.typolink {
          parameter = typo3conf/ext/bmf_page_bundeshaushalt_info/Resources/Public/Images/datacenter.jpg
          forceAbsoluteUrl = 1
          returnLast = url
        }
        wrap = <meta property="og:image" content="|">
      }
    }
  }
}
