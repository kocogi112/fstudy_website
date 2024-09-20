function getOS() {
    let userAgent = window.navigator.userAgent;
    let platform = window.navigator.platform;
    

    // Check for iOS
    if (/iPhone|iPad|iPod/.test(userAgent)) {
        os = 'iOS';
    }
    // Check for Windows
    else if (platform.startsWith('Win')) {
        os = 'Windows';
    }
    // Check for macOS
    else if (platform.startsWith('Mac')) {
        os = 'macOS';
    }
    // Check for Linux
    else if (platform.startsWith('Linux')) {
        os = 'Linux';
    }
    // Check for Android
    else if (/Android/.test(userAgent)) {
        os = 'Android';
    } 
    // If OS can't be identified
    else {
        os = 'Unknown OS';
    }

    console.log(`Device Os: ${os}`);
   // return os;
}
