const electorn = require('electron');

process.env['ELECTRON_DISABLE_SECURITY_WARNINGS'] = 'true';

const {app, BrowserWindow} = electorn;

app.on('ready', () => {
    var main_window = new BrowserWindow(
        {
            // fullscreen: true,
            width: 1440,
            height: 900,
            backgroundColor: '#312450'
        });
    // main_window.once('ready-to-show', () => {
    // 	mainWindow.show();
    // })

    main_window.loadURL('http://system-it.test');
});

app.on('close-me', (evt, arg) => {
    app.quit();
})
