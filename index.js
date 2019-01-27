const electorn = require('electron');

process.env['ELECTRON_DISABLE_SECURITY_WARNINGS'] = 'true';

const {app, BrowserWindow} = electorn;

app.on('ready', () => {
    var main_window = new BrowserWindow(
        {
            // fullscreen: true,
            width: 1366,
            height: 768,
            backgroundColor: '#312450'
        });
    // main_window.once('ready-to-show', () => {
    // 	mainWindow.show();
    // })

    main_window.loadURL('http://management-software.test');
});

app.on('close-me', (evt, arg) => {
    app.quit();
})

