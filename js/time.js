const interval = setInterval(setTime,1000)
/**
 * sets the contents of the "time" element to the current time
 */
function setTime() {
    $("#time").html(getDateString());
}
/**
 * returns the current time as String
 * @returns {string}
 */
function getDateString() {
    let date = new Date();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();

    return  addLeadingZero(hours) + ":" + addLeadingZero(minutes) + ":" + addLeadingZero(seconds);
}

/**
 * turns Numbers to Strings and adds leading zero to one-digit numbers
 */
function addLeadingZero(string) {
    string = string.toString();
    if (string.length < 2) {
       string = "0" + string;
    }
    return string;
}