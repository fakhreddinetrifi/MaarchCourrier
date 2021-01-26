exports.config = {
    baseUrl: 'http://127.0.0.1/MaarchCourrier',
    seleniumAddress: 'http://localhost:4444/wd/hub',
    specs: [
        'login-spec.js',
        'index-resource-spec.js',
        //'about-us-spec.js'
    ],
    multiCapabilities: [
        {
            'browserName': 'chrome',
            'chromeOptions': {
                'args': ["--no-sandbox", "--headless", "--disable-gpu",  "--window-size=1920,1080"]
            },
        },
        {
            'browserName': 'firefox',
            'moz:firefoxOptions': {
                'args': ["--headless", "--width=1920", "--height=1080"]
            }
        }
    ],
    chromeDriver: '/usr/bin/chromedriver',
    maxSessions: 1,

    onPrepare: () => {
        browser.driver.getCapabilities().then(function(caps){
            browser.browserName = caps.get('browserName');
        });
    }
};
