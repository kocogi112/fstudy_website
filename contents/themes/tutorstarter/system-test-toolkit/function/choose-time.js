function setTimeLimit(value) {
    countdownValue = parseInt(value);
    document.getElementById('countdown').innerHTML = secondsToHMS(countdownValue);
}