function getOS() {
    const userAgent = window.navigator.userAgent || window.navigator.vendor || window.opera;

    if (/windows/i.test(userAgent)) {
        return 'Windows';
    } else if (/macintosh|mac os x/i.test(userAgent)) {
        return 'MacOS';
    } else if (/ipad|iphone|ipod/i.test(userAgent) && !window.MSStream) {
        return 'iOS';
    } else if (/android/i.test(userAgent)) {
        return 'Android';
    } else if (/linux/i.test(userAgent)) {
        return 'Linux';
    }

    return 'Unknown';
}