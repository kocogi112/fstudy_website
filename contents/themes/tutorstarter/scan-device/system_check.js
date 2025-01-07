function getOS() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (navigator.userAgent.indexOf("Win") != -1) 
        os =   "Windows"; 
    else if (navigator.userAgent.indexOf("Mac") != -1) 
        os =  "Macintosh"; 
    else if (navigator.userAgent.indexOf("Linux") != -1) 
        os =  "Linux"; 
    else if (navigator.userAgent.indexOf("Android") != -1) 
        os =  "Android"; 
    else if (navigator.userAgent.indexOf("like Mac") != -1) 
        os =  "iOS"; 
    else os= "Unknown OS";
    console.log("Thiết bị hiện tại", os)

}