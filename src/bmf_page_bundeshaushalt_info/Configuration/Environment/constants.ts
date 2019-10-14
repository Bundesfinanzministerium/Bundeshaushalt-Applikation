# Stage: Development
[applicationContext == "Development"]
    enable.google.verification = 0
    config.domain = www.bundeshaushalt.vbox
    config.piwik.enable = 0
[end]

[applicationContext == "Development/vm-bmf-bundeshaushalt"]
    enable.google.verification = 0
    config.domain = www.bundeshaushalt.vbox
    config.piwik.enable = 1
[end]

[applicationContext == "Development/dev-bundeshaushalt"]
    enable.google.verification = 0
    config.domain = www.bundeshaushalt.vbox
[end]


# Stage: Test
[applicationContext == "piwik"]
    enable.google.verification = 0
    config.domain = test-web02-bundeshaushalt.pixelpark.net
    config.piwik.enable = 1
[end]


# Stage: Production
[applicationContext == "Production"]
    enable.google.verification = 1
    config.domain = www.bundeshaushalt.de
    config.piwik.enable = 1
[end]
