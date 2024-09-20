function checkLocationAndIpAdress(){

    fetch("https://ipinfo.io/json?token=1e3887629fcd4e").then(
        (response) => response.json()
    ).then(
        (jsonResponse) => console.log(jsonResponse.ip, jsonResponse.country)
    )
}